<?php

namespace Database\Seeders;

use App\Models\PostureIntervention;
use Illuminate\Database\Seeder;

/**
 * Seeds starter CADANGAN INTERVENSI (exercise / intervention) entries per
 * posture type. Defaults are scoped to the ADOLESCENT (13-18 years) age group
 * as required; an admin can extend them for any age group via the admin page.
 *
 * Each row: [posture_type, category, title, description, sets_reps, frequency, duration_minutes]
 */
class InterventionSeeder extends Seeder
{
    private const AGE_GROUP = '13-18';

    // [posture_type, category, title, description, sets_reps, frequency, duration_minutes]
    private const DATA = [
        'normal_neutral' => [
            ['exercise', 'Floating Relaxation', 'Relax the whole body in a supine floating position, focusing on breathing.', '3 x 5 min', 'Daily', 15],
            ['exercise', 'Postural Awareness Walk', 'Walk through the pool maintaining a tall, aligned posture.', '3 x 10 reps', 'Daily', 10],
            ['exercise', 'Gentle Core Stability', 'Prone float with core engagement to maintain neutral alignment.', '3 x 8 reps', '3x per week', 15],
        ],
        'forward_head' => [
            ['postural_awareness', 'Chin Tuck Float', 'Perform chin tucks while floating to reduce forward head position.', '3 x 10 reps', 'Daily', 10],
            ['strengthening', 'Deep Neck Flexor Activation', 'Gently activate deep neck flexors against water resistance.', '3 x 10 reps', 'Daily', 10],
            ['stretching', 'Upper Trap Stretch', 'Side-lying neck stretch against water buoyancy.', '2 x 30 sec', 'Daily', 10],
        ],
        'kyphosis' => [
            ['stretching', 'Chest Opener Float', 'Supine float with arms open to stretch the anterior chest wall.', '3 x 30 sec', 'Daily', 10],
            ['strengthening', 'Scapular Retraction Drill', 'Squeeze shoulder blades together against water resistance.', '3 x 12 reps', '3x per week', 15],
            ['exercise', 'Thoracic Extension Float', 'Float with controlled thoracic extension to reduce rounding.', '3 x 8 reps', '3x per week', 15],
        ],
        'lordosis' => [
            ['postural_awareness', 'Pelvic Neutral Float', 'Find and hold a neutral pelvic tilt while floating.', '3 x 10 reps', 'Daily', 10],
            ['stretching', 'Hip Flexor Release', 'Kneeling float to gently stretch the hip flexors.', '2 x 30 sec', 'Daily', 10],
            ['strengthening', 'Deep Abdominal Bracing', 'Brace the deep core to support the lumbar spine.', '3 x 10 reps', '3x per week', 15],
        ],
        'kyphosis_lordosis' => [
            ['exercise', 'Posture Integration Flow', 'Combine thoracic extension with pelvic neutral in one flow.', '3 x 8 reps', '3x per week', 15],
            ['stretching', 'Chest & Hip Flexor Stretch', 'Simultaneously open the chest and hip flexors in the water.', '2 x 30 sec', 'Daily', 10],
            ['strengthening', 'Deep Core & Scapular Control', 'Combine scapular retraction with core bracing.', '3 x 10 reps', '3x per week', 15],
        ],
        'flatback' => [
            ['exercise', 'Lumbar Mobility Drill', 'Gentle repeated lumbar flexion and extension in the water.', '3 x 10 reps', 'Daily', 10],
            ['strengthening', 'Spinal Extension Flow', 'Strengthen spinal extensors to restore a balanced curve.', '3 x 10 reps', '3x per week', 15],
            ['postural_awareness', 'Dynamic Rotation', 'Add trunk rotation to promote spinal flexibility.', '3 x 8 reps', '3x per week', 15],
        ],
        'genu_recurvatum' => [
            ['strengthening', 'Hamstring Activation', 'Strengthen hamstrings to control knee hyperextension.', '3 x 10 reps', '3x per week', 15],
            ['postural_awareness', 'Knee Alignment Float', 'Practice keeping knees softly bent in a neutral floating position.', '3 x 10 reps', 'Daily', 10],
            ['exercise', 'Quadriceps Control', 'Control knee extension range against water resistance.', '3 x 10 reps', '3x per week', 15],
        ],
        'frontal_asymmetry' => [
            ['strengthening', 'Bilateral Symmetry Drill', 'Perform symmetrical exercises to reduce frontal asymmetry.', '3 x 10 reps', '3x per week', 15],
            ['postural_awareness', 'Mirror Work in Water', 'Observe alignment in the water to correct asymmetry.', '3 x 5 min', 'Daily', 10],
            ['exercise', 'Weight Shift Balance', 'Practice even weight distribution across both sides.', '3 x 8 reps', '3x per week', 10],
        ],
        'pronated_foot' => [
            ['strengthening', 'Intrinsic Foot Muscle Drill', 'Strengthen the arch-supporting muscles of the foot.', '3 x 10 reps', 'Daily', 10],
            ['exercise', 'Foot Alignment Walk', 'Walk in water focusing on neutral foot and ankle alignment.', '3 x 20 steps', 'Daily', 10],
            ['postural_awareness', 'Ankle Stability Float', 'Train ankle stabilisers with a floating balance challenge.', '3 x 10 reps', '3x per week', 10],
        ],
    ];

    public function run(): void
    {
        foreach (self::DATA as $postureType => $items) {
            $label = config('sparisk.intervention_posture_types.' . $postureType) ?? $postureType;

            foreach ($items as $index => [$category, $title, $description, $setsReps, $frequency, $duration]) {
                PostureIntervention::updateOrCreate(
                    ['posture_type' => $postureType, 'age_group' => self::AGE_GROUP, 'title' => $title],
                    [
                        'posture_label' => $label,
                        'category' => $category,
                        'description' => $description,
                        'sets_reps' => $setsReps,
                        'frequency' => $frequency,
                        'duration_minutes' => $duration,
                        'order_index' => $index,
                    ]
                );
            }
        }
    }
}
