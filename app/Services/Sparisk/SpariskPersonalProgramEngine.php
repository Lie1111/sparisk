<?php

namespace App\Services\Sparisk;

use App\Models\PostureAssessment;
use App\Models\WeeklyProgram;

class SpariskPersonalProgramEngine
{
    /**
     * Generate a personalized weekly program based on assessment.
     */
    public function generate(PostureAssessment $assessment, int $weekNumber = 1): array
    {
        $template = config('sparisk.weekly_program_templates.standard');
        $program = [];
        $orderIndex = 0;

        $exerciseIds = $assessment->exerciseRecommendations()
            ->pluck('id')
            ->implode(',');

        foreach ($template as $day) {
            $activityDetails = $this->getActivityDetails(
                $day['activity_type'],
                $weekNumber,
                $assessment
            );

            $duration = $this->getActivityDuration($day['activity_type']);

            $entry = WeeklyProgram::create([
                'posture_assessment_id' => $assessment->id,
                'week_number' => $weekNumber,
                'day_of_week' => $day['day'],
                'activity_type' => $day['activity_type'],
                'activity_title' => $day['title'],
                'activity_details' => $activityDetails,
                'duration_minutes' => $duration,
                'exercise_ids' => $exerciseIds,
                'order_index' => $orderIndex++,
            ]);

            $program[] = $entry;
        }

        return $program;
    }

    /**
     * Generate multi-week progressive program.
     */
    public function generateMultiWeek(PostureAssessment $assessment, int $totalWeeks = 6): array
    {
        $allPrograms = [];

        for ($week = 1; $week <= $totalWeeks; $week++) {
            $allPrograms[$week] = $this->generate($assessment, $week);
        }

        return $allPrograms;
    }

    private function getActivityDetails(string $type, int $week, PostureAssessment $assessment): string
    {
        $progression = config('sparisk.progression_stages');
        $stage = min($week, count($progression));

        return match ($type) {
            'aquatic_therapy' => "Stage {$stage}: " . ($progression[$stage]['name'] ?? 'Aquatic exercises')
                . " — " . ($progression[$stage]['focus'] ?? ''),
            'massage' => 'Focus on recommended body areas based on posture assessment. Use moderate pressure.',
            'stretching' => 'Full body stretching routine. Hold each stretch for 20-30 seconds.',
            'balance_exercise' => 'Single leg balance, tandem stance, dynamic balance challenges.',
            'strength_training' => 'Body weight exercises targeting core stability and postural muscles.',
            'rest' => 'Active recovery. Light walking or gentle movement encouraged.',
            'family_activity' => 'Recreational activity with family. Park, playground, or beach.',
            default => 'Follow recommended exercise prescription.',
        };
    }

    private function getActivityDuration(string $type): int
    {
        return match ($type) {
            'aquatic_therapy' => 45,
            'massage' => 30,
            'stretching' => 20,
            'balance_exercise' => 25,
            'strength_training' => 30,
            'rest' => 0,
            'family_activity' => 60,
            default => 30,
        };
    }
}
