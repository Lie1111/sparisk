<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;

class SpariskInterpretationEngine
{
    public function __construct(private SpariskMeasurementEngine $measurementEngine) {}

    /**
     * Interpret biomechanical measurements and produce clinical interpretations.
     *
     * Every measurement is reported with its user-facing alignment status and a
     * dynamic English interpretation derived from the actual value, target
     * reference and direction of deviation.
     */
    public function interpret(PostureAssessment $assessment): array
    {
        $statuses = config('sparisk.alignment_statuses');
        $outputStatuses = config('sparisk.posture_output_statuses');

        return $assessment->measurements()->get()
            ->map(function (PostureMeasurement $m) use ($statuses, $outputStatuses) {
                $status = $this->resolveStatus($m);
                $meta = $statuses[$status] ?? $statuses['review'];
                $statusCode = $this->resolveStatusCode($m, $status);
                $outputMeta = $outputStatuses[$statusCode] ?? $outputStatuses['review'];

                return [
                    'section' => $m->section,
                    'view' => $m->view,
                    'label' => $m->label,
                    // The body region this reading grades, so a muscle marker
                    // can be coloured from the worst finding in its region.
                    'region' => config("sparisk.measurements.{$m->section}.region"),
                    // Raw anatomical angle, kept for the database/audit trail only.
                    'value' => (float) $m->value,
                    'measured_angle' => $m->measured_angle !== null ? (float) $m->measured_angle : (float) $m->value,
                    // SATA user-facing output: the deviation from the applicable reference.
                    'deviation_angle' => $m->deviation_angle !== null ? (float) $m->deviation_angle : null,
                    'reference_value' => $m->reference_value !== null ? (float) $m->reference_value : null,
                    'deviation' => $m->deviation !== null ? (float) $m->deviation : null,
                    'deviation_direction' => $m->deviation_direction,
                    'alignment_status' => $status,
                    'alignment_label' => $meta['label'],
                    'alignment_color' => $meta['color'],
                    'status_code' => $statusCode,
                    'status_label' => $outputMeta['label'],
                    'status_color' => $outputMeta['color'],
                    'status_correction' => $outputMeta['correction'],
                    'reference_source' => $m->reference_source,
                    'reference_level' => $m->reference_level,
                    'tolerance' => $this->measurementEngine->toleranceFor($m),
                    // The shared axis/landmarks/sign convention for this
                    // measurement, so the app never describes a reading
                    // differently from the engine.
                    'definition' => $this->measurementEngine->definitionFor((string) $m->section),
                    'age_group' => $m->age_group,
                    'sex_if_required' => $m->sex_if_required,
                    'posture_classification' => $m->posture_classification,
                    'position_note' => $m->position_note,
                    'interpretation' => $this->describe($m, $status),
                    'review_required' => (bool) $m->review_required,
                ];
            })
            ->toArray();
    }

    /**
     * Measurements enriched with the user-facing alignment status, ready for the
     * presentation layers. Keeps every consumer from having to re-derive the
     * alignment wording from the stored severity levels.
     */
    public function enrichMeasurements(PostureAssessment $assessment): array
    {
        $interpreted = collect($this->interpret($assessment))->keyBy('section');

        return $assessment->measurements
            ->map(function (PostureMeasurement $m) use ($interpreted) {
                $details = $interpreted->get($m->section);

                return array_merge($m->toArray(), [
                    // The body region this reading grades. The presentation
                    // layers colour a muscle from the worst finding in its
                    // region, so this has to travel with every payload the app
                    // reads — `interpret()` sets it for the save response, and
                    // dropping it here is what left the app's character view
                    // showing every muscle as normal.
                    'region' => $details['region'] ?? null,
                    'alignment_status' => $details['alignment_status'] ?? $m->alignment_status,
                    'alignment_label' => $details['alignment_label'] ?? null,
                    'alignment_color' => $details['alignment_color'] ?? null,
                    'status_code' => $details['status_code'] ?? $m->status_code,
                    'status_label' => $details['status_label'] ?? null,
                    'status_color' => $details['status_color'] ?? null,
                    'status_correction' => $details['status_correction'] ?? null,
                    'deviation_angle' => $details['deviation_angle'] ?? $m->deviation_angle,
                    'reference_value' => $details['reference_value'] ?? $m->reference_value,
                    'reference_source' => $details['reference_source'] ?? $m->reference_source,
                    'reference_level' => $details['reference_level'] ?? $m->reference_level,
                    'tolerance' => $details['tolerance'] ?? $this->measurementEngine->toleranceFor($m),
                    'definition' => $details['definition'] ?? $this->measurementEngine->definitionFor((string) $m->section),
                    'age_group' => $details['age_group'] ?? $m->age_group,
                    'sex_if_required' => $details['sex_if_required'] ?? $m->sex_if_required,
                    'position_note' => $details['position_note'] ?? $m->position_note,
                    'interpretation' => $details['interpretation'] ?? $m->status_text,
                    'review_required' => (bool) $m->review_required,
                ]);
            })
            ->values()
            ->toArray();
    }

