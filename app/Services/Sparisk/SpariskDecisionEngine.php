<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureClassification;
use App\Models\PostureMeasurement;
use Illuminate\Support\Collection;

class SpariskDecisionEngine
{
    /**
     * Ordering used to find the strongest alignment status inside a rule.
     */
    private const LEVEL_RANK = ['normal' => 0, 'mild' => 1, 'moderate' => 2, 'severe' => 3];

    public function __construct(private SpariskInterpretationEngine $interpretationEngine) {}

    /**
     * Execute the full decision pipeline on an assessment.
     *
     * A posture type is only confirmed when the rules are supported by enough
     * independent, reliable measurements. When the data is missing, unreliable
     * or does not describe a single clear pattern the result stays UNCLASSIFIED
     * with `review_status = MEASUREMENT_REVIEW_REQUIRED`; the strongest partial
     * match is reported as a suspected/secondary pattern instead of a diagnosis.
     */
    public function decide(PostureAssessment $assessment): array
    {
        $policy = config('sparisk.classification_policy');
        $unclassified = config('sparisk.unclassified');

        $measurements = $assessment->measurements()->get()->keyBy('section');
        $dataQuality = $this->dataQuality($measurements);

        $symmetry = $this->interpretationEngine->generateSymmetry($assessment);
        $asymmetryFlag = collect($symmetry)->contains(fn (array $pair) => $pair['status'] !== 'symmetrical');

        $evaluations = collect(config('sparisk.decision_rules'))
            ->map(fn (array $rule) => $this->evaluateRule($rule, $measurements, $dataQuality, $policy))
            ->all();

        $confirmed = collect($evaluations)
            ->where('status', 'confirmed')
            ->sortByDesc('confidence')
            ->values();

        $candidates = collect($evaluations)
            ->whereIn('status', ['suspected', 'insufficient_data'])
            ->sortByDesc(fn (array $evaluation) => [$evaluation['confidence'], $evaluation['evidence']])
            ->values();

        $primary = $confirmed->first();
        $isUnclassified = $primary === null;

        $suspected = $candidates->get(0);
        $secondary = $candidates->get(1);

        $confidence = (float) ($isUnclassified ? ($suspected['confidence'] ?? 0.0) : $primary['confidence']);

        $classifications = [];
        if ($primary) {
            $classifications[] = $this->storeClassification($assessment, $primary, 'primary');
            foreach ($confirmed->skip(1) as $extra) {
                $classifications[] = $this->storeClassification($assessment, $extra, 'secondary');
            }
        } else {
            $classifications[] = PostureClassification::create([
                'posture_assessment_id' => $assessment->id,
                'classification_type' => 'primary',
                'classification_name' => $unclassified['code'],
                'severity' => null,
                'confidence' => round($confidence, 2),
                'description' => $this->unclassifiedDescription($measurements, $dataQuality, $suspected),
            ]);
        }

        $reviewStatus = $isUnclassified ? $unclassified['review_status'] : null;

        $assessment->update([
            'posture_classification' => $isUnclassified ? $unclassified['code'] : $primary['classification'],
            'review_status' => $reviewStatus,
            'suspected_pattern' => $suspected['token'] ?? null,
            'secondary_pattern' => $secondary['token'] ?? null,
            'asymmetry_flag' => $asymmetryFlag,
            'confidence_level' => $this->confidenceLevel($confidence, $policy),
        ]);

        return [
            'posture_classification' => $isUnclassified ? $unclassified['code'] : $primary['classification'],
            'posture_classification_display' => $isUnclassified ? $unclassified['display'] : $primary['classification'],
            'review_status' => $reviewStatus,
            'review_reason' => $isUnclassified ? $this->reviewReason($measurements, $dataQuality, $suspected) : null,
            'suspected_pattern' => $suspected['token'] ?? null,
            'secondary_pattern' => $secondary['token'] ?? null,
            'asymmetry_flag' => $asymmetryFlag,
            'confidence_level' => $this->confidenceLevel($confidence, $policy),
            'confidence' => round($confidence, 1),
            'data_quality' => $dataQuality,
            'decisions' => collect($evaluations)->where('status', '!=', 'not_matched')->values()->all(),
            'classifications' => $classifications,
            'primary_classification' => $isUnclassified ? $unclassified['code'] : $primary['classification'],
            'symmetry' => $symmetry,
        ];
    }

