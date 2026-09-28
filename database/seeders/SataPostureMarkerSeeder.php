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
    /**
     * Zone key => [label, muscle, x, y, width, height]
     *
     * x / y are the NORMALIZED CENTRE of the shape (0..1 of the character
     * image). Each value was measured against the transparent silhouette of
     * the character PNGs so the shapes sit *on* the body instead of spilling
     * past the outline.
     */
    private const POSITIONS = [
        'front' => [
            'neck'      => ['Neck / SCM', 'Upper Trapezius / SCM', 0.495, 0.155, 0.160, 0.110],
            'chest_l'   => ['Chest L', 'Pectoralis Major / Minor (L)', 0.415, 0.285, 0.150, 0.120],
            'chest_r'   => ['Chest R', 'Pectoralis Major / Minor (R)', 0.585, 0.285, 0.150, 0.120],
            'biceps_l'  => ['Biceps L', 'Biceps Brachii (L)', 0.225, 0.375, 0.095, 0.170],
            'biceps_r'  => ['Biceps R', 'Biceps Brachii (R)', 0.775, 0.375, 0.095, 0.170],
            'core'      => ['Core', 'Abdominals / Deep Core', 0.500, 0.430, 0.200, 0.120],
            'hip_l'     => ['Hip L', 'Gluteus Medius (L)', 0.390, 0.545, 0.130, 0.100],
            'hip_r'     => ['Hip R', 'Gluteus Medius (R)', 0.610, 0.545, 0.130, 0.100],
            'thigh_l'   => ['Thigh L', 'Hip Flexors / TFL / Quadriceps (L)', 0.385, 0.625, 0.140, 0.145],
            'thigh_r'   => ['Thigh R', 'Hip Flexors / TFL / Quadriceps (R)', 0.615, 0.625, 0.140, 0.145],
            'shin_l'    => ['Shin L', 'Tibialis Anterior (L)', 0.382, 0.720, 0.085, 0.075],
            'shin_r'    => ['Shin R', 'Tibialis Anterior (R)', 0.618, 0.720, 0.085, 0.075],
            'calf_l'    => ['Calf L', 'Gastrocnemius (L)', 0.382, 0.790, 0.085, 0.080],
            'calf_r'    => ['Calf R', 'Gastrocnemius (R)', 0.618, 0.790, 0.085, 0.080],
        ],
        'back' => [
            'upper_trap'  => ['Upper Trap', 'Upper Trapezius / Levator Scapulae', 0.500, 0.215, 0.260, 0.090],
            'rhomboids'   => ['Rhomboids', 'Rhomboids / Middle Trapezius', 0.500, 0.295, 0.190, 0.090],
            'lat_l'       => ['Lat L', 'Latissimus Dorsi / Serratus Anterior (L)', 0.395, 0.355, 0.100, 0.165],
            'lat_r'       => ['Lat R', 'Latissimus Dorsi / Serratus Anterior (R)', 0.605, 0.355, 0.100, 0.165],
            'erector'     => ['Erector Spinae', 'Erector Spinae / Multifidus / QL', 0.500, 0.395, 0.090, 0.125],
            'glute_l'     => ['Glute L', 'Gluteus Maximus / Medius (L)', 0.390, 0.535, 0.140, 0.110],
            'glute_r'     => ['Glute R', 'Gluteus Maximus / Medius (R)', 0.610, 0.535, 0.140, 0.110],
            'hamstring_l' => ['Hamstring L', 'Hamstrings (L)', 0.385, 0.635, 0.125, 0.140],
            'hamstring_r' => ['Hamstring R', 'Hamstrings (R)', 0.615, 0.635, 0.125, 0.140],
            'calf_l'      => ['Calf L', 'Gastrocnemius / Soleus (L)', 0.365, 0.770, 0.095, 0.105],
            'calf_r'      => ['Calf R', 'Gastrocnemius / Soleus (R)', 0.635, 0.770, 0.095, 0.105],
        ],
        'right_side' => [
            'head'       => ['Head / Neck', 'Deep Neck Flexors / SCM', 0.515, 0.115, 0.145, 0.145],
            'chest'      => ['Chest', 'Pectoralis Major / Minor', 0.585, 0.290, 0.125, 0.130],
            'thoracic'   => ['Thoracic', 'Thoracic Erector Spinae / Lower Trapezius', 0.450, 0.350, 0.100, 0.190],
            'abdominal'  => ['Abdominal', 'Deep Abdominals / Transverse Abdominis', 0.585, 0.430, 0.115, 0.115],
            'gluteus'    => ['Gluteus', 'Gluteus Maximus', 0.450, 0.560, 0.130, 0.120],
            'upper_leg'  => ['Upper Leg', 'Hip Flexors / Quadriceps / Hamstrings', 0.435, 0.680, 0.140, 0.150],
            'lower_leg'  => ['Lower Leg', 'Gastrocnemius / Soleus / Tibialis', 0.435, 0.785, 0.115, 0.110],
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
        'sway_back' => [
            'label' => 'Sway-Back Posture',
            'front' => ['tight' => ['thigh_l', 'thigh_r', 'calf_l', 'calf_r'], 'weak' => ['core', 'hip_l', 'hip_r']],
            'back' => ['tight' => ['erector', 'hamstring_l', 'hamstring_r', 'calf_l', 'calf_r'], 'weak' => ['upper_trap', 'glute_l', 'glute_r']],
            'right_side' => ['tight' => ['upper_leg', 'lower_leg'], 'weak' => ['thoracic', 'abdominal', 'gluteus']],
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
        'frontal_asymmetry' => [
            'label' => 'Frontal Postural Asymmetry',
            'front' => ['tight' => ['neck', 'chest_l', 'biceps_l', 'hip_l'], 'weak' => ['core', 'hip_r', 'thigh_r']],
            'back' => ['tight' => ['upper_trap', 'erector', 'glute_r'], 'weak' => ['lat_l', 'glute_l', 'hamstring_l']],
            'right_side' => ['tight' => ['head', 'chest', 'thoracic'], 'weak' => ['thoracic', 'abdominal', 'gluteus']],
        ],
        'pronated_foot' => [
            'label' => 'Pronated Foot Posture',
            'front' => ['tight' => ['calf_l', 'calf_r'], 'weak' => ['shin_l', 'shin_r']],
            'back' => ['tight' => ['calf_l', 'calf_r'], 'weak' => ['glute_l', 'glute_r']],
            'right_side' => ['tight' => ['lower_leg'], 'weak' => ['gluteus']],
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

                    // A zone can only render one way: if a pattern lists the
                    // same zone as tight and weak it would stack two shapes on
                    // identical coordinates, so tight wins.
                    $tight = $viewPattern['tight'] ?? [];
                    $weak = array_values(array_diff($viewPattern['weak'] ?? [], $tight));

                    $order = 0;
                    foreach (['tight' => $tight, 'weak' => $weak] as $type => $zones) {
                        foreach ($zones as $zone) {
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
