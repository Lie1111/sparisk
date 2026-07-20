<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;

class SpariskMeasurementEngine
{
    /**
     * Process raw MediaPipe measurements and return structured data.
     */
    public function process(array $landmarks, PostureAssessment $assessment): array
    {
        $measurements = [];
        $config = config('sparisk.measurements');

        foreach ($landmarks as $section => $value) {
            if (!isset($config[$section])) continue;

            $def = $config[$section];
            $severity = $this->classifySeverity($section, abs($value));

            $measurements[] = [
                'posture_assessment_id' => $assessment->id,
                'view' => $def['view'],
                'section' => $section,
                'label' => $def['label'],
                'value' => $value,
                'unit' => '°',
                'severity' => $severity['level'],
                'status_text' => $severity['status'],
            ];
        }

        PostureMeasurement::insert($measurements);

        return $measurements;
    }

    /**
     * Classify severity based on threshold configuration.
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
            $absValue = abs($m->value);
            if ($absValue <= 1) continue;
            if ($absValue <= 5) $penalties += 1;
            elseif ($absValue <= 10) $penalties += 2;
            elseif ($absValue <= 20) $penalties += 4;
            else $penalties += 6;
        }

        $score = max(0, $score - $penalties);
        return min(100, $score);
    }
}