    /**
     * Evaluate a single decision rule against the stored measurements.
     *
     * `requires` sections must exist and be reliable before a rule can even be
     * considered, `all` conditions must every match, and `any`/`any2` need at
     * least one match. A rule is only confirmed when it matches, carries enough
     * independent evidence and clears the configured confidence and data
     * quality guards.
     */
    private function evaluateRule(array $rule, Collection $measurements, float $dataQuality, array $policy): array
    {
        $sections = $this->ruleSections($rule);
        $matchedSections = [];
        $levels = [];

        $missing = [];
        foreach ($rule['requires'] ?? [] as $section) {
            if (!$this->isReliable($measurements->get($section))) {
                $missing[] = $section;
            }
        }

        $allPass = true;
        foreach ($rule['all'] ?? [] as $condition) {
            if ($this->conditionMatches($condition, $measurements)) {
                $this->recordMatch($condition['section'], $measurements, $matchedSections, $levels);
            } else {
                $allPass = false;
            }
        }

        $anyPass = true;
        if (!empty($rule['any'])) {
            $anyPass = false;
            foreach ($rule['any'] as $condition) {
                if ($this->conditionMatches($condition, $measurements)) {
                    $anyPass = true;
                    $this->recordMatch($condition['section'], $measurements, $matchedSections, $levels);
                }
            }
        }

        $any2Pass = true;
        if (!empty($rule['any2'])) {
            $any2Pass = false;
            foreach ($rule['any2'] as $condition) {
                if ($this->conditionMatches($condition, $measurements)) {
                    $any2Pass = true;
                    $this->recordMatch($condition['section'], $measurements, $matchedSections, $levels);
                }
            }
        }

        $hasConditions = !empty($rule['all']) || !empty($rule['any']) || !empty($rule['any2']);
        $matched = $hasConditions && $allPass && $anyPass && $any2Pass;

        if ($rule['normal_when_no_deviation'] ?? false) {
            $reliable = $measurements->filter(fn (PostureMeasurement $m) => !$m->review_required);
            $matched = $reliable->isNotEmpty()
                && $reliable->every(fn (PostureMeasurement $m) => $m->alignment_status === 'normal');
            $matchedSections = $reliable->keys()->all();
            $levels = ['normal'];
            $sections = $reliable->keys()->all();
        }

        $evidence = count($matchedSections);
        $coverage = $sections === [] ? 1.0 : $evidence / count($sections);
        $confidence = round(min(100.0, $coverage * 100) * $dataQuality, 1);

        $confirmable = in_array($rule['classification'], config('sparisk.confirmable_classifications', []), true);

        $status = 'not_matched';
        if ($matched) {
            $confirmed = $confirmable
                && $missing === []
                && $evidence >= $policy['min_evidence']
                && $confidence >= $policy['min_confidence']
                && $dataQuality >= $policy['min_data_quality'];

            $status = $confirmed ? 'confirmed' : 'suspected';
        } elseif ($missing !== []) {
            $status = 'insufficient_data';
        } elseif ($hasConditions && $allPass && $evidence > 0) {
            // The mandatory criteria of the pattern were met but its supporting
            // criteria were not, so the pattern can only be reported as a
            // suspicion. When `all` fails the pattern's core criteria are not
            // satisfied at all and it is not a candidate.
            $status = 'suspected';
        }

        return [
            'rule_id' => $rule['id'],
            'classification' => $rule['classification'],
            'conclusion' => $rule['conclusion'],
            'status' => $status,
            'matched' => $matched,
            'confirmable' => $confirmable,
            'evidence' => $evidence,
            'expected_evidence' => count($sections),
            'matched_sections' => array_values($matchedSections),
            'missing_sections' => $missing,
            'confidence' => $confidence,
            'level' => $this->worstLevel($levels),
            'severity' => $this->worstLevel($levels) ?? ($rule['severity'] ?? null),
            'side' => $this->side($measurements, array_keys($matchedSections)),
            'token' => $this->patternToken($rule['pattern'] ?? $rule['classification'], $measurements, array_keys($matchedSections), $this->worstLevel($levels)),
        ];
    }

