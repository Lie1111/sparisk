<?php

namespace Database\Seeders;

use App\Models\MuscleCondition;
use App\Models\PostureIntervention;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        'flat_back' => [
            ['exercise', 'Lumbar Mobility Drill', 'Stand in chest-deep water. Cat-cow stretch, gentle trunk rotations.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['strengthening', 'Prone Lumbar Arch Float', 'Float face down with a noodle and gently lift the chest to strengthen the back extensors and restore the lower-back curve.', '3 x 30 sec', 'Daily', 10, 'beginner'],
            ['strengthening', 'High-Knee Water March', 'Stand in waist-deep water and lift the knees high one at a time to strengthen the weak hip flexors.', '3 x 12 reps', '3x per week', 15, 'beginner'],
            ['strengthening', 'Pool-Edge Glute Kickback', 'Hold the pool edge and push one straight leg backwards, squeezing the buttock to strengthen the gluteus maximus.', '3 x 12 reps each leg', '3x per week', 15, 'intermediate'],
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
        'sway_back' => [
            ['strengthening', 'Glute Bridge Float', 'Supine float, press the hips into extension using the gluteals only.', '3 x 10 reps', '3x per week', 15, 'intermediate'],
            ['strengthening', 'High-Knee Water March', 'Stand in waist-deep water and lift the knees high one at a time to strengthen the weak hip flexors.', '3 x 12 reps', '3x per week', 15, 'beginner'],
            ['postural_awareness', 'Rib-Pelvis Stacking', 'Stand in shallow water and stack the ribs directly over the pelvis.', '3 x 30 sec', 'Daily', 10, 'beginner'],
        ],
        'postural_scoliosis' => [
            ['stretching', 'Side-Lying Float Stretch', 'Float on your side with the tight (concave) side facing up and reach the top arm overhead to lengthen it.', '3 x 30 sec', 'Daily', 10, 'beginner'],
            ['postural_awareness', 'Trunk Shift in Chest-Deep Water', 'Stand tall and shift the ribcage towards the convex side, hold for 5 seconds, then relax.', '3 x 10 reps', '3x per week', 10, 'beginner'],
            ['strengthening', 'Long-Reach Backstroke Kick', 'Backstroke kick with the convex-side arm reaching long overhead to build even trunk control.', '3 x 1 length', '3x per week', 15, 'intermediate'],
        ],
        'anterior_pelvic_tilt' => [
            ['postural_awareness', 'Pelvic Tuck Float', 'Float on your back and gently tuck the pelvis to flatten the lower back, then release.', '3 x 10 reps', 'Daily', 10, 'beginner'],
            ['stretching', 'Water Lunge Hip Flexor Stretch', 'In a lunge at the pool wall, tuck the pelvis to stretch the front of the back hip.', '3 x 30 sec each leg', '3x per week', 10, 'beginner'],
            ['strengthening', 'Pool-Edge Glute Kickback', 'Hold the pool edge and push one straight leg backwards, squeezing the buttock.', '3 x 12 reps each leg', '3x per week', 15, 'intermediate'],
        ],
        'posterior_pelvic_tilt' => [
            ['stretching', 'Prone Lumbar Arch Float', 'Float face down with a noodle and gently lift the chest to restore the lower-back curve.', '3 x 30 sec', 'Daily', 10, 'beginner'],
            ['postural_awareness', 'Long-Stride Water Walk', 'Walk across the pool with long strides and a tall chest, keeping a gentle lower-back curve.', '3 x 1 length', '3x per week', 10, 'beginner'],
            ['strengthening', 'High-Knee Water March', 'Stand in waist-deep water and lift the knees high one at a time to work the hip flexors.', '3 x 12 reps', '3x per week', 15, 'intermediate'],
        ],
        'uneven_shoulder' => [
            ['stretching', 'Shoulder Shrug Release', 'In shoulder-deep water, lift both shoulders and let them drop, relaxing the higher side.', '3 x 10 reps', 'Daily', 5, 'beginner'],
            ['strengthening', 'Pool-Wall Scapula Squeeze', 'Face the wall, pull the shoulder blades back and down evenly and hold for 3 seconds.', '3 x 12 reps', '3x per week', 10, 'beginner'],
            ['strengthening', 'Breaststroke Arm Pull', 'Standing in the water, sweep both arms in a slow breaststroke pull with even effort on each side.', '3 x 1 length', '3x per week', 15, 'intermediate'],
        ],
        'genu_valgum' => [
            ['strengthening', 'Side-Step Water Walk', 'Step sideways across the pool keeping the knees in line with the toes.', '3 x 1 width', '3x per week', 10, 'beginner'],
            ['strengthening', 'Pool-Wall Clamshell', 'Lie on your side at the wall with knees bent and open the top knee against the water.', '3 x 12 reps each side', '3x per week', 10, 'beginner'],
            ['strengthening', 'Knees-Out Water Squat', 'Squat in chest-deep water while pushing the knees gently outwards over the toes.', '3 x 10 reps', '3x per week', 10, 'intermediate'],
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
        'flat_back' => [
            ['Hamstrings', 'Long strokes along the back of the thigh to release the tight hamstrings.', 10, '3x per week'],
            ['Abdominals', 'Light circular strokes over the abdomen to ease the tight rectus abdominis.', 5, '2x per week'],
            ['Upper Back', 'Gentle effleurage between the shoulder blades to relax the upper back.', 10, '2x per week'],
        ],
        'genu_recurvatum' => [
            ['Front Thigh (Quadriceps)', 'Kneading along the front of the thigh to relax the tight quadriceps that push the knee back.', 10, '3x per week'],
            ['Calf', 'Kneading strokes along the gastrocnemius and soleus with trigger point pressure.', 10, '3x per week'],
            ['Hip Flexors', 'Gentle pressure and strokes at the front of the hip to release the tight hip flexors.', 10, '2x per week'],
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
            ['Hip Flexors', 'Gentle pressure and strokes at the front of the hip to release the tight hip flexors.', 10, '3x per week'],
            ['Calf', 'Kneading along the calf to reduce the pull that keeps the knee flexed.', 10, '2x per week'],
        ],
        'sway_back' => [
            ['Hamstrings', 'Long strokes along the back of the thigh to release the tight hamstrings.', 10, '3x per week'],
            ['Upper Abdominals', 'Light circular strokes over the upper abdomen to ease the tight upper rectus abdominis.', 5, '2x per week'],
            ['Side Waist (Upper Obliques)', 'Gentle kneading along the upper side waist to relax the tight obliques.', 5, '2x per week'],
        ],
        'postural_scoliosis' => [
            ['Lower Back (Concave Side)', 'Slow strokes along the tight side of the lower back and quadratus lumborum.', 10, '3x per week'],
            ['Side Waist (Obliques)', 'Gentle kneading of the side waist on the tight side to help it lengthen.', 10, '2x per week'],
            ['Upper Back', 'Light effleurage between the shoulder blades to relax the upper back evenly.', 10, '2x per week'],
        ],
        'anterior_pelvic_tilt' => [
            ['Hip Flexors', 'Gentle pressure and strokes at the front of the hip to release the hip flexors.', 10, '3x per week'],
            ['Lower Back', 'Long strokes along the lumbar muscles to ease lower-back tightness.', 10, '3x per week'],
            ['Front Thigh (Quadriceps)', 'Kneading along the front of the thigh to relax the rectus femoris.', 10, '2x per week'],
        ],
        'posterior_pelvic_tilt' => [
            ['Hamstrings', 'Long strokes along the back of the thigh to release tight hamstrings.', 10, '3x per week'],
            ['Buttocks (Gluteals)', 'Circular kneading over the buttocks to relax the gluteal muscles.', 10, '3x per week'],
            ['Abdominals', 'Light circular strokes over the abdomen to ease tight abdominal muscles.', 5, '2x per week'],
        ],
        'uneven_shoulder' => [
            ['Upper Trapezius (High Side)', 'Kneading along the top of the higher shoulder to relax the upper trapezius.', 10, '3x per week'],
            ['Neck (SCM and Scalenes)', 'Gentle strokes down the side of the neck on the tight side.', 5, '3x per week'],
            ['Between the Shoulder Blades', 'Light strokes around the shoulder blades to balance both sides.', 10, '2x per week'],
        ],
        'genu_valgum' => [
            ['Inner Thigh (Adductors)', 'Long strokes along the inner thigh to release the tight adductors.', 10, '3x per week'],
            ['Outer Hip (TFL / IT Band)', 'Gentle kneading along the outer hip and thigh to ease the TFL.', 10, '3x per week'],
            ['Back of Thigh', 'Long strokes along the hamstrings to relax the biceps femoris.', 5, '2x per week'],
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
        'flat_back' => [
            ['strengthening', 'Prone Extension', 'Lift the upper body gently from prone to strengthen spinal extensors.', '3 x 8 reps', '3x per week', 10, 'beginner'],
            ['stretching', 'Hamstring Stretch', 'Sit with one leg straight and lean forward from the hips until you feel a stretch.', '3 x 30 sec each leg', 'Daily', 5, 'beginner'],
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
        'sway_back' => [
            ['strengthening', 'Glute Bridge', 'Bridge while keeping the ribs down and the pelvis tucked.', '3 x 10 reps', '3x per week', 10, 'beginner'],
            ['postural_awareness', 'Rib Down Breathing', 'Breathe into the lower ribs while keeping the pelvis tucked.', '3 x 1 min', 'Daily', 5, 'beginner'],
        ],
        'postural_scoliosis' => [
            ['strengthening', 'Side Plank (Convex Side Down)', 'Hold a side plank on the convex side to strengthen the weaker trunk muscles.', '3 x 20 sec', '3x per week', 5, 'intermediate'],
            ['lifestyle', 'Mirror Posture Check', 'Stand in front of a mirror and level the shoulders and waist line.', '2 x 1 min', 'Daily', 3, 'beginner'],
        ],
        'anterior_pelvic_tilt' => [
            ['strengthening', 'Glute Bridge', 'Lie on your back, tuck the pelvis and lift the hips by squeezing the buttocks.', '3 x 12 reps', '3x per week', 5, 'beginner'],
            ['strengthening', 'Dead Bug Core Hold', 'On your back, keep the lower back flat while slowly lowering the opposite arm and leg.', '3 x 10 reps', '3x per week', 5, 'intermediate'],
        ],
        'posterior_pelvic_tilt' => [
            ['stretching', 'Hamstring Stretch', 'Sit with one leg straight and lean forward from the hips until you feel a stretch.', '3 x 30 sec each leg', 'Daily', 5, 'beginner'],
            ['exercise', 'Cat-Camel Mobility', 'On hands and knees, gently arch and round the back to restore lower-back movement.', '2 x 10 reps', 'Daily', 3, 'beginner'],
        ],
        'uneven_shoulder' => [
            ['stretching', 'Neck Side Stretch', 'Tilt the ear towards the lower shoulder to stretch the tight upper trapezius.', '3 x 20 sec each side', 'Daily', 3, 'beginner'],
            ['strengthening', 'Band Pull-Apart', 'Hold a band at shoulder height and pull it apart, squeezing both shoulder blades evenly.', '3 x 12 reps', '3x per week', 5, 'beginner'],
        ],
        'genu_valgum' => [
            ['strengthening', 'Side-Lying Leg Raise', 'Lie on your side and lift the top leg slightly behind you to work the gluteus medius.', '3 x 12 reps each side', '3x per week', 5, 'beginner'],
            ['exercise', 'Single-Leg Balance', 'Stand on one leg with the knee soft and in line with the toes.', '3 x 30 sec each leg', 'Daily', 3, 'beginner'],
        ],
    ];

    public function run(): void
    {
        // Every key in the catalogue, labelled from config or the muscle-map
        // condition it belongs to.
        $postureTypes = array_unique(array_merge(array_keys(self::AQUATIC), array_keys(self::MASSAGE), array_keys(self::GENERAL)));
        $conditions = MuscleCondition::pluck('name', 'slug');

        foreach ($postureTypes as $postureType) {
            $label = config("sparisk.intervention_posture_types.{$postureType}")
                ?? $conditions[$postureType]
                ?? Str::headline($postureType);

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
                // Days the admin already picked are kept, like the image.
                'days' => $existing?->days ?: PostureIntervention::defaultDays($attributes['frequency'] ?? null, $program),
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