    /**
     * The enriched measurements grouped by view.
     */
    public function measurementsByView(PostureAssessment $assessment): array
    {
        $enriched = collect($this->enrichMeasurements($assessment));

        $viewMeasurements = [];
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $viewMeasurements[$view] = $enriched
                ->where('view', $view)
                ->values()
                ->toArray();
        }

        return $viewMeasurements;
    }

    /**
     * The posture classification as presented to users. An unclassified posture
     * is never shown as a diagnosis — it is presented as a review request.
     *
     * The stored keys (`Kyphosis`, `Swayback`, …) stay untouched for the
     * decision rules, the database and research; only `display` and the labels
     * inside `classifications` are worded for users.
     */
    public function classificationSummary(PostureAssessment $assessment): array
    {
        $unclassified = config('sparisk.unclassified');
        $isUnclassified = $assessment->posture_classification === $unclassified['code']
            || $assessment->review_status === $unclassified['review_status'];

        return [
            'code' => $assessment->posture_classification,
            'display' => $isUnclassified
                ? $unclassified['display']
                : $this->patternLabel($assessment->posture_classification),
            'primary_label' => $isUnclassified
                ? null
                : $this->patternLabel($assessment->posture_classification),
            // The Main Finding is the CONFIRMED primary classification, never
            // the suspected pattern. The suspected pattern is reported
            // separately so a single uncertain/incorrect parameter can no longer
            // appear as the main diagnosis (@see FLATBACK regression).
            'main_finding' => $this->mainFinding($assessment, $isUnclassified),
            'review_status' => $assessment->review_status,
            'review_required' => $isUnclassified,
            'suspected_pattern' => $assessment->suspected_pattern,
            'suspected_pattern_label' => $this->patternTokenLabel($assessment->suspected_pattern),
            'secondary_pattern' => $assessment->secondary_pattern,
            'secondary_pattern_label' => $this->patternTokenLabel($assessment->secondary_pattern),
            'asymmetry_flag' => (bool) $assessment->asymmetry_flag,
            'confidence_level' => $assessment->confidence_level,
            'confidence_label' => $this->confidenceLabel($assessment->confidence_level, $isUnclassified),
            // Plain-language reason produced by the decision engine, so every
            // layer names the pattern that was actually suspected instead of a
            // hardcoded one.
            'explanation' => $isUnclassified ? $this->unclassifiedExplanation($assessment) : null,
            'classifications' => $this->enrichClassifications($assessment),
        ];
    }

    /**
     * The sentence shown as the report's Main Finding.
     *
     * A confirmed assessment uses the primary classification's clinical
     * conclusion; an unclassified assessment can only state the suspected
     * pattern, because nothing has been confirmed.
     */
    private function mainFinding(PostureAssessment $assessment, bool $isUnclassified): ?string
    {
        if ($isUnclassified) {
            return $this->patternTokenLabel($assessment->suspected_pattern);
        }

        $primary = $assessment->classifications()
            ->where('classification_type', 'primary')
            ->first();

        return $primary?->description
            ?: $this->patternLabel($assessment->posture_classification);
    }

    /**
     * The decision engine's plain-language explanation for an unclassified
     * posture, taken from the stored primary classification so the wording
     * always matches the pattern the engine actually suspected.
     */
    private function unclassifiedExplanation(PostureAssessment $assessment): string
    {
        $primary = $assessment->classifications
            ->firstWhere('classification_type', 'primary');

        return $primary?->description
            ?: 'The measurements do not clearly point to a single posture pattern. Measurement review is required.';
    }

    /**
     * User-facing wording for a stored classification key.
     */
    public function patternLabel(?string $classification): ?string
    {
        if ($classification === null || $classification === '') {
            return null;
        }

        return config("sparisk.pattern_labels.{$classification}", $classification);
    }

    /**
     * User-facing wording for a decision-engine pattern token such as
     * `RIGHT_FLEXED_KNEE`.
     */
    public function patternTokenLabel(?string $token): ?string
    {
        if ($token === null || $token === '') {
            return null;
        }

        $name = str_replace('_', ' ', $token);
        $side = null;
        foreach (['RIGHT', 'LEFT', 'MILD', 'MODERATE', 'SEVERE'] as $prefix) {
            if (str_starts_with($token, $prefix . '_')) {
                $side = $prefix;
                $name = str_replace('_', ' ', substr($token, strlen($prefix) + 1));
                break;
            }
        }

        $label = ucwords(strtolower($name));
        $label = $side === null
            ? 'Possible ' . $label . ' Pattern'
            : 'Possible ' . ucfirst(strtolower($side)) . ' ' . $label . ' Pattern';

        return $label;
    }

    /**
     * Plain-language wording for a decision-engine confidence level.
     */
    public function confidenceLabel(?string $level, bool $reviewRequired = false): string
    {
        if ($reviewRequired) {
            return config('sparisk.confidence_labels.REVIEW', 'Review Required');
        }

        return config("sparisk.confidence_labels.{$level}", $level ?: 'Low Confidence');
    }

    /**
     * The stored classifications with user-facing wording and alignment status
     * added, so every presentation layer renders the same labels.
     */
    public function enrichClassifications(PostureAssessment $assessment): array
    {
        $statuses = config('sparisk.alignment_statuses');

        return $assessment->classifications
            ->map(function ($classification) use ($statuses) {
                $status = $classification->severity;
                $meta = $status !== null ? ($statuses[$status] ?? null) : null;

                return array_merge($classification->toArray(), [
                    'classification_label' => $this->patternLabel($classification->classification_name),
                    'alignment_status' => $status,
                    'alignment_label' => $meta['label'] ?? null,
                    'alignment_color' => $meta['color'] ?? null,
                ]);
            })
            ->values()
            ->toArray();
    }

    /**
     * Compare the left and right measurement of each configured pair.
     */
    public function generateSymmetry(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get()->keyBy('section');
        $states = config('sparisk.symmetry_states');
        $thresholds = config('sparisk.symmetry_thresholds');
        $results = [];

        foreach (config('sparisk.symmetry_pairs') as $pair) {
            $left = $measurements->get($pair['left']);
            $right = $measurements->get($pair['right']);

            if (!$left || !$right || $left->review_required || $right->review_required) {
                continue;
            }

            $difference = round(abs((float) $left->value - (float) $right->value), 2);
            $state = $this->symmetryState($difference, $thresholds);
            $meta = $states[$state];

            $results[] = [
                'label' => $pair['label'],
                'left_section' => $pair['left'],
                'right_section' => $pair['right'],
                'left_value' => (float) $left->value,
                'right_value' => (float) $right->value,
                'difference' => $difference,
                'status' => $state,
                'status_label' => $meta['label'],
                'status_color' => $meta['color'],
                'interpretation' => $state === 'symmetrical'
                    ? "{$pair['label']} is symmetrical between the left and right side."
                    : "{$pair['label']} differs between the left and right side by " . number_format($difference, 1) . "° ({$meta['label']}).",
            ];
        }

        return $results;
    }

    /**
     * Build the overall posture interpretation from all available measurements.
     */
    public function generateOverallInterpretation(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get();
        $total = $measurements->count();
        $review = $measurements->where('review_required', true);
        $scored = $measurements->where('review_required', false);

        $abnormal = $scored->filter(fn (PostureMeasurement $m) => $this->resolveStatus($m) !== 'normal')
            ->sortByDesc(fn (PostureMeasurement $m) => (float) $m->deviation)
            ->values();

        $dataQuality = $total > 0 ? round(($total - $review->count()) / $total, 2) : 0.0;
        $sentences = [];

        if ($total === 0) {
            $sentences[] = config('sparisk.alignment_status_descriptions.missing');
        } elseif ($abnormal->isEmpty()) {
            $sentences[] = 'Overall alignment remains within the target range.';
        } else {
            $worst = $abnormal->first();
            $worstStatus = $this->resolveStatus($worst);
            $label = config("sparisk.alignment_statuses.{$worstStatus}.label", '');
            $note = $worst->position_note ?? $this->describe($worst, $worstStatus);
            $sentences[] = 'Overall alignment is mostly within the target range. ' . $note . ' (' . $label . ').';
        }

        if ($review->isNotEmpty()) {
            $sentences[] = sprintf(
                '%d of %d measurements need review before a posture pattern can be suggested.',
                $review->count(),
                $total
            );
        }

        return [
            'data_quality' => $dataQuality,
            'measurement_count' => $total,
            'review_count' => $review->count(),
            'abnormal_count' => $abnormal->count(),
            'summary' => implode(' ', array_filter($sentences)),
        ];
    }

    /**
     * Generate overall clinical summary.
     */
    public function generateClinicalSummary(PostureAssessment $assessment): string
    {
        $measurements = $assessment->measurements()->get();
        $total = $measurements->count();
        $reviewCount = $measurements->where('review_required', true)->count();
        $severe = $measurements->where('alignment_status', 'severe')->count();
        $moderate = $measurements->where('alignment_status', 'moderate')->count();
        $mild = $measurements->where('alignment_status', 'mild')->count();

        $policy = config('sparisk.classification_policy');
        $dataQuality = $total > 0 ? ($total - $reviewCount) / $total : 0.0;

        if ($severe >= 5) {
            $summary = 'Multiple significant postural deviations detected. Comprehensive intervention including aquatic therapy, massage, and a structured exercise program is strongly recommended.';
        } elseif ($severe >= 2 || $moderate >= 5) {
            $summary = 'Moderate postural deviations identified. Targeted intervention with progress monitoring is recommended.';
        } elseif ($moderate >= 2 || $mild >= 4) {
            $summary = 'Mild postural asymmetry observed. Preventive exercises and periodic reassessment are recommended.';
        } else {
            $summary = 'Postural alignment is within the acceptable range. Continue maintenance exercises.';
        }

        if ($reviewCount > 0 || $dataQuality < $policy['min_data_quality']) {
            $summary .= ' Measurement review is required before a definitive posture classification can be made.';
        }

        return $summary;
    }

    /**
     * Generate summary findings for the report.
     */
    public function generatePrimaryFindings(PostureAssessment $assessment): array
    {
        $statuses = config('sparisk.alignment_statuses');

        return $assessment->measurements()
            ->where('review_required', false)
            ->whereIn('alignment_status', ['mild', 'moderate', 'severe'])
            ->orderByDesc('deviation')
            ->limit(5)
            ->get()
            ->map(fn (PostureMeasurement $m) => [
                'section' => $m->section,
                'view' => $m->view,
                'label' => $m->label,
                'value' => (float) $m->value,
                'deviation' => $m->deviation !== null ? (float) $m->deviation : null,
                'alignment_status' => $m->alignment_status,
                'alignment_label' => $statuses[$m->alignment_status]['label'] ?? null,
                'position_note' => $m->position_note,
                'interpretation' => $this->describe($m, $m->alignment_status),
            ])
            ->toArray();
    }

    /**
     * Plain-language statement of the patient's posture problem, together with
     * the individual problem areas behind it.
     *
     * The wording is derived entirely from the decision result already stored on
     * the assessment (the confirmed classification or the suspected / secondary
     * patterns) plus the reliability-filtered measurements. It never invents a
     * cut-off: only the alignment statuses and deviation angles that the
     * measurement and decision engines already produced are read.
     */
    public function patientProblem(PostureAssessment $assessment): array
    {
        $unclassified = config('sparisk.unclassified');
        $isUnclassified = $assessment->posture_classification === $unclassified['code']
            || $assessment->review_status === $unclassified['review_status'];

        $problems = $this->problemAreas($assessment);

        if ($isUnclassified) {
            $sentences = ['No single posture pattern could be identified from the measurements available.'];
            $suspected = $this->patternTokenLabel($assessment->suspected_pattern);
            if ($suspected !== null) {
                $sentences[] = "The strongest sign currently points to {$suspected}.";
            }
        } else {
            $label = $this->patternLabel($assessment->posture_classification)
                ?? ucwords(strtolower(str_replace('_', ' ', (string) $assessment->posture_classification)));
            $sentences = ["The assessment indicates {$label}."];
        }

        if ($problems === []) {
            $sentences[] = 'No posture deviation reached the reporting threshold in the measurements available.';
        } else {
            $areas = implode(', ', array_unique(array_column($problems, 'area')));
            $sentences[] = "The main problem areas are {$areas}.";
        }

        return [
            'summary' => implode(' ', $sentences),
            'problems' => $problems,
        ];
    }

    /**
     * The reliability-filtered measurements that have deviated, ordered by the
     * size of the deviation. This is the structured half of `patientProblem()`.
     */
    private function problemAreas(PostureAssessment $assessment): array
    {
        $statuses = config('sparisk.alignment_statuses');

        return $assessment->measurements()
            ->where('review_required', false)
            ->whereIn('alignment_status', ['mild', 'moderate', 'severe'])
            ->orderByDesc('deviation')
            ->limit(5)
            ->get()
            ->map(function (PostureMeasurement $m) use ($statuses) {
                $status = $m->alignment_status ?? 'normal';

                return [
                    'section' => $m->section,
                    'view' => $m->view,
                    'area' => $m->label,
                    'severity' => $status,
                    'severity_label' => $statuses[$status]['label'] ?? ucfirst($status),
                    'deviation_angle' => $m->deviation_angle !== null ? (float) $m->deviation_angle : null,
                    'direction' => $m->deviation_direction,
                    'finding' => $this->describe($m, $status),
                ];
            })
            ->toArray();
    }

    /**
     * Calculate trend between two assessments.
     */
    public function calculateTrend(PostureAssessment $current, PostureAssessment $previous): array
    {
        $improved = [];
        $attention = [];
        $currentMeasurements = $current->measurements()->get()->keyBy('section');
        $previousMeasurements = $previous->measurements()->get()->keyBy('section');

        foreach ($currentMeasurements as $section => $cm) {
            if (!isset($previousMeasurements[$section])) continue;
            $pm = $previousMeasurements[$section];
            if ($cm->review_required || $pm->review_required) continue;

            $currentDeviation = (float) ($cm->deviation ?? abs($cm->value));
            $previousDeviation = (float) ($pm->deviation ?? abs($pm->value));
            $diff = $previousDeviation - $currentDeviation;

            if ($diff > 2) {
                $improved[] = ['section' => $section, 'label' => $cm->label, 'change' => round($diff, 1)];
            } elseif ($diff < -2) {
                $attention[] = ['section' => $section, 'label' => $cm->label, 'change' => round($diff, 1)];
            }
        }

        return [
            'improved' => $improved,
            'need_attention' => $attention,
        ];
    }

    /**
     * Resolve the alignment status of a stored measurement, keeping legacy rows
     * (captured before the alignment status existed) working.
     */
    private function resolveStatus(PostureMeasurement $m): string
    {
        if ($m->review_required) {
            return 'review';
        }

        return $m->alignment_status ?? $m->severity ?? 'normal';
    }

    /**
     * Resolve the SATA user-facing output status (on_point / near_point /
     * off_point / baseline_tracking / review). Stored rows carry the status code
     * written by the measurement engine; legacy rows fall back through the
     * documented level→status mapping so no arbitrary cut-off is invented.
     */
    private function resolveStatusCode(PostureMeasurement $m, string $status): string
    {
        if ($m->review_required) {
            return 'review';
        }

        if ($m->status_code !== null) {
            return $m->status_code;
        }

        return config('sparisk.level_to_status')[$status] ?? 'review';
    }

    /**
     * Build the plain-language interpretation for a stored measurement.
     */
    private function describe(PostureMeasurement $m, string $status): string
    {
        if ($status === 'review') {
            return config('sparisk.alignment_status_descriptions.review');
        }

        if ($status === 'normal') {
            return config('sparisk.alignment_status_descriptions.normal');
        }

        // Measurements captured after the alignment status was introduced carry
        // their interpretation. Legacy rows are re-derived from the stored data.
        if ($m->alignment_status !== null && $m->status_text) {
            return $m->status_text;
        }

        $def = config("sparisk.measurements.{$m->section}");

        if (!$def) {
            return config("sparisk.alignment_status_descriptions.{$status}")
                ?? config('sparisk.alignment_status_descriptions.review');
        }

        $isAbove = $m->deviation_direction !== null
            ? $m->deviation_direction === 'above'
            : (float) $m->value >= (float) ($m->reference_value ?? 0);

        return $this->measurementEngine->interpretation($m->section, $def, $status, $isAbove);
    }

    private function symmetryState(float $difference, array $thresholds): string
    {
        if ($difference < $thresholds['slight']) {
            return 'symmetrical';
        }

        return $difference < $thresholds['marked'] ? 'slightly_asymmetrical' : 'markedly_asymmetrical';
    }
}