    /**
     * Whether a single rule condition is satisfied by the stored measurement.
     */
    private function conditionMatches(array $condition, Collection $measurements): bool
    {
        $measurement = $measurements->get($condition['section']);

        if (!$this->isReliable($measurement)) {
            return false;
        }

        if (!in_array($measurement->alignment_status, $condition['level'] ?? [], true)) {
            return false;
        }

        $direction = $condition['direction'] ?? null;

        return $direction === null || $measurement->deviation_direction === $direction;
    }

    /**
     * A measurement can only drive a decision when it was captured and did not
     * need its landmark or angle verified.
     */
    private function isReliable(?PostureMeasurement $measurement): bool
    {
        return $measurement !== null
            && !$measurement->review_required
            && $measurement->alignment_status !== null;
    }

    private function recordMatch(string $section, Collection $measurements, array &$matchedSections, array &$levels): void
    {
        $measurement = $measurements->get($section);
        $matchedSections[$section] = true;
        $levels[] = $measurement->alignment_status;
    }

    /**
     * Every distinct section a rule can look at, used to score how complete the
     * evidence for that rule is.
     */
    private function ruleSections(array $rule): array
    {
        $sections = $rule['requires'] ?? [];

        foreach (['all', 'any', 'any2'] as $group) {
            foreach ($rule[$group] ?? [] as $condition) {
                $sections[] = $condition['section'];
            }
        }

        return array_values(array_unique($sections));
    }

    private function worstLevel(array $levels): ?string
    {
        if ($levels === []) {
            return null;
        }

        usort($levels, fn ($a, $b) => (self::LEVEL_RANK[$b] ?? 0) <=> (self::LEVEL_RANK[$a] ?? 0));

        return $levels[0];
    }

    /**
     * Whether the matched evidence sits entirely on one side of the body.
     *
     * Side views are one-sided by definition. Front/back measurements carry a
     * side when the parameter itself is one-sided (e.g. the right knee angle),
     * which is declared in the measurement definition.
     */
    private function side(Collection $measurements, array $sections): ?string
    {
        $sides = [];
        foreach ($sections as $section) {
            $measurement = $measurements->get($section);
            if ($measurement === null) {
                continue;
            }

            $side = $this->measurementSide($measurement->section, $measurement->view);
            if ($side !== null) {
                $sides[] = $side;
            }
        }

        $sides = array_unique($sides);

        return count($sides) === 1 ? $sides[0] : null;
    }

    /**
     * Resolve the side a measurement belongs to, if any.
     */
    private function measurementSide(string $section, ?string $view): ?string
    {
        if ($view === 'right_side') {
            return 'RIGHT';
        }

        if ($view === 'left_side') {
            return 'LEFT';
        }

        $side = config("sparisk.measurements.{$section}.side");

        return $side ? strtoupper($side) : null;
    }

    /**
     * Machine readable pattern key, e.g. `RIGHT_FLEXED_KNEE` or
     * `MILD_ROUNDED_SHOULDER`. The side is used when the evidence is one-sided,
     * otherwise the deviation level is used as the prefix.
     */
    private function patternToken(string $classification, Collection $measurements, array $sections, ?string $level): string
    {
        $name = strtoupper(str_replace(['-', ' '], '_', $classification));
        $prefix = $this->side($measurements, $sections) ?? ($level ? strtoupper($level) : null);

        return $prefix ? $prefix . '_' . $name : $name;
    }

    private function confidenceLevel(float $confidence, array $policy): string
    {
        if ($confidence >= $policy['confidence_levels']['high']) {
            return 'HIGH';
        }

        return $confidence >= $policy['confidence_levels']['moderate'] ? 'MODERATE' : 'LOW';
    }

    /**
     * Share of measurements that were captured and validated.
     */
    public function dataQuality(Collection $measurements): float
    {
        $total = $measurements->count();

        if ($total === 0) {
            return 0.0;
        }

        $reliable = $measurements->filter(fn (PostureMeasurement $m) => !$m->review_required)->count();

        return round($reliable / $total, 2);
    }

