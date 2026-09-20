<?php

namespace Database\Seeders;

use App\Models\PostureIntervention;
use Illuminate\Database\Seeder;

/**
 * Seeds the starter CADANGAN INTERVENSI catalogue for every program the app
 * renders on the Program tab:
 *
 *  - aquatic_exercise  -> "Aquatic Exercises"
 *  - massage_therapy   -> "Massage Therapy Plan"
 *  - general_exercise  -> land based general exercises
 *
 * Defaults are scoped to the ADOLESCENT (13-18 years) age group as required by
 * the clinical spec; an admin extends them for other age groups on the admin
 * page. Every entry carries an image URL so the app has a picture to show next
 * to each exercise / massage area.
 */
class InterventionSeeder extends Seeder
{
    private const AGE_GROUP = '13-18';

    /**
     * Aquatic exercise catalogue.
     *
     * [category, title, description, sets_reps, frequency, duration_minutes, level]
     */
    private const AQUATIC = [
        'normal_neutral' => [
            ['exercise', 'Floating Relaxation', 'Relax the whole body in a supine floating position, focusing on breathing.', '3 x 5 min', 'Daily', 15, 'beginner'],
            ['postural_awareness', 'Postural Awareness Walk', 'Walk through the pool maintaining a tall, aligned posture.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['strengthening', 'Gentle Core Stability', 'Prone float with core engagement to maintain neutral alignment.', '3 x 8 reps', '3x per week', 15, 'intermediate'],
        ],
        'forward_head' => [
            ['postural_awareness', 'Chin Tuck Float', 'Perform chin tucks while floating to reduce forward head position.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['strengthening', 'Deep Neck Flexor Activation', 'Gently activate deep neck flexors against water resistance.', '3 x 10 reps', 'Daily', 10, 'intermediate'],
            ['stretching', 'Upper Trap Stretch', 'Side-lying neck stretch against water buoyancy.', '2 x 30 sec', 'Daily', 10, 'beginner'],
        ],
        'kyphosis' => [
            ['stretching', 'Chest Opener Float', 'Supine float with arms open to stretch the anterior chest wall.', '3 x 30 sec', 'Daily', 10, 'beginner'],
            ['strengthening', 'Scapular Retraction Drill', 'Squeeze shoulder blades together against water resistance.', '3 x 12 reps', '3x per week', 15, 'intermediate'],
            ['exercise', 'Thoracic Extension Float', 'Float with controlled thoracic extension to reduce rounding.', '3 x 8 reps', '3x per week', 15, 'intermediate'],
        ],
        'lordosis' => [
            ['postural_awareness', 'Pelvic Neutral Float', 'Find and hold a neutral pelvic tilt while floating.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['stretching', 'Hip Flexor Release', 'Kneeling float to gently stretch the hip flexors.', '2 x 30 sec', 'Daily', 10, 'beginner'],
            ['strengthening', 'Deep Abdominal Bracing', 'Brace the deep core to support the lumbar spine.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
        ],
        'kyphosis_lordosis' => [
            ['exercise', 'Posture Integration Flow', 'Combine thoracic extension with pelvic neutral in one flow.', '3 x 8 reps', '3x per week', 15, 'intermediate'],
            ['stretching', 'Chest & Hip Flexor Stretch', 'Simultaneously open the chest and hip flexors in the water.', '2 x 30 sec', 'Daily', 10, 'intermediate'],
            ['strengthening', 'Deep Core & Scapular Control', 'Combine scapular retraction with core bracing.', '3 x 10 reps', '3x per week', 15, 'advanced'],
        ],
        'flatback' => [
            ['exercise', 'Lumbar Mobility Drill', 'Stand in chest-deep water. Cat-cow stretch, gentle trunk rotations.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['exercise', 'Dynamic Rotation', 'Stand with feet planted. Rotate trunk side to side with arm movement.', '3 x 8 reps', '3x per week', 15, 'intermediate'],
            ['postural_awareness', 'Balance Training', 'Stand on one leg in water. Progress to eyes closed.', '3 x 30 sec', '3x per week', 15, 'intermediate'],
            ['exercise', 'Spinal Extension Flow', 'Float prone. Alternate between arching and rounding spine gently.', '3 x 8 reps', '3x per week', 15, 'intermediate'],
        ],
        'genu_recurvatum' => [
            ['strengthening', 'Hamstring Activation', 'Strengthen hamstrings to control knee hyperextension.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
            ['postural_awareness', 'Knee Alignment Float', 'Practice keeping knees softly bent in a neutral floating position.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['exercise', 'Quadriceps Control', 'Control knee extension range against water resistance.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
        ],
        'frontal_asymmetry' => [
            ['strengthening', 'Bilateral Symmetry Drill', 'Perform symmetrical exercises to reduce frontal asymmetry.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
            ['postural_awareness', 'Mirror Work in Water', 'Observe alignment in the water to correct asymmetry.', '3 x 5 min', 'Daily', 10, 'beginner'],
            ['exercise', 'Weight Shift Balance', 'Practice even weight distribution across both sides.', '3 x 8 reps', '3x per week', 10, 'intermediate'],
        ],
        'pronated_foot' => [
            ['strengthening', 'Intrinsic Foot Muscle Drill', 'Strengthen the arch-supporting muscles of the foot.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['exercise', 'Foot Alignment Walk', 'Walk in water focusing on neutral foot and ankle alignment.', '3 x 20 steps', 'Daily', 10, 'beginner'],
            ['postural_awareness', 'Ankle Stability Float', 'Train ankle stabilisers with a floating balance challenge.', '3 x 10 reps', '3x per week', 10, 'intermediate'],
        ],
        'flexed_knee' => [
            ['strengthening', 'Knee Extension Float', 'Seated on the pool step, slowly straighten the knee against water resistance.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
            ['stretching', 'Hamstring Float Stretch', 'Supine float with the leg extended to lengthen the hamstrings.', '2 x 30 sec', 'Daily', 10, 'beginner'],
            ['postural_awareness', 'Standing Knee Alignment', 'Stand in chest-deep water and hold a straight but unlocked knee.', '3 x 30 sec', 'Daily', 10, 'beginner'],
        ],
        'swayback' => [
            ['strengthening', 'Glute Bridge Float', 'Supine float, press the hips into extension using the gluteals only.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
            ['stretching', 'Hip Flexor Float Stretch', 'Kneeling float with a posterior pelvic tilt to lengthen the hip flexors.', '2 x 30 sec', 'Daily', 10, 'beginner'],
            ['postural_awareness', 'Rib-Pelvis Stacking', 'Stand in shallow water and stack the ribs directly over the pelvis.', '3 x 30 sec', 'Daily', 10, 'beginner'],
        ],
    ];

    /**
     * Massage therapy plan.
     *
     * [title (body area), description, duration_minutes, frequency]
     */
    private const MASSAGE = [
        'normal_neutral' => [
            ['Neck', 'Gentle maintenance massage along the cervical spine to keep the neck relaxed.', 10, '3x per week'],
            ['Chest', 'Open palm strokes across the pectorals to keep the chest wall mobile.', 10, '3x per week'],
            ['Lower Back', 'Light effleurage along the lumbar spine to maintain soft tissue balance.', 10, '3x per week'],
        ],
        'forward_head' => [
            ['Neck', 'Circular pressure along the cervical spine with focus on the suboccipital region.', 10, '3x per week'],
            ['Upper Trap', 'Firm kneading along the upper trapezius from neck to shoulder.', 10, '3x per week'],
            ['Chest', 'Release the pectoralis minor to allow the shoulders to sit back.', 10, '2x per week'],
        ],
        'kyphosis' => [
            ['Chest', 'Sustained pressure on tight pectoral bands to open the chest wall.', 10, '3x per week'],
            ['Thoracic', 'Kneading along the thoracic spine focusing on rhomboids and mid trapezius.', 10, '3x per week'],
            ['Upper Trap', 'Firm kneading along the upper trapezius to reduce shoulder tension.', 10, '2x per week'],
        ],
        'lordosis' => [
            ['Lower Back', 'Gentle effleurage along the lumbar spine with sustained pressure on tight paraspinals.', 10, '3x per week'],
            ['Hip Flexor', 'Deep pressure to the anterior hip region using slow, sustained strokes.', 10, '3x per week'],
            ['Hamstring', 'Long strokes along the hamstring belly with cross-fibre friction.', 10, '2x per week'],
        ],
        'kyphosis_lordosis' => [
            ['Chest', 'Sustained pressure across the pectorals to release the rounded upper back.', 10, '3x per week'],
            ['Lower Back', 'Gentle lumbar effleurage to ease the increased lumbar curve.', 10, '3x per week'],
            ['Upper Trap', 'Kneading from neck to shoulder to settle the shoulder girdle.', 10, '2x per week'],
        ],
        'flatback' => [
            ['Thoracic', 'Kneading along the thoracic spine to restore segmental mobility.', 10, '3x per week'],
            ['Lower Back', 'Gentle effleurage with sustained pressure on the lumbar paraspinals.', 10, '3x per week'],
            ['Glute', 'Deep kneading on the gluteals to support a neutral pelvis.', 10, '2x per week'],
        ],
        'genu_recurvatum' => [
            ['Hamstring', 'Cross-fibre friction along the hamstring to support knee control.', 10, '3x per week'],
            ['Calf', 'Kneading strokes along the gastrocnemius and soleus with trigger point pressure.', 10, '3x per week'],
            ['Glute', 'Deep kneading on the gluteals to improve lower limb alignment.', 10, '2x per week'],
        ],
        'frontal_asymmetry' => [
            ['Neck', 'Balanced circular massage on both sides of the cervical spine.', 10, '3x per week'],
            ['Upper Trap', 'Compare and even out tissue tension between the left and right trapezius.', 10, '3x per week'],
            ['Glute', 'Deep kneading on the gluteals to even out pelvic support.', 10, '2x per week'],
        ],
        'pronated_foot' => [
            ['Calf', 'Deep kneading along the calf to release the plantar flexors.', 10, '3x per week'],
            ['Hamstring', 'Long strokes along the hamstring to support lower limb mechanics.', 10, '2x per week'],
            ['Glute', 'Deep kneading on the gluteals to control foot pronation.', 10, '2x per week'],
        ],
        'flexed_knee' => [
            ['Hamstring', 'Cross-fibre friction along the hamstrings to support knee extension control.', 10, '3x per week'],
            ['Quadriceps', 'Long strokes along the quadriceps to release the shortened anterior thigh.', 10, '3x per week'],
            ['Calf', 'Kneading along the calf to reduce the pull that keeps the knee flexed.', 10, '2x per week'],
        ],
        'swayback' => [
            ['Lower Back', 'Gentle effleurage along the lumbar spine to ease the flattened lumbar curve.', 10, '3x per week'],
            ['Hip Flexor', 'Sustained pressure on the hip flexors to reduce the anterior pelvic shift.', 10, '3x per week'],
            ['Glute', 'Deep kneading on the gluteals to restore pelvic support.', 10, '2x per week'],
        ],
    ];

    /**
     * Land based general exercises.
     *
     * [category, title, description, sets_reps, frequency, duration_minutes, level]
     */
    private const GENERAL = [
        'normal_neutral' => [
            ['lifestyle', 'Daily Posture Check', 'Stand against a wall and reset the head, shoulders and pelvis three times a day.', '3 x 1 min', 'Daily', 5, 'beginner'],
            ['postural_awareness', 'Desk Set-Up Review', 'Adjust chair, screen and keyboard height to keep a neutral spine.', '1 x 5 min', 'Weekly', 5, 'beginner'],
        ],
        'forward_head' => [
            ['strengthening', 'Chin Tuck (Land)', 'Seated chin tucks with a 5 second hold to activate deep neck flexors.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['stretching', 'Doorway Chest Stretch', 'Forearms on a door frame, step through to stretch the chest.', '2 x 30 sec', 'Daily', 5, 'beginner'],
        ],
        'kyphosis' => [
            ['stretching', 'Thoracic Foam Roll', 'Roll the upper back over a foam roller to restore extension.', '2 x 10 reps', 'Daily', 10, 'intermediate'],
            ['strengthening', 'Prone Y-T-W Raises', 'Lift the arms into Y, T and W positions to strengthen scapular muscles.', '3 x 8 reps', '3x per week', 10, 'intermediate'],
        ],
        'lordosis' => [
            ['strengthening', 'Dead Bug', 'Alternate opposite arm and leg while bracing the deep core.', '3 x 8 reps', '3x per week', 10, 'intermediate'],
            ['stretching', 'Kneeling Hip Flexor Stretch', 'Tuck the pelvis and lean forward to lengthen the hip flexor.', '2 x 30 sec', 'Daily', 5, 'beginner'],
        ],
        'kyphosis_lordosis' => [
            ['strengthening', 'Bird Dog', 'Extend the opposite arm and leg while holding a neutral spine.', '3 x 8 reps', '3x per week', 10, 'intermediate'],
            ['postural_awareness', 'Wall Slide', 'Slide the arms up the wall keeping the ribs down and back flat.', '3 x 10 reps', '3x per week', 10, 'intermediate'],
        ],
        'flatback' => [
            ['strengthening', 'Prone Extension', 'Lift the upper body gently from prone to strengthen spinal extensors.', '3 x 8 reps', '3x per week', 10, 'beginner'],
            ['postural_awareness', 'Cat-Cow Mobility', 'Flow between flexion and extension on all fours.', '3 x 10 reps', 'Daily', 5, 'beginner'],
        ],
        'genu_recurvatum' => [
            ['strengthening', 'Hamstring Bridge', 'Bridge with the knees softly bent to control hyperextension.', '3 x 10 reps', '3x per week', 10, 'beginner'],
            ['postural_awareness', 'Step-Down Control', 'Slowly step down from a low platform keeping the knee over the foot.', '3 x 8 reps', '3x per week', 10, 'intermediate'],
        ],
        'frontal_asymmetry' => [
            ['strengthening', 'Single Leg Stance', 'Balance on each leg equally to compare and train both sides.', '3 x 30 sec', 'Daily', 5, 'beginner'],
            ['postural_awareness', 'Mirror Alignment Check', 'Check shoulder and hip height in a mirror and self-correct.', '3 x 1 min', 'Daily', 5, 'beginner'],
        ],
        'pronated_foot' => [
            ['strengthening', 'Short Foot Exercise', 'Draw the base of the big toe toward the heel to lift the arch.', '3 x 10 reps', 'Daily', 5, 'beginner'],
            ['strengthening', 'Calf Raise', 'Rise onto the toes slowly and lower with control.', '3 x 12 reps', '3x per week', 10, 'beginner'],
        ],
        'flexed_knee' => [
            ['strengthening', 'Quad Set', 'Tighten the quadriceps with the knee straight and hold for 5 seconds.', '3 x 10 reps', 'Daily', 5, 'beginner'],
            ['stretching', 'Standing Hamstring Stretch', 'Place the heel on a low step and hinge forward with a flat back.', '2 x 30 sec', 'Daily', 5, 'beginner'],
        ],
        'swayback' => [
            ['strengthening', 'Glute Bridge', 'Bridge while keeping the ribs down and the pelvis tucked.', '3 x 10 reps', '3x per week', 10, 'beginner'],
            ['postural_awareness', 'Rib Down Breathing', 'Breathe into the lower ribs while keeping the pelvis tucked.', '3 x 1 min', 'Daily', 5, 'beginner'],
        ],
    ];

    public function run(): void
    {
        foreach (config('sparisk.intervention_posture_types') as $postureType => $label) {
            $this->seedAquatic($postureType, $label);
            $this->seedMassage($postureType, $label);
            $this->seedGeneral($postureType, $label);
        }
    }

    private function seedAquatic(string $postureType, string $label): void
    {
        foreach (self::AQUATIC[$postureType] ?? [] as $index => [$category, $title, $description, $setsReps, $frequency, $duration, $level]) {
            $this->upsert($postureType, $label, 'aquatic_exercise', $index, [
                'category' => $category,
                'level' => $level,
                'title' => $title,
                'description' => $description,
                'sets_reps' => $setsReps,
                'frequency' => $frequency,
                'duration_minutes' => $duration,
                'image_url' => $this->imageUrl(
                    "Aquatic physiotherapy: {$title} performed in a warm therapy pool, patient guided by a physiotherapist, clean bright clinical photography"
                ),
            ]);
        }
    }

    private function seedMassage(string $postureType, string $label): void
    {
        foreach (self::MASSAGE[$postureType] ?? [] as $index => [$title, $description, $duration, $frequency]) {
            $this->upsert($postureType, $label, 'massage_therapy', $index, [
                'category' => 'manual_therapy',
                'level' => null,
                'title' => $title,
                'description' => $description,
                'sets_reps' => null,
                'frequency' => $frequency,
                'duration_minutes' => $duration,
                'image_url' => $this->imageUrl(
                    "Professional massage therapy of the {$title} area, therapist hands on a client on a treatment table, calm spa lighting, clean clinical photography"
                ),
            ]);
        }
    }

    private function seedGeneral(string $postureType, string $label): void
    {
        foreach (self::GENERAL[$postureType] ?? [] as $index => [$category, $title, $description, $setsReps, $frequency, $duration, $level]) {
            $this->upsert($postureType, $label, 'general_exercise', $index, [
                'category' => $category,
                'level' => $level,
                'title' => $title,
                'description' => $description,
                'sets_reps' => $setsReps,
                'frequency' => $frequency,
                'duration_minutes' => $duration,
                'image_url' => $this->imageUrl(
                    "Posture correction exercise: {$title}, person training in a clean physiotherapy studio, bright clinical photography"
                ),
            ]);
        }
    }

    /**
     * Keyed by posture type + age group + title (not program) so re-running the
     * seeder updates rows created before programs existed instead of creating a
     * second copy of every exercise.
     *
     * @param  array<string, mixed>  $attributes
     */
    private function upsert(string $postureType, string $label, string $program, int $orderIndex, array $attributes): void
    {
        $existing = PostureIntervention::query()
            ->where('posture_type', $postureType)
            ->where('age_group', self::AGE_GROUP)
            ->where('title', $attributes['title'])
            ->first();

        // The starter catalogue must never replace an image the admin set up
        // themselves. Without this, re-running the seeder would write the
        // generated link over an uploaded file and the app would stop showing
        // the admin's own picture.
        if ($existing && (!empty($existing->image_path) || !empty($existing->image_url))) {
            $attributes['image_url'] = $existing->image_url;
        }

        PostureIntervention::updateOrCreate(
            [
                'posture_type' => $postureType,
                'age_group' => self::AGE_GROUP,
                'title' => $attributes['title'],
            ],
            array_merge($attributes, [
                'posture_label' => $label,
                'program' => $program,
                'order_index' => $orderIndex,
            ])
        );
    }

    /**
     * Builds a live image URL for a seeded entry so the app has a picture to
     * show without shipping binary files in the repository.
     */
    private function imageUrl(string $prompt): string
    {
        return 'https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt='
            . rawurlencode($prompt)
            . '&image_size=landscape_4_3';
    }
}
