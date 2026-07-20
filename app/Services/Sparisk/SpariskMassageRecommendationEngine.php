<?php

namespace App\Services\Sparisk;

use App\Models\MassageRecommendation;
use App\Models\PostureAssessment;

class SpariskMassageRecommendationEngine
{
    /**
     * Generate massage recommendations based on assessment classification.
     */
    public function recommend(PostureAssessment $assessment): array
    {
        $classification = $assessment->posture_classification ?? 'default';
        $massages = config("sparisk.massage_recommendations.{$classification}")
            ?? config('sparisk.massage_recommendations.default');

        $recommendations = [];
        $orderIndex = 0;

        foreach ($massages as $massage) {
            $rec = MassageRecommendation::create([
                'posture_assessment_id' => $assessment->id,
                'body_area' => $massage['area'],
                'priority_stars' => $massage['stars'],
                'instructions' => $this->getMassageInstructions($massage['area']),
                'duration_minutes' => $massage['duration'],
                'frequency' => $massage['frequency'],
                'safety_notes' => $this->getSafetyNotes($massage['area']),
                'order_index' => $orderIndex++,
            ]);

            $recommendations[] = $rec;
        }

        return $recommendations;
    }

    private function getMassageInstructions(string $area): string
    {
        $instructions = [
            'Neck' => 'Apply gentle circular pressure along cervical spine. Focus on suboccipital region. Use moderate pressure.',
            'Chest' => 'Use open palm strokes across pectoralis muscles. Apply sustained pressure to tight bands.',
            'Thoracic' => 'Use kneading technique along thoracic spine. Focus on rhomboids and middle trapezius.',
            'Upper Trapezius' => 'Apply firm pressure along upper trapezius from neck to shoulder. Use circular motions.',
            'Lower Back' => 'Use gentle effleurage along lumbar spine. Apply sustained pressure to tight paraspinals.',
            'Hip Flexor' => 'Apply deep pressure to anterior hip region. Use slow, sustained strokes.',
            'Hamstring' => 'Use long strokes along hamstring belly. Apply cross-fiber friction to tight bands.',
            'Calf' => 'Use kneading strokes along gastrocnemius and soleus. Apply sustained pressure to trigger points.',
            'SCM' => 'Gentle circular massage along sternocleidomastoid muscle. Use light to moderate pressure.',
            'Suboccipital' => 'Apply gentle sustained pressure at base of skull. Use small circular motions.',
            'Glute' => 'Use deep kneading on gluteal muscles. Apply sustained pressure to tender points.',
            'Upper Trap' => 'Apply firm kneading along upper trapezius. Use circular motions from neck to shoulder.',
        ];

        return $instructions[$area] ?? 'Apply moderate pressure in circular motions. Adjust pressure based on comfort.';
    }

    private function getSafetyNotes(string $area): string
    {
        return match (true) {
            in_array($area, ['Neck', 'SCM', 'Suboccipital', 'Upper Trap', 'Upper Trapezius']) =>
                'Avoid excessive pressure on neck. Do not massage if there is acute injury or inflammation.',
            in_array($area, ['Lower Back', 'Hip Flexor']) =>
                'Use caution with deep pressure. Avoid during acute flare-ups. Consult therapist if pain persists.',
            in_array($area, ['Hamstring', 'Calf', 'Glute']) =>
                'Avoid if there is DVT or circulatory issues. Do not massage over varicose veins.',
            default =>
                'Use moderate pressure. Stop if pain increases. Consult healthcare provider if unsure.',
        };
    }
}
