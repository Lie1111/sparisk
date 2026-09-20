<?php

namespace App\Services\Sparisk;

use App\Models\ExerciseRecommendation;
use App\Models\PostureAssessment;
use App\Models\PostureIntervention;

class SpariskAquaticRecommendationEngine
{
    /**
     * Program slug shared with config('sparisk.intervention_programs').
     */
    public const PROGRAM = 'aquatic_exercise';

    public function __construct(private SpariskMeasurementEngine $measurements) {}

    /**
     * Generate aquatic exercise recommendations for an assessment.
     *
     * The admin-authored CADANGAN INTERVENSI rows are the source of truth: the
     * title, level, prescription, description and image the admin saved are
     * copied onto the recommendation so the app shows exactly what was set up.
     * Only when the admin has nothing for this posture type / age band do we
     * fall back to the built-in stage defaults so the report is never empty.
     */
    public function recommend(PostureAssessment $assessment): array
    {
        $interventions = $this->adminInterventions($assessment);

        if ($interventions->isNotEmpty()) {
            return $this->copyFromAdmin($assessment, $interventions);
        }

        return $this->fromConfig($assessment);
    }

    /**
     * Admin rows for this assessment's posture type and age band, in the order
     * the admin arranged them.
     */
    private function adminInterventions(PostureAssessment $assessment)
    {
        $postureType = $this->measurements->resolveInterventionPostureType($assessment->posture_classification);

        $ageGroup = $this->measurements->resolveAgeGroup($assessment->patient?->age);

        $query = fn (string $age) => PostureIntervention::query()
            ->where('program', self::PROGRAM)
            ->where('posture_type', $postureType)
            ->where('age_group', $age)
            ->orderBy('order_index')
            ->orderBy('id')
            ->get();

        $interventions = $query($ageGroup);

        // The catalogue is normally authored for the adolescent band, so an
        // adult assessment would otherwise miss the admin's work entirely.
        if ($interventions->isEmpty() && $ageGroup !== '13-18') {
            $interventions = $query('13-18');
        }

        return $interventions;
    }

    /**
     * Build one recommendation per admin entry, carrying the image across.
     *
     * @param  \Illuminate\Support\Collection<int, PostureIntervention>  $interventions
     */
    private function copyFromAdmin(PostureAssessment $assessment, $interventions): array
    {
        $recommendations = [];

        foreach ($interventions as $orderIndex => $intervention) {
            $recommendations[] = ExerciseRecommendation::create([
                'posture_assessment_id' => $assessment->id,
                'engine' => 'SARE',
                'program' => self::PROGRAM,
                'exercise_name' => $intervention->title,
                'program_level' => $intervention->level,
                // `difficulty` is a NOT NULL enum, so an admin entry without a
                // level still needs a value.
                'difficulty' => $intervention->level ?? 'beginner',
                'estimated_duration_minutes' => $intervention->duration_minutes,
                'image_url' => $intervention->image_src,
                'sets_reps' => $intervention->sets_reps,
                'progression_stage' => $this->stageFromLevel($intervention->level),
                'instructions' => $intervention->description
                    ?: $this->getExerciseInstructions($intervention->title),
                'frequency' => $intervention->frequency,
                'order_index' => $orderIndex,
            ]);
        }

        return $recommendations;
    }

    /**
     * `progression_stage` is an integer column, so the admin's level chip is
     * stored as its stage number.
     */
    private function stageFromLevel(?string $level): ?int
    {
        return match ($level) {
            'beginner' => 1,
            'intermediate' => 2,
            'advanced' => 3,
            default => null,
        };
    }

    /**
     * Built-in stage-based defaults, used when the admin catalogue is empty.
     */
    private function fromConfig(PostureAssessment $assessment): array
    {
        $classification = $assessment->posture_classification ?? 'default';
        $exercises = config("sparisk.aquatic_exercises.{$classification}")
            ?? config('sparisk.aquatic_exercises.default');

        $recommendations = [];
        $orderIndex = 0;

        foreach ($exercises as $exercise) {
            $rec = ExerciseRecommendation::create([
                'posture_assessment_id' => $assessment->id,
                'engine' => 'SARE',
                'program' => self::PROGRAM,
                'exercise_name' => $exercise['name'],
                'program_level' => $exercise['level'],
                'difficulty' => $exercise['difficulty'],
                'estimated_duration_minutes' => $exercise['duration'],
                'progression_stage' => $exercise['stage'],
                'instructions' => $this->getExerciseInstructions($exercise['name']),
                'sets' => 3,
                'reps' => 10,
                'frequency' => '3x per week',
                'order_index' => $orderIndex++,
            ]);

            $recommendations[] = $rec;
        }

        return $recommendations;
    }

