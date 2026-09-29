<?php

namespace Database\Seeders;

use App\Models\PostureMarker;
use Illuminate\Database\Seeder;

/**
 * Seeds the default red / green body markers drawn on the character images.
 *
 * Coordinates are normalized (0..1) relative to the character image and were
 * derived from the on-device MuscleAnalysis template so the app and the web
 * editor start from the same reference. Admins can edit them afterwards.
 */
class PostureMarkerSeeder extends Seeder
{
    public function run(): void
    {
        // [view, type, muscle, label, x, y, width, height]
        // x / y are the NORMALIZED CENTRE of the shape and were measured
        // against the transparent silhouette of the character PNGs.
        $markers = [
            // ---- Front view ----
            ['front', 'tight', 'Upper Trapezius / SCM', 'Neck / SCM', 0.495, 0.155, 0.160, 0.110],
            ['front', 'tight', 'Pectoralis Major (Left)', 'Chest L', 0.415, 0.285, 0.150, 0.120],
            ['front', 'tight', 'Pectoralis Major (Right)', 'Chest R', 0.585, 0.285, 0.150, 0.120],
            ['front', 'tight', 'Biceps Brachii (Left)', 'Biceps L', 0.225, 0.375, 0.095, 0.170],
            ['front', 'tight', 'Biceps Brachii (Right)', 'Biceps R', 0.775, 0.375, 0.095, 0.170],
            ['front', 'weak', 'Core Muscles (Deep Abdominals)', 'Core', 0.500, 0.430, 0.200, 0.120],
            ['front', 'tight', 'Rectus Femoris / Hip Flexors (Left)', 'Thigh L', 0.385, 0.625, 0.140, 0.145],
            ['front', 'tight', 'Rectus Femoris / Hip Flexors (Right)', 'Thigh R', 0.615, 0.625, 0.140, 0.145],
            ['front', 'tight', 'Gastrocnemius (Left)', 'Calf L', 0.382, 0.790, 0.085, 0.080],
            ['front', 'tight', 'Gastrocnemius (Right)', 'Calf R', 0.618, 0.790, 0.085, 0.080],

            // ---- Back view ----
            ['back', 'tight', 'Upper Trapezius / Levator Scapulae', 'Upper Trap', 0.500, 0.215, 0.260, 0.090],
            ['back', 'tight', 'Rhomboids', 'Rhomboids', 0.500, 0.295, 0.190, 0.090],
            ['back', 'weak', 'Latissimus Dorsi (Left)', 'Lat L', 0.395, 0.355, 0.100, 0.165],
            ['back', 'weak', 'Latissimus Dorsi (Right)', 'Lat R', 0.605, 0.355, 0.100, 0.165],
            ['back', 'tight', 'Erector Spinae', 'Erector Spinae', 0.500, 0.395, 0.090, 0.125],
            ['back', 'tight', 'Hamstrings (Left)', 'Hamstring L', 0.385, 0.635, 0.125, 0.140],
            ['back', 'tight', 'Hamstrings (Right)', 'Hamstring R', 0.615, 0.635, 0.125, 0.140],
            ['back', 'tight', 'Gastrocnemius (Left)', 'Calf L', 0.365, 0.770, 0.095, 0.105],
            ['back', 'tight', 'Gastrocnemius (Right)', 'Calf R', 0.635, 0.770, 0.095, 0.105],

            // ---- Right side view ----
            ['right_side', 'tight', 'Deep Neck Flexors / SCM', 'Head / Neck', 0.515, 0.115, 0.145, 0.145],
            ['right_side', 'tight', 'Pectoralis Major', 'Chest', 0.585, 0.290, 0.125, 0.130],
            ['right_side', 'tight', 'Thoracic Erector Spinae', 'Thoracic', 0.450, 0.350, 0.100, 0.190],
            ['right_side', 'weak', 'Deep Abdominals', 'Abdominal', 0.585, 0.430, 0.115, 0.115],
            ['right_side', 'weak', 'Gluteus Maximus', 'Gluteus', 0.450, 0.560, 0.130, 0.120],
            ['right_side', 'tight', 'Hip Flexors / Quadriceps', 'Upper Leg', 0.435, 0.680, 0.140, 0.150],
            ['right_side', 'tight', 'Gastrocnemius / Soleus', 'Lower Leg', 0.435, 0.785, 0.115, 0.110],

            // ---- Left side view (mirrored x) ----
            ['left_side', 'tight', 'Deep Neck Flexors / SCM', 'Head / Neck', 0.485, 0.115, 0.145, 0.145],
            ['left_side', 'tight', 'Pectoralis Major', 'Chest', 0.415, 0.290, 0.125, 0.130],
            ['left_side', 'tight', 'Thoracic Erector Spinae', 'Thoracic', 0.550, 0.350, 0.100, 0.190],
            ['left_side', 'weak', 'Deep Abdominals', 'Abdominal', 0.415, 0.430, 0.115, 0.115],
            ['left_side', 'weak', 'Gluteus Maximus', 'Gluteus', 0.550, 0.560, 0.130, 0.120],
            ['left_side', 'tight', 'Hip Flexors / Quadriceps', 'Upper Leg', 0.565, 0.680, 0.140, 0.150],
            ['left_side', 'tight', 'Gastrocnemius / Soleus', 'Lower Leg', 0.565, 0.785, 0.115, 0.110],
        ];

        // Rebuild the generic (severity-based) sets from the reference
        // geometry on every run so re-seeding restores the original
        // alignment even if the rows were edited or drifted.
        PostureMarker::whereNull('condition')->delete();

        // Male and female images are tuned independently, and each result
        // severity (normal / moderate / severe) has its own marker set. Every
        // set starts from the same reference pattern and can be edited later.
        foreach (['normal', 'moderate', 'severe'] as $severity) {
            foreach (['male', 'female'] as $gender) {
                foreach ($markers as $index => [$view, $type, $muscle, $label, $x, $y, $w, $h]) {
                    PostureMarker::create([
                        'view' => $view,
                        'gender' => $gender,
                        'severity' => $severity,
                        'type' => $type,
                        'label' => $label,
                        'muscle' => $muscle,
                        'x' => $x,
                        'y' => $y,
                        'width' => $w,
                        'height' => $h,
                        'order_index' => $index,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
