<?php

namespace Database\Seeders;

use App\Models\PostureMarker;
use Illuminate\Database\Seeder;

/**
 * Seeds the SATA posture-condition marker patterns (Figures 1–12).
 *
 * Each condition (Forward Head, Rounded Shoulder, Kyphotic, Lordotic,
 * Kyphosis–Lordosis, Flat-Back, Flexed-Knee, Genu Recurvatum, Normal Neutral)
 * defines which muscles are TIGHT (red) and WEAK (green) per view, using the
 * normalized positions on the character images. Admins can then fine-tune each
 * condition in the Posture Markers page.
 *
 * Muscles are grouped onto the nearest body zone of the character image.
 */
class SataPostureMarkerSeeder extends Seeder
{
    /** Zone key => [label, muscle, x, y, width, height] */
    private const POSITIONS = [
        'front' => [
            'neck'      => ['Neck / SCM', 'Upper Trapezius / SCM', 0.50, 0.135, 0.16, 0.13],
            'chest_l'   => ['Chest L', 'Pectoralis Major / Minor (L)', 0.37, 0.295, 0.18, 0.15],
            'chest_r'   => ['Chest R', 'Pectoralis Major / Minor (R)', 0.63, 0.295, 0.18, 0.15],
            'biceps_l'  => ['Biceps L', 'Biceps Brachii (L)', 0.175, 0.38, 0.11, 0.24],
            'biceps_r'  => ['Biceps R', 'Biceps Brachii (R)', 0.825, 0.38, 0.11, 0.24],
            'core'      => ['Core', 'Abdominals / Deep Core', 0.50, 0.50, 0.20, 0.20],
            'hip_l'     => ['Hip L', 'Gluteus Medius (L)', 0.34, 0.60, 0.16, 0.16],
            'hip_r'     => ['Hip R', 'Gluteus Medius (R)', 0.66, 0.60, 0.16, 0.16],
            'thigh_l'   => ['Thigh L', 'Hip Flexors / TFL / Quadriceps (L)', 0.39, 0.695, 0.18, 0.19],
            'thigh_r'   => ['Thigh R', 'Hip Flexors / TFL / Quadriceps (R)', 0.61, 0.695, 0.18, 0.19],
            'shin_l'    => ['Shin L', 'Tibialis Anterior (L)', 0.39, 0.80, 0.14, 0.14],
            'shin_r'    => ['Shin R', 'Tibialis Anterior (R)', 0.61, 0.80, 0.14, 0.14],
            'calf_l'    => ['Calf L', 'Gastrocnemius (L)', 0.39, 0.875, 0.16, 0.17],
            'calf_r'    => ['Calf R', 'Gastrocnemius (R)', 0.61, 0.875, 0.16, 0.17],
        ],
        'back' => [
            'upper_trap'  => ['Upper Trap', 'Upper Trapezius / Levator Scapulae', 0.50, 0.17, 0.24, 0.14],
            'rhomboids'   => ['Rhomboids', 'Rhomboids / Middle Trapezius', 0.50, 0.28, 0.40, 0.16],
            'lat_l'       => ['Lat L', 'Latissimus Dorsi / Serratus Anterior (L)', 0.31, 0.39, 0.14, 0.22],
            'lat_r'       => ['Lat R', 'Latissimus Dorsi / Serratus Anterior (R)', 0.69, 0.39, 0.14, 0.22],
            'erector'     => ['Erector Spinae', 'Erector Spinae / Multifidus / QL', 0.50, 0.54, 0.12, 0.24],
            'glute_l'     => ['Glute L', 'Gluteus Maximus / Medius (L)', 0.40, 0.60, 0.16, 0.14],
            'glute_r'     => ['Glute R', 'Gluteus Maximus / Medius (R)', 0.60, 0.60, 0.16, 0.14],
            'hamstring_l' => ['Hamstring L', 'Hamstrings (L)', 0.39, 0.695, 0.18, 0.19],
            'hamstring_r' => ['Hamstring R', 'Hamstrings (R)', 0.61, 0.695, 0.18, 0.19],
            'calf_l'      => ['Calf L', 'Gastrocnemius / Soleus (L)', 0.39, 0.875, 0.16, 0.17],
            'calf_r'      => ['Calf R', 'Gastrocnemius / Soleus (R)', 0.61, 0.875, 0.16, 0.17],
        ],
        'right_side' => [
            'head'       => ['Head / Neck', 'Deep Neck Flexors / SCM', 0.57, 0.135, 0.18, 0.13],
            'chest'      => ['Chest', 'Pectoralis Major / Minor', 0.44, 0.31, 0.16, 0.14],
            'thoracic'   => ['Thoracic', 'Thoracic Erector Spinae / Lower Trapezius', 0.66, 0.49, 0.12, 0.30],
            'abdominal'  => ['Abdominal', 'Deep Abdominals / Transverse Abdominis', 0.44, 0.56, 0.12, 0.24],
            'gluteus'    => ['Gluteus', 'Gluteus Maximus', 0.72, 0.615, 0.16, 0.15],
            'upper_leg'  => ['Upper Leg', 'Hip Flexors / Quadriceps / Hamstrings', 0.41, 0.67, 0.18, 0.18],
            'lower_leg'  => ['Lower Leg', 'Gastrocnemius / Soleus / Tibialis', 0.42, 0.855, 0.16, 0.19],
        ],
    ];

