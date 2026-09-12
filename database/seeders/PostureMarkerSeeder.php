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
        $markers = [
            // ---- Front view ----
            ['front', 'tight', 'Upper Trapezius / SCM', 'Neck / SCM', 0.50, 0.135, 0.16, 0.13],
            ['front', 'tight', 'Pectoralis Major (Left)', 'Chest L', 0.37, 0.295, 0.18, 0.15],
            ['front', 'tight', 'Pectoralis Major (Right)', 'Chest R', 0.63, 0.295, 0.18, 0.15],
            ['front', 'tight', 'Biceps Brachii (Left)', 'Biceps L', 0.175, 0.38, 0.11, 0.24],
            ['front', 'tight', 'Biceps Brachii (Right)', 'Biceps R', 0.825, 0.38, 0.11, 0.24],
            ['front', 'weak', 'Core Muscles (Deep Abdominals)', 'Core', 0.50, 0.50, 0.20, 0.20],
            ['front', 'tight', 'Rectus Femoris / Hip Flexors (Left)', 'Thigh L', 0.39, 0.695, 0.18, 0.19],
            ['front', 'tight', 'Rectus Femoris / Hip Flexors (Right)', 'Thigh R', 0.61, 0.695, 0.18, 0.19],
            ['front', 'tight', 'Gastrocnemius (Left)', 'Calf L', 0.39, 0.875, 0.16, 0.17],
            ['front', 'tight', 'Gastrocnemius (Right)', 'Calf R', 0.61, 0.875, 0.16, 0.17],

            // ---- Back view ----
            ['back', 'tight', 'Upper Trapezius / Levator Scapulae', 'Upper Trap', 0.50, 0.17, 0.24, 0.14],
            ['back', 'tight', 'Rhomboids', 'Rhomboids', 0.50, 0.28, 0.40, 0.16],
            ['back', 'weak', 'Latissimus Dorsi (Left)', 'Lat L', 0.31, 0.39, 0.14, 0.22],
            ['back', 'weak', 'Latissimus Dorsi (Right)', 'Lat R', 0.69, 0.39, 0.14, 0.22],
            ['back', 'tight', 'Erector Spinae', 'Erector Spinae', 0.50, 0.54, 0.12, 0.24],
            ['back', 'tight', 'Hamstrings (Left)', 'Hamstring L', 0.39, 0.695, 0.18, 0.19],
            ['back', 'tight', 'Hamstrings (Right)', 'Hamstring R', 0.61, 0.695, 0.18, 0.19],
            ['back', 'tight', 'Gastrocnemius (Left)', 'Calf L', 0.39, 0.875, 0.16, 0.17],
            ['back', 'tight', 'Gastrocnemius (Right)', 'Calf R', 0.61, 0.875, 0.16, 0.17],

            // ---- Right side view ----
            ['right_side', 'tight', 'Upper Trapezius', 'Head / Neck', 0.57, 0.135, 0.18, 0.13],
            ['right_side', 'tight', 'Pectoralis Major', 'Chest', 0.44, 0.31, 0.16, 0.14],
            ['right_side', 'tight', 'Thoracic Erector Spinae', 'Thoracic', 0.66, 0.49, 0.12, 0.30],
            ['right_side', 'weak', 'Deep Abdominals', 'Abdominal', 0.44, 0.56, 0.12, 0.24],
            ['right_side', 'weak', 'Gluteus Maximus', 'Gluteus', 0.72, 0.615, 0.16, 0.15],
            ['right_side', 'tight', 'Rectus Femoris', 'Upper Leg', 0.41, 0.67, 0.18, 0.18],
            ['right_side', 'tight', 'Gastrocnemius', 'Lower Leg', 0.42, 0.855, 0.16, 0.19],

            // ---- Left side view (mirrored x) ----
            ['left_side', 'tight', 'Upper Trapezius', 'Head / Neck', 0.43, 0.135, 0.18, 0.13],
            ['left_side', 'tight', 'Pectoralis Major', 'Chest', 0.56, 0.31, 0.16, 0.14],
            ['left_side', 'tight', 'Thoracic Erector Spinae', 'Thoracic', 0.34, 0.49, 0.12, 0.30],
            ['left_side', 'weak', 'Deep Abdominals', 'Abdominal', 0.56, 0.56, 0.12, 0.24],
            ['left_side', 'weak', 'Gluteus Maximus', 'Gluteus', 0.28, 0.615, 0.16, 0.15],
            ['left_side', 'tight', 'Rectus Femoris', 'Upper Leg', 0.59, 0.67, 0.18, 0.18],
            ['left_side', 'tight', 'Gastrocnemius', 'Lower Leg', 0.58, 0.855, 0.16, 0.19],
        ];

        // Male and female images are tuned independently, and each result
        // severity (normal / moderate / severe) has its own marker set. Every
        // set starts from the same reference pattern and can be edited later.
        foreach (['normal', 'moderate', 'severe'] as $severity) {
            foreach (['male', 'female'] as $gender) {
                foreach ($markers as $index => [$view, $type, $muscle, $label, $x, $y, $w, $h]) {
                    PostureMarker::firstOrCreate(
                        [
                            'view' => $view,
                            'gender' => $gender,
                            'severity' => $severity,
                            'muscle' => $muscle,
                        ],
                        [
                            'type' => $type,
                            'label' => $label,
                            'x' => $x,
                            'y' => $y,
                            'width' => $w,
                            'height' => $h,
                            'order_index' => $index,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