    /**
     * Get detailed instructions for each exercise.
     */
    private function getExerciseInstructions(string $name): string
    {
        $instructions = [
            'Floating Practice' => 'Lie on your back in water. Relax and let the water support your body. Focus on maintaining a neutral spine position.',
            'Back Float' => 'Position body supine in water with ear in water. Engage core. Arms extended overhead or at sides.',
            'Thoracic Extension Float' => 'Float on back with arms extended. Focus on opening chest and extending thoracic spine.',
            'Scapular Control Drill' => 'Stand in chest-deep water. Squeeze shoulder blades together and release. Control the movement.',
            'Arm Extension Reach' => 'Stand facing pool wall. Extend arms forward, reaching as far as comfortable.',
            'Chest Opener Stretch' => 'Stand sideways to wall. Place hand on wall, rotate trunk away.',
            'Pelvic Neutral Float' => 'Float on back. Rock pelvis to find neutral position. Hold.',
            'Hip Mobility Drill' => 'Stand in waist-deep water. Hip circles, leg swings forward/back, side to side.',
            'Core Stability Hold' => 'Hold plank position in shallow water. Engage deep core. Maintain breathing.',
            'Lower Back Release' => 'Float face down. Let lower back relax. Gentle pelvic tilts.',
            'Lumbar Mobility Drill' => 'Stand in chest-deep water. Cat-cow stretch, gentle trunk rotations.',
            'Dynamic Rotation' => 'Stand with feet planted. Rotate trunk side to side with arm movement.',
            'Balance Training' => 'Stand on one leg in water. Progress to eyes closed.',
            'Spinal Extension Flow' => 'Float prone. Alternate between arching and rounding spine gently.',
            'Neck Control Drill' => 'Float on back. Small, controlled head movements: nod, rotate.',
            'Vestibular Exercise' => 'Stand in water. Head turns while maintaining balance.',
            'Chin Tuck Float' => 'Float on back. Tuck chin toward chest. Hold, release.',
            'Chest Stretch Float' => 'Float on back. Arms out to sides. Feel chest opening.',
            'Scapular Retraction' => 'Stand. Squeeze shoulder blades. Hold 5 seconds. Release.',
            'Arm Circles' => 'Stand in chest-deep water. Draw circles with arms, forward and backward.',
            'Backstroke Drill' => 'Swim backstroke focusing on shoulder rotation and extension.',
            'Neck Stabilization' => 'Float with head supported. Gentle isometric holds each direction.',
            'Shoulder Girdle Control' => 'Stand. Shoulder shrugs, circles. Progress to resisted movements.',
            'Trunk Balance' => 'Sit on pool noodle or float. Maintain upright posture.',
            'Coordinated Breathing' => 'Practice rhythmic breathing while maintaining posture.',
            'Pelvic Control' => 'Stand in water. Anterior/posterior pelvic tilts with control.',
            'Foot Alignment' => 'Stand. Focus on even weight distribution. Practice foot positioning.',
            'Hip Stability' => 'Stand on one leg. Small hip circles. Progress to dynamic.',
            'Kicking Coordination' => 'Hold pool edge. Practice flutter kick with control.',
            'Water Adaptation' => 'Walk in water. Splash water on face. Blow bubbles. Basic submersions.',
            'Floating Basics' => 'Practice prone and supine floats with assistance if needed.',
            'Balance Drills' => 'Weight shifting exercises. Progress to single leg stance.',
            'Core Stability' => 'Planks, dead bugs, bird dogs in water.',
            'Swimming Progression' => 'Combine all skills into coordinated swimming.',
        ];

        return $instructions[$name] ?? 'Follow therapist guidance for proper form and technique.';
    }
}
