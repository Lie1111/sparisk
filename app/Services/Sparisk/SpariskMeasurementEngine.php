<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;
use App\Models\PostureSeverityBand;
use App\Models\PostureSetting;

/**
 * The single SATA deviation engine.
 *
 * Every posture reading — on the photo overlay, the result table, the database
 * row and the PDF report — is produced here, from one calculation:
 *
 *      Deviation Angle = Measured Angle − Applicable Reference Value
 *
 * The reference is resolved through the locked evidence hierarchy
 * (Level 1 published → Level 2 SATA validated → Level 3 individual baseline).
 * When no approved reference exists the parameter is an Evidence Gap: the
 * first assessment is stored as the individual baseline and later assessments
 * report the CHANGE from that baseline. No population cut-off is invented, and
 * a point status (On Point / Near Point / Off Point) is only produced when an
 * approved reference — with its own published tolerance — exists.
 */
class SpariskMeasurementEngine
{
    /**
     * Process raw MediaPipe measurements and store the deviation rows.
     */
    public function process(array $landmarks, PostureAssessment $assessment): array
    {
        $config = config('sparisk.measurements');

        $ageGroup = $this->resolveAgeGroup($assessment->patient?->age);
        $sex = $this->normaliseSex($assessment->patient?->gender);
        $references = $this->loadReferences($ageGroup, $sex);
        $baselines = $this->loadBaselines($assessment);

        $measurements = [];

        foreach ($landmarks as $section => $value) {
            if (! isset($config[$section])) {
                continue;
            }

            $def = $config[$section];
            $key = $def['view'] . '.' . $section;

            $measurements[] = $this->evaluate(
                $assessment,
                $section,
                $def,
                (float) $value,
                $references[$key] ?? null,
                $ageGroup,
                $sex,
                $baselines[$key] ?? null,
            );
        }

        PostureMeasurement::insert($measurements);

        return $measurements;
    }

    /**
     * Evaluate one raw measurement into a storable deviation record.
     *
     * `$setting` is the resolved reference (NULL when the parameter has no
     * row); `$baseline` is the individual's first-assessment measured angle for
     * this section, used only when the parameter is an Evidence Gap.
     */
    public function evaluate(
        PostureAssessment $assessment,
        string $section,
        array $def,
        float $rawValue,
        ?PostureSetting $setting,
        string $ageGroup,
        ?string $sex,
        ?float $baseline
    ): array {
        $view = $def['view'];
        $referenceLevel = $setting?->reference_level;
        $sexUsed = ($setting?->sex_if_required ?? false) ? $sex : null;

        $row = [
            'posture_assessment_id' => $assessment->id,
            'view' => $view,
            'section' => $section,
            'label' => $setting?->label ?: $def['label'],
            'value' => is_finite($rawValue) ? round($rawValue, 2) : 0.0,
            'measured_angle' => null,
            'reference_value' => null,
            'deviation' => null,
            'deviation_angle' => null,
            'deviation_direction' => null,
            'unit' => '°',
            'severity' => null,
            'alignment_status' => 'review',
            'position_note' => null,
            'review_required' => true,
            'status_text' => config('sparisk.alignment_status_descriptions.review'),
            'status_code' => 'review',
            'reference_source' => $setting?->reference_source,
            'reference_level' => $referenceLevel,
            'age_group' => $ageGroup,
            'sex_if_required' => $sexUsed,
            'posture_classification' => null,
        ];

        // Guard against landmarks that could not be resolved (NaN / INF) or
        // that produced an impossible angle for this measurement.
        if (! is_finite($rawValue) || abs($rawValue) > 360) {
            return $row;
        }

        $measured = $this->normaliseAngle($rawValue, $def);
        $row['value'] = round($measured, 2);
        $row['measured_angle'] = round($measured, 2);

        // Evidence Gap — no approved reference: track change from the
        // individual baseline instead of inventing a cut-off.
        if ($setting === null || $setting->reference_value === null || ! $setting->hasApprovedReference()) {
            return $this->baselineRow($row, $def, $measured, $baseline);
        }

        $reference = $this->normaliseAngle((float) $setting->reference_value, $def);
        $signed = $this->signedDeviation($measured, $reference, $def);
        $deviation = round(abs($signed), 2);

        $statusCode = $this->resolveOutputStatus($deviation, $setting);
        $level = config("sparisk.status_to_level.{$statusCode}", 'review');
        $isAbove = $signed > 0;

        $row['reference_value'] = round($reference, 2);
        $row['deviation_angle'] = $signed;
        $row['deviation'] = $deviation;
        $row['deviation_direction'] = $statusCode === 'on_point' ? null : ($isAbove ? 'above' : 'below');
        $row['severity'] = $statusCode === 'on_point' ? null : $level;
        $row['alignment_status'] = $level;
        $row['review_required'] = $statusCode === 'review';
        $row['status_code'] = $statusCode;
        $row['position_note'] = $statusCode === 'on_point' ? null : $this->positionNote($section, $def, $isAbove);
        $row['status_text'] = $this->interpretation($section, $def, $level, $isAbove);

        return $row;
    }

