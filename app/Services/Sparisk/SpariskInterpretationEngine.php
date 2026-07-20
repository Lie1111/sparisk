<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureClassification;

class SpariskInterpretationEngine
{
    /**
     * Interpret biomechanical measurements and produce clinical interpretations.
     */
    public function interpret(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get();
        $interpretations = [];

        foreach ($measurements as $m) {
            $interpretations[] = [
                'section' => $m->section,
                'label' => $m->label,
                'value' => $m->value,
                'severity' => $m->severity,
                'interpretation' => $this->getInterpretation($m->label, $m->value, $m->severity),
            ];
        }

        return $interpretations;
    }

    /**
     * Generate human-readable interpretation for a single measurement.
     */
    private function getInterpretation(string $label, float $value, ?string $severity): string
    {
        $absValue = abs($value);

        if ($absValue <= 1) {
            return "Normal";
        }

        $direction = $value > 0 ? 'right' : 'left';
        if (in_array($label, ['Head Shift', 'Shoulder Angle', 'Pelvic Tilt'])) {
            $direction = $value > 0 ? 'forward' : 'backward';
        }

        return match ($severity) {
            'mild' => "Slightly {$direction} — mild deviation",
            'moderate' => "{$direction} shift — moderate, needs attention",
            'severe' => "Severe {$direction} deviation — requires intervention",
            default => "Within normal range",
        };
    }

    /**
     * Generate overall clinical summary.
     */
    public function generateClinicalSummary(PostureAssessment $assessment): string
    {
        $severe = $assessment->measurements()->where('severity', 'severe')->count();
        $moderate = $assessment->measurements()->where('severity', 'moderate')->count();

        if ($severe >= 5) {
            return 'Multiple severe postural deviations detected. Comprehensive intervention including aquatic therapy, massage, and structured exercise program is strongly recommended.';
        }
        if ($severe >= 2 || $moderate >= 5) {
            return 'Moderate to severe postural deviations identified. Targeted intervention with progress monitoring is recommended.';
        }
        if ($moderate >= 2) {
            return 'Mild to moderate postural asymmetry observed. Preventive exercises and periodic reassessment are recommended.';
        }
        return 'Postural alignment is within acceptable range. Continue maintenance exercises.';
    }

    /**
     * Generate summary findings.
     */
    public function generatePrimaryFindings(PostureAssessment $assessment): array
    {
        return $assessment->measurements()
            ->whereIn('severity', ['moderate', 'severe'])
            ->orderBy('value', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($m) => [
                'section' => $m->section,
                'label' => $m->label,
                'value' => $m->value,
                'severity' => $m->severity,
            ])
            ->toArray();
    }

    /**
     * Classify posture based on measurements.
     */
    public function classify(PostureAssessment $assessment): array
    {
        $measurements = $assessment->measurements()->get()->keyBy('section');
        $classifications = [];
        $rules = config('sparisk.decision_rules');

        foreach ($rules as $rule) {
            $allMatch = true;
            $anyMatch = false;

            foreach ($rule['conditions'] as $condition) {
                $measurement = $measurements->get($condition['section']);
                $matches = $measurement && in_array($measurement->severity, $condition['severity']);

                if ($matches) {
                    $anyMatch = true;
                } else {
                    $allMatch = false;
                }
            }

            $isMatch = $rule['condition_logic'] === 'ALL' ? $allMatch : $anyMatch;

            if ($isMatch) {
                $classifications[] = [
                    'type' => 'primary',
                    'name' => $rule['classification'],
                    'severity' => $rule['severity'] ?? 'moderate',
                    'description' => config("sparisk.classifications.{$rule['classification']}.description") ?? '',
                ];
            }
        }

        if (empty($classifications)) {
            $hasMild = $measurements->where('severity', 'mild')->count();
            if ($hasMild > 3) {
                $classifications[] = [
                    'type' => 'primary',
                    'name' => 'Mild Asymmetry',
                    'severity' => 'mild',
                    'description' => 'Slight bilateral differences observed.',
                ];
            }
        }

        return $classifications;
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
            $diff = abs($pm->value) - abs($cm->value);

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
}