    /**
     * condition slug => [label, view => ['tight' => [zone keys], 'weak' => [zone keys]]]
     */
    private const PATTERNS = [
        'normal_neutral' => [
            'label' => 'Normal / Neutral Posture',
            'front' => ['tight' => [], 'weak' => []],
            'back' => ['tight' => [], 'weak' => []],
            'right_side' => ['tight' => [], 'weak' => []],
        ],
        'forward_head' => [
            'label' => 'Forward Head Posture',
            'front' => ['tight' => ['neck', 'chest_l', 'chest_r'], 'weak' => ['neck', 'core']],
            'back' => ['tight' => ['upper_trap', 'rhomboids'], 'weak' => ['upper_trap', 'lat_l', 'lat_r']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic'], 'weak' => ['head', 'thoracic', 'abdominal', 'gluteus']],
        ],
        'rounded_shoulder' => [
            'label' => 'Rounded Shoulder Posture',
            'front' => ['tight' => ['neck', 'chest_l', 'chest_r', 'biceps_l', 'biceps_r'], 'weak' => ['neck', 'core']],
            'back' => ['tight' => ['upper_trap', 'rhomboids'], 'weak' => ['upper_trap', 'lat_l', 'lat_r', 'erector']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic'], 'weak' => ['head', 'thoracic', 'abdominal', 'gluteus']],
        ],
        'kyphosis' => [
            'label' => 'Kyphosis Posture (Increased Thoracic Kyphosis)',
            'front' => ['tight' => ['neck', 'chest_l', 'chest_r'], 'weak' => ['core', 'chest_l', 'chest_r']],
            'back' => ['tight' => ['upper_trap', 'rhomboids'], 'weak' => ['upper_trap', 'lat_l', 'lat_r', 'erector']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic'], 'weak' => ['head', 'thoracic', 'abdominal', 'gluteus']],
        ],
        'lordosis' => [
            'label' => 'Lordosis Posture (Increased Lumbar Lordosis)',
            'front' => ['tight' => ['thigh_l', 'thigh_r', 'calf_l', 'calf_r'], 'weak' => ['core', 'hip_l', 'hip_r']],
            'back' => ['tight' => ['erector', 'hamstring_l', 'hamstring_r', 'calf_l', 'calf_r'], 'weak' => ['erector', 'glute_l', 'glute_r']],
            'right_side' => ['tight' => ['thoracic', 'upper_leg', 'lower_leg'], 'weak' => ['abdominal', 'gluteus']],
        ],
        'kyphosis_lordosis' => [
            'label' => 'Kyphosis - Lordosis Posture',
            'front' => ['tight' => ['neck', 'chest_l', 'chest_r', 'thigh_l', 'thigh_r'], 'weak' => ['neck', 'core', 'hip_l', 'hip_r']],
            'back' => ['tight' => ['upper_trap', 'rhomboids', 'erector', 'hamstring_l', 'hamstring_r'], 'weak' => ['upper_trap', 'lat_l', 'lat_r', 'erector', 'glute_l', 'glute_r']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic', 'upper_leg'], 'weak' => ['head', 'abdominal', 'gluteus']],
        ],
        'flatback' => [
            'label' => 'Flatback Posture',
            'front' => ['tight' => ['neck', 'chest_l', 'chest_r', 'thigh_l', 'thigh_r'], 'weak' => ['neck', 'core', 'hip_l', 'hip_r']],
            'back' => ['tight' => ['upper_trap', 'erector', 'hamstring_l', 'hamstring_r'], 'weak' => ['upper_trap', 'erector', 'glute_l', 'glute_r', 'calf_l', 'calf_r']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic', 'upper_leg'], 'weak' => ['head', 'abdominal', 'gluteus']],
        ],
        'flexed_knee' => [
            'label' => 'Flexed-Knee Posture',
            'front' => ['tight' => ['thigh_l', 'thigh_r', 'calf_l', 'calf_r'], 'weak' => ['core', 'thigh_l', 'thigh_r', 'shin_l', 'shin_r']],
            'back' => ['tight' => ['upper_trap', 'erector', 'hamstring_l', 'hamstring_r', 'calf_l', 'calf_r'], 'weak' => ['upper_trap', 'glute_l', 'glute_r']],
            'right_side' => ['tight' => ['upper_leg', 'lower_leg'], 'weak' => ['abdominal', 'gluteus', 'upper_leg']],
        ],
        'genu_recurvatum' => [
            'label' => 'Genu Recurvatum Posture',
            'front' => ['tight' => ['thigh_l', 'thigh_r', 'shin_l', 'shin_r', 'calf_l', 'calf_r'], 'weak' => ['core', 'thigh_l', 'thigh_r']],
            'back' => ['tight' => ['erector', 'glute_l', 'glute_r', 'hamstring_l', 'hamstring_r', 'calf_l', 'calf_r'], 'weak' => ['erector', 'glute_l', 'glute_r', 'hamstring_l', 'hamstring_r']],
            'right_side' => ['tight' => ['upper_leg', 'lower_leg'], 'weak' => ['thoracic', 'abdominal', 'gluteus']],
        ],
    ];

    public function run(): void
    {
        foreach (self::PATTERNS as $slug => $pattern) {
            foreach (['male', 'female'] as $gender) {
                foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
                    $viewPattern = $this->viewPattern($pattern, $view);
                    if ($viewPattern === null) {
                        continue;
                    }

                    $order = 0;
                    foreach (['tight' => 'tight', 'weak' => 'weak'] as $type => $key) {
                        foreach ($viewPattern[$key] ?? [] as $zone) {
                            $pos = $this->position($view, $zone);
                            if ($pos === null) {
                                continue;
                            }
                            [$label, $muscle, $x, $y, $w, $h] = $pos;

                            PostureMarker::firstOrCreate(
                                [
                                    'view' => $view,
                                    'gender' => $gender,
                                    'condition' => $slug,
                                    'muscle' => $muscle,
                                ],
                                [
                                    'severity' => 'normal',
                                    'type' => $type,
                                    'label' => $label,
                                    'x' => $x,
                                    'y' => $y,
                                    'width' => $w,
                                    'height' => $h,
                                    'order_index' => $order++,
                                    'is_active' => true,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    private function viewPattern(array $pattern, string $view): ?array
    {
        if ($view === 'left_side') {
            return $pattern['right_side'] ?? null;
        }

        return $pattern[$view] ?? null;
    }

    /** Resolve a zone position, mirroring x for the left side view. */
    private function position(string $view, string $zone): ?array
    {
        $baseView = $view === 'left_side' ? 'right_side' : $view;
        $pos = self::POSITIONS[$baseView][$zone] ?? null;
        if ($pos === null) {
            return null;
        }

        if ($view === 'left_side') {
            $pos[2] = 1 - $pos[2];
        }

        return $pos;
    }
}