    /**
     * Build an Evidence Gap row: the deviation is the change from the
     * individual's baseline, and the status is Baseline / Change Tracking.
     */
    private function baselineRow(array $row, array $def, float $measured, ?float $baseline): array
    {
        $row['alignment_status'] = 'baseline';
        $row['severity'] = null;
        $row['review_required'] = false;
        $row['status_code'] = 'baseline_tracking';

        if ($baseline === null) {
            // First assessment: this reading becomes the baseline.
            $row['reference_value'] = null;
            $row['deviation_angle'] = 0.0;
            $row['deviation'] = 0.0;
            $row['deviation_direction'] = null;
            $row['position_note'] = 'Stored as the individual baseline';
            $row['status_text'] = 'No approved reference yet — this first assessment is stored as the individual baseline.';

            return $row;
        }

        $change = round($measured - $baseline, 2);
        $row['reference_value'] = round($baseline, 2);
        $row['deviation_angle'] = $change;
        $row['deviation'] = round(abs($change), 2);
        $row['deviation_direction'] = $change == 0.0 ? null : ($change > 0 ? 'above' : 'below');
        $row['position_note'] = $change == 0.0
            ? 'No change from baseline'
            : sprintf('Change of %s° from baseline', round(abs($change), 2));
        $row['status_text'] = sprintf(
            'No approved reference yet — change of %s° %s from the individual baseline.',
            round(abs($change), 2),
            $change > 0 ? 'above' : 'below',
        );

        return $row;
    }

    /**
     * The tolerance a stored row was judged against: the published half-width
     * of its reference, resolved exactly the way the engine resolves it when it
     * writes the row. NULL means the parameter carries no approved band, so no
     * point status can be claimed for it.
     */
    public function toleranceFor(PostureMeasurement $measurement): ?float
    {
        return $this->referenceSettingFor($measurement)?->tolerance();
    }

    /**
     * The reference row behind a stored measurement, resolved from the age
     * group and sex the row was written with.
     */
    public function referenceSettingFor(PostureMeasurement $measurement): ?PostureSetting
    {
        if ($measurement->age_group === null || $measurement->view === null || $measurement->section === null) {
            return null;
        }

        $key = $measurement->view.'.'.$measurement->section;

        return $this->loadReferences($measurement->age_group, $measurement->sex_if_required)[$key] ?? null;
    }

    /**
     * Resolve a patient age to a SATA age group key.
     */
    public function resolveAgeGroup(?int $age): string
    {
        $groups = config('sparisk.age_groups');

        if ($age !== null) {
            foreach ($groups as $key => $group) {
                if ($age >= $group['min'] && ($group['max'] === null || $age <= $group['max'])) {
                    return $key;
                }
            }
        }

        return '19-49';
    }

    /**
     * Translate an assessment's `posture_classification` into the slug the
     * Intervention Recommendations catalogue is keyed by.
     */
    public function resolveInterventionPostureType(?string $classification): string
    {
        $map = config('sparisk.intervention_classification_map', []);

        return $map[$classification] ?? config('sparisk.intervention_fallback_posture_type', 'normal_neutral');
    }

    /**
     * Normalise an angle according to the measurement's `angle_mode`.
     */
    public function normaliseAngle(float $angle, array $def): float
    {
        if (($def['angle_mode'] ?? 'angle') !== 'orientation') {
            return $angle;
        }

        $normalised = fmod($angle, 180.0);
        if ($normalised > 90.0) {
            $normalised -= 180.0;
        }
        if ($normalised <= -90.0) {
            $normalised += 180.0;
        }

        return round($normalised, 2);
    }

    /**
     * Signed deviation between a value and its reference, using the shortest
     * arc so that a value near ±180° is not reported as a severe deviation.
     */
    public function signedDeviation(float $value, float $reference, array $def): float
    {
        $delta = $value - $reference;

        if (($def['angle_mode'] ?? 'angle') === 'orientation') {
            return round($delta, 2);
        }

        $delta = fmod($delta + 180.0, 360.0);
        if ($delta < 0) {
            $delta += 360.0;
        }

        return round($delta - 180.0, 2);
    }

    /**
     * Map the size of the deviation to the user-facing output status.
     *
     * The bands are a multiple of the reference's own published tolerance, so
     * no arbitrary universal cut-off (±5° / ±10°) is ever applied. An approved
     * reference without a published band cannot carry a point status.
     */
    public function resolveOutputStatus(float $deviation, PostureSetting $setting): string
    {
        $tolerance = $setting->tolerance();

        if ($tolerance === null || $tolerance <= 0) {
            return 'review';
        }

        $bands = config('sparisk.deviation_bands');

        if ($deviation <= $tolerance * ($bands['on_point'] ?? 1.0)) {
            return 'on_point';
        }

        if ($deviation <= $tolerance * ($bands['near_point'] ?? 2.0)) {
            return 'near_point';
        }

        return 'off_point';
    }