    private function reviewReason(Collection $measurements, float $dataQuality, ?array $suspected): string
    {
        if ($measurements->isEmpty() || $dataQuality < config('sparisk.classification_policy.min_data_quality')) {
            return 'INSUFFICIENT_DATA';
        }

        return $suspected ? 'PATTERN_NOT_CONFIRMED' : 'NO_CLEAR_PATTERN';
    }

    private function unclassifiedDescription(Collection $measurements, float $dataQuality, ?array $suspected): string
    {
        $reviewCount = $measurements->filter(fn (PostureMeasurement $m) => $m->review_required)->count();

        if ($measurements->isEmpty()) {
            return 'No measurements were available, so no posture type can be confirmed. Measurement review is required.';
        }

        if ($dataQuality < config('sparisk.classification_policy.min_data_quality')) {
            return sprintf(
                '%d of %d measurements could not be validated. Measurement review is required before a posture type can be confirmed.',
                $reviewCount,
                $measurements->count()
            );
        }

        if ($suspected) {
            return sprintf(
                'The measurements do not support a single confirmable posture type. A %s pattern is suspected and needs review.',
                str_replace('_', ' ', strtolower($suspected['token']))
            );
        }

        return 'The measurements do not support a single confirmable posture type. Measurement review is required.';
    }

    private function storeClassification(PostureAssessment $assessment, array $evaluation, string $type): PostureClassification
    {
        $description = config("sparisk.classifications.{$evaluation['classification']}.description", $evaluation['conclusion']);

        return PostureClassification::create([
            'posture_assessment_id' => $assessment->id,
            'classification_type' => $type,
            'classification_name' => $evaluation['classification'],
            'severity' => $evaluation['severity'],
            'confidence' => round($evaluation['confidence'], 2),
            'description' => $evaluation['conclusion'] . ' ' . $description . '.',
        ]);
    }

    /**
     * Generate a global posture analysis summary.
     *
     * Regions come from the `region` key of each measurement definition so the
     * analysis stays in sync with the measurement catalogue. Measurements that
     * need review are counted separately and never affect a region.
     */
    public function generateGlobalAnalysis(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get();

        $regions = [
            'alignment' => [],
            'head' => [],
            'trunk' => [],
            'pelvis' => [],
            'lower_limb' => [],
        ];

        $groups = [
            'alignment' => 'alignment',
            'head' => 'head',
            'shoulder' => 'trunk',
            'thoracic' => 'trunk',
            'pelvis' => 'pelvis',
            'lower_limb' => 'lower_limb',
        ];

        foreach ($measurements as $measurement) {
            $region = config("sparisk.measurements.{$measurement->section}.region", 'lower_limb');
            $group = $groups[$region] ?? 'lower_limb';
            $regions[$group][$measurement->section] = $measurement->review_required
                ? 'review'
                : $measurement->alignment_status;
        }

        $counts = $measurements->countBy(fn (PostureMeasurement $m) => $m->review_required
            ? 'review'
            : ($m->alignment_status ?? 'review'));

        $affectedRegions = 0;
        foreach ($regions as $statuses) {
            $concern = array_filter($statuses, fn ($status) => in_array($status, ['moderate', 'severe'], true));
            if ($concern !== []) {
                $affectedRegions++;
            }
        }

        return [
            'total_measurements' => $measurements->count(),
            'severe_count' => (int) $counts->get('severe', 0),
            'moderate_count' => (int) $counts->get('moderate', 0),
            'mild_count' => (int) $counts->get('mild', 0),
            'normal_count' => (int) $counts->get('normal', 0),
            'review_count' => (int) $counts->get('review', 0),
            'affected_regions' => $affectedRegions,
            'regions_detail' => $regions,
            'is_global' => $affectedRegions >= 3,
            'is_upper_body' => $regions['head'] !== [] && $regions['trunk'] !== [],
            'is_lower_body' => $regions['pelvis'] !== [] && $regions['lower_limb'] !== [],
        ];
    }
}
