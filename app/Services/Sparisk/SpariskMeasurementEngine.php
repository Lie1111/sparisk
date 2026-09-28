<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;
use App\Models\PostureSeverityBand;
use App\Models\PostureSetting;

class SpariskMeasurementEngine
{
    /**
     * Process raw MediaPipe measurements and return structured data.
     *
     * Each measured value is validated and angle-normalised before it is
     * compared against the SATA age-based reference for the patient's age
     * group. The signed deviation is mapped to a fixed SATA severity band and
     * to a user-facing alignment status (Aligned / Slightly Misaligned /
     * Misaligned / Clearly Misaligned / Check Measurement).
     *
     * Measurements that cannot be trusted (missing landmark, impossible angle,
     * missing reference) are stored with `alignment_status = review` and
     * `review_required = true` instead of a severity, so they never drive a
     * posture diagnosis.
     */
    public function process(array $landmarks, PostureAssessment $assessment): array
    {
        $measurements = [];
        $config = config('sparisk.measurements');

        $ageGroup = $this->resolveAgeGroup($assessment->patient?->age);
        $references = $this->loadReferences($ageGroup);

        foreach ($landmarks as $section => $value) {
            if (!isset($config[$section])) continue;

            $def = $config[$section];
            $referenceValue = $references[$def['view'] . '.' . $section] ?? $this->fallbackReference($def);

            $measurements[] = $this->evaluate($assessment, $section, $def, (float) $value, (float) $referenceValue);
        }

        PostureMeasurement::insert($measurements);

        return $measurements;
    }

    /**
     * Evaluate a single raw measurement into a storable alignment record.
     */
    public function evaluate(
        PostureAssessment $assessment,
        string $section,
        array $def,
        float $rawValue,
        float $referenceValue
    ): array {
        $reviewText = config('sparisk.alignment_status_descriptions.review');

        $row = [
            'posture_assessment_id' => $assessment->id,
            'view' => $def['view'],
            'section' => $section,
            'label' => $def['label'],
            'value' => round($rawValue, 2),
            'reference_value' => round($referenceValue, 2),
            'deviation' => null,
            'deviation_direction' => null,
            'unit' => '°',
            'severity' => null,
            'alignment_status' => 'review',
            'position_note' => null,
            'review_required' => true,
            'status_text' => $reviewText,
        ];

        // Guard against landmarks that could not be resolved (NaN / INF) or
        // that produced an impossible angle for this measurement.
        if (!is_finite($rawValue) || abs($rawValue) > 360) {
            return $row;
        }

        $value = $this->normaliseAngle($rawValue, $def);
        $reference = $this->normaliseAngle($referenceValue, $def);
        $signed = $this->signedDeviation($value, $reference, $def);
        $deviation = round(abs($signed), 2);

        $status = $this->resolveAlignmentStatus($value, $deviation, $def);
        $isAbove = $signed >= 0;

        $row['value'] = round($value, 2);
        $row['reference_value'] = round($reference, 2);
        $row['deviation'] = $deviation;
        $row['deviation_direction'] = $status === 'normal' ? null : ($isAbove ? 'above' : 'below');
        $row['severity'] = $status === 'review' ? null : $status;
        $row['alignment_status'] = $status;
        $row['review_required'] = $status === 'review';
        $row['position_note'] = $status === 'normal' ? null : $this->positionNote($section, $def, $isAbove);
        $row['status_text'] = $this->interpretation($section, $def, $status, $isAbove);

        return $row;
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
     *
     * An unrecognised classification (an UNCLASSIFIED result, or a pattern the
     * admin hasn't authored yet) resolves to the configured neutral fallback so
     * the admin-authored programme is still shown.
     */
    public function resolveInterventionPostureType(?string $classification): string
    {
        $map = config('sparisk.intervention_classification_map', []);

        return $map[$classification] ?? config('sparisk.intervention_fallback_posture_type', 'normal_neutral');
    }

    /**
     * Normalise an angle according to the measurement's `angle_mode`.
     *
     * `orientation` measures are line orientations modulo 180°, so -179.97°
     * and +0.03° describe the same alignment and are reduced to the same
     * value (-90°, +90°]. `angle` measures keep their full-circle sign because
     * the direction (left/right, forward/back) is clinically meaningful.
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
     * Compare the (normalised) value against the measurement's target range and
     * fall back to the deviation band when it sits outside the range.
     */
    public function resolveAlignmentStatus(float $value, float $deviation, array $def): string
    {
        $min = $def['normal_min'] ?? null;
        $max = $def['normal_max'] ?? null;

        if ($min !== null && $max !== null && $value >= $min && $value <= $max) {
            return 'normal';
        }

        return $this->classifyDeviation($deviation)['level'];
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
     * Load the reference values for a given age group keyed by "view.section".
     */
    private function loadReferences(string $ageGroup): array
    {
        return PostureSetting::where('age_group', $ageGroup)
            ->get()
            ->mapWithKeys(fn (PostureSetting $setting) => [
                $setting->view . '.' . $setting->section => (float) $setting->reference_value,
            ])
            ->toArray();
    }

    /**
     * Reference used when the SATA table has no row for a section. The midpoint
     * of the target range is used so measures with a non-zero target (e.g.
     * thoracic kyphosis) are not compared against zero.
     */
    private function fallbackReference(array $def): float
    {
        $min = $def['normal_min'] ?? 0;
        $max = $def['normal_max'] ?? 0;

        if ($min <= 0 && $max >= 0) {
            return 0.0;
        }

        return round(($min + $max) / 2, 2);
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
     * not reliable enough to be scored.
     */
    public function calculateOverallScore(PostureAssessment $assessment): int
    {
        $measurements = $assessment->measurements;
        if ($measurements->isEmpty()) return 100;

        $penalties = 0;

        foreach ($measurements as $m) {
            if ($m->review_required) continue;

            $deviation = (float) ($m->deviation ?? abs($m->value));
            if ($deviation <= 1) continue;
            if ($deviation <= 5) $penalties += 1;
            elseif ($deviation <= 10) $penalties += 2;
            elseif ($deviation <= 20) $penalties += 4;
            else $penalties += 6;
        }

        return min(100, max(0, 100 - $penalties));
    }
}