    /**
     * Build the plain-language interpretation for a measurement.
     */
    public function interpretation(string $section, array $def, string $status, bool $isAbove): string
    {
        $descriptions = config('sparisk.alignment_status_descriptions');

        if ($status === 'review') {
            return $descriptions['review'];
        }

        if ($status === 'normal') {
            return $descriptions['normal'];
        }

        $adverb = config("sparisk.deviation_adverbs.{$status}", '');

        return $this->fillTemplate($this->semanticsTemplate($section, $isAbove), $def, $adverb);
    }

    /**
     * Build the short Position Note shown next to the alignment status.
     */
    public function positionNote(string $section, array $def, bool $isAbove): ?string
    {
        $note = $this->fillTemplate($this->semanticsTemplate($section, $isAbove), $def, '');
        $note = trim(preg_replace('/\s+/', ' ', $note));
        $note = preg_replace('/^The\s+/i', '', $note);
        $note = rtrim($note, '.');

        return $note === '' ? null : ucfirst($note);
    }

    /**
     * Load the reference rows for an age group keyed by "view.section".
     *
     * A sex-specific row (for example the male/female Pelvic Tilt reference)
     * wins over the neutral row when the patient's sex is known.
     */
    private function loadReferences(string $ageGroup, ?string $sex): array
    {
        $map = [];

        foreach (PostureSetting::where('age_group', $ageGroup)->get() as $setting) {
            $key = $setting->view . '.' . $setting->section;

            if ($setting->sex === null) {
                $map[$key] ??= $setting;
                continue;
            }

            if ($sex !== null && strtolower((string) $setting->sex) === $sex) {
                $map[$key] = $setting;
            }
        }

        return $map;
    }

    /**
     * The individual's first-assessment measured angle per "view.section",
     * used as the baseline for Evidence Gap parameters.
     */
    private function loadBaselines(PostureAssessment $assessment): array
    {
        $patient = $assessment->patient;

        if ($patient === null) {
            return [];
        }

        $first = $patient->postureAssessments()
            ->where('id', '<', $assessment->id)
            ->orderBy('id')
            ->first();

        if ($first === null) {
            return [];
        }

        return $first->measurements()
            ->whereNotNull('measured_angle')
            ->get()
            ->mapWithKeys(fn (PostureMeasurement $m) => [
                $m->view . '.' . $m->section => (float) $m->measured_angle,
            ])
            ->toArray();
    }

    /**
     * Normalise the free-text patient gender to the reference's male/female key.
     */
    public function normaliseSex(mixed $gender): ?string
    {
        if (! is_string($gender)) {
            return null;
        }

        $value = strtolower(trim($gender));
        $aliases = [
            'male' => 'male', 'm' => 'male', 'boy' => 'male', 'lelaki' => 'male',
            'female' => 'female', 'f' => 'female', 'girl' => 'female', 'perempuan' => 'female',
        ];

        return $aliases[$value] ?? (in_array($value, ['male', 'female'], true) ? $value : null);
    }

    private function semanticsTemplate(string $section, bool $isAbove): string
    {
        $semantics = config("sparisk.measurement_semantics.{$section}")
            ?? config('sparisk.measurement_semantics._default');

        return $semantics[$isAbove ? 'positive' : 'negative'];
    }

    private function fillTemplate(string $template, array $def, string $adverb): string
    {
        return str_replace([':degree', ':label'], [$adverb, $def['label']], $template);
    }

    /**
     * Classify a deviation value against the fixed SATA severity bands.
     */
    public function classifyDeviation(float $deviation): array
    {
        foreach (PostureSeverityBand::orderedConfig() as $band) {
            if ($deviation >= $band['min'] && ($band['max'] === null || $deviation < $band['max'])) {
                return ['level' => $band['level'], 'label' => $band['label']];
            }
        }

        return ['level' => 'severe', 'label' => 'SEVERE'];
    }

    /**
     * Calculate overall posture score from measurements.
     *
     * Measurements flagged for review are excluded because their deviation is
     * not reliable enough to be scored. Evidence Gap (baseline) readings carry
     * no population deviation and therefore do not penalise the score.
     */
    public function calculateOverallScore(PostureAssessment $assessment): int
    {
        $measurements = $assessment->measurements;
        if ($measurements->isEmpty()) {
            return 100;
        }

        $penalties = 0;

        foreach ($measurements as $m) {
            if ($m->review_required || $m->alignment_status === 'baseline') {
                continue;
            }

            $deviation = (float) ($m->deviation ?? abs($m->value));
            if ($deviation <= 1) {
                continue;
            }
            if ($deviation <= 5) {
                $penalties += 1;
            } elseif ($deviation <= 10) {
                $penalties += 2;
            } elseif ($deviation <= 20) {
                $penalties += 4;
            } else {
                $penalties += 6;
            }
        }

        return min(100, max(0, 100 - $penalties));
    }
}
