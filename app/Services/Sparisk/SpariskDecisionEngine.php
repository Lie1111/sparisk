<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureClassification;

class SpariskDecisionEngine
{
    /**
     * Execute the full decision pipeline on an assessment.
     */
    public function decide(PostureAssessment $assessment): array
    {
        $rules = config('sparisk.decision_rules');
        $measurements = $assessment->measurements()->get()->keyBy('section');
        $decisions = [];
        $classifications = [];

        foreach ($rules as $rule) {
            $result = $this->evaluateRule($rule, $measurements);

            if ($result['matched']) {
                $decisions[] = $result;

                $classification = PostureClassification::create([
                    'posture_assessment_id' => $assessment->id,
                    'classification_type' => 'primary',
                    'classification_name' => $rule['classification'],
                    'severity' => $rule['severity'] ?? 'moderate',
                    'confidence' => $result['confidence'],
                    'description' => $result['conclusion'],
                ]);

                $classifications[] = $classification;
            }
        }

        $primaryClassification = !empty($classifications)
            ? $classifications[0]->classification_name
            : 'Normal Alignment';

        $assessment->update([
            'posture_classification' => $primaryClassification,
        ]);

        return [
            'decisions' => $decisions,
            'classifications' => $classifications,
            'primary_classification' => $primaryClassification,
        ];
    }

    /**
     * Evaluate a single decision rule against measurements.
     */
    private function evaluateRule(array $rule, $measurements): array
    {
        $allMatch = true;
        $anyMatch = false;
        $matchedConditions = 0;
        $totalConditions = count($rule['conditions']);

        foreach ($rule['conditions'] as $condition) {
            $measurement = $measurements->get($condition['section']);
            $matches = $measurement && in_array($measurement->severity, $condition['severity']);

            if ($matches) {
                $anyMatch = true;
                $matchedConditions++;
            } else {
                $allMatch = false;
            }
        }

        $isMatch = $rule['condition_logic'] === 'ALL' ? $allMatch : $anyMatch;
        $confidence = $isMatch ? ($matchedConditions / $totalConditions) * 100 : 0;

        return [
            'rule_id' => $rule['id'],
            'matched' => $isMatch && $matchedConditions > 0,
            'matched_conditions' => $matchedConditions,
            'total_conditions' => $totalConditions,
            'confidence' => round($confidence, 1),
            'classification' => $rule['classification'],
            'conclusion' => $rule['conclusion'],
            'severity' => $rule['severity'] ?? 'moderate',
        ];
    }

    /**
     * Generate a global posture analysis summary.
     */
    public function generateGlobalAnalysis(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get();
        $severe = $measurements->where('severity', 'severe')->count();
        $moderate = $measurements->where('severity', 'moderate')->count();
        $mild = $measurements->where('severity', 'mild')->count();
        $normal = $measurements->where('severity', 'normal')->count();

        $regions = [
            'head' => [],
            'trunk' => [],
            'pelvis' => [],
            'lower_limb' => [],
        ];

        foreach ($measurements as $m) {
            $section = $m->section;
            $group = match (true) {
                in_array($section, ['A2', 'C2', 'D2', 'B2']) => 'head',
                in_array($section, ['A3', 'A4', 'A5', 'A6', 'B3', 'B4', 'B5', 'C3', 'D3']) => 'trunk',
                in_array($section, ['A7', 'B6', 'C4', 'D4']) => 'pelvis',
                default => 'lower_limb',
            };
            $regions[$group][$section] = $m->severity;
        }

        $affectedRegions = 0;
        foreach ($regions as $group => $ms) {
            $hasSevere = count(array_filter($ms, fn($s) => $s === 'severe')) > 0;
            $hasModerate = count(array_filter($ms, fn($s) => $s === 'moderate')) > 0;
            if ($hasSevere || $hasModerate) $affectedRegions++;
        }

        return [
            'total_measurements' => $measurements->count(),
            'severe_count' => $severe,
            'moderate_count' => $moderate,
            'mild_count' => $mild,
            'normal_count' => $normal,
            'affected_regions' => $affectedRegions,
            'regions_detail' => $regions,
            'is_global' => $affectedRegions >= 3,
            'is_upper_body' => ($regions['head'] && $regions['trunk']),
            'is_lower_body' => ($regions['pelvis'] && $regions['lower_limb']),
        ];
    }
}
