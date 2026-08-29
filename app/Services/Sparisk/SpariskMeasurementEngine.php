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
     * Each measured value is compared against the SATA age-based reference for
     * the patient's age group. The deviation (ABS(Clinical Angle - Age Reference))
     * is then mapped to a fixed SATA severity band.
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
            $referenceValue = $references[$def['view'] . '.' . $section] ?? 0;
            $deviation = round(abs($value - $referenceValue), 2);
            $severity = $this->classifyDeviation($deviation);

            $measurements[] = [
                'posture_assessment_id' => $assessment->id,
                'view' => $def['view'],
                'section' => $section,
                'label' => $def['label'],
                'value' => $value,
                'reference_value' => $referenceValue,
                'deviation' => $deviation,
                'unit' => '°',
                'severity' => $severity['level'],
                'status_text' => $severity['label'],
            ];
        }

        PostureMeasurement::insert($measurements);

        return $measurements;
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
     * Classify severity based on threshold configuration.
     *
     * Legacy helper that treats the supplied value as a raw deviation from a
     * zero reference. Age-aware classification is handled through
     * {@see self::classifyDeviation()}.
     */
    public function classifySeverity(string $section, float $value): array
    {
        $type = $this->getMeasurementType($section);
        $thresholds = config("sparisk.severity_thresholds.{$type}");

        if (!$thresholds) {
            return ['level' => 'normal', 'status' => 'Normal'];
        }

        if ($value <= 1) {
            return ['level' => 'normal', 'status' => 'Normal'];
        }
        if ($value <= $thresholds['mild']) {
            return ['level' => 'mild', 'status' => 'Ringan'];
        }
        if ($value <= $thresholds['moderate']) {
            return ['level' => 'moderate', 'status' => 'Sederhana'];
        }
        return ['level' => 'severe', 'status' => 'Teruk'];
    }

    /**
     * Map section code to measurement type group.
     */
    private function getMeasurementType(string $section): string
    {
        $sectionCode = substr($section, 1);
        $viewLetter = $section[0];

        return match (true) {
            in_array($sectionCode, ['2', '3']) && in_array($viewLetter, ['C', 'D']) => 'shoulder',
            in_array($sectionCode, ['2']) => 'head',
            in_array($sectionCode, ['7', '4']) && in_array($viewLetter, ['C', 'D']) => 'pelvic',
            in_array($sectionCode, ['7']) && in_array($viewLetter, ['A']) => 'pelvic',
            in_array($sectionCode, ['6']) && in_array($viewLetter, ['B']) => 'pelvic',
            in_array($sectionCode, ['10', '11']) && in_array($viewLetter, ['A']) => 'foot',
            in_array($sectionCode, ['7']) && in_array($viewLetter, ['C', 'D']) => 'foot',
            in_array($sectionCode, ['8', '9']) && in_array($viewLetter, ['A']) => 'knee',
            in_array($sectionCode, ['5']) && in_array($viewLetter, ['C', 'D']) => 'knee',
            in_array($sectionCode, ['5', '6']) && in_array($viewLetter, ['A', 'B']) => 'trunk',
            default => 'head',
        };
    }

    /**
     * Calculate overall posture score from measurements.
     */
    public function calculateOverallScore(PostureAssessment $assessment): int
    {
        $measurements = $assessment->measurements;
        if ($measurements->isEmpty()) return 100;

        $score = 100;
        $penalties = 0;

        foreach ($measurements as $m) {
            $deviation = (float) ($m->deviation ?? abs($m->value));
            if ($deviation <= 1) continue;
            if ($deviation <= 5) $penalties += 1;
            elseif ($deviation <= 10) $penalties += 2;
            elseif ($deviation <= 20) $penalties += 4;
            else $penalties += 6;
        }

        $score = max(0, $score - $penalties);
        return min(100, $score);
    }
}
