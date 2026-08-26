<?php

namespace Database\Seeders;

use App\Models\PostureSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds the SATA Age-Based Posture Reference v1.1.
 *
 * Values are the fixed SATA reference angles (degrees) per camera view and
 * age group. Deviation = ABS(Clinical Angle - Age Reference) is compared
 * against the locked SATA severity bands (see config/sparisk.php).
 */
class PostureSettingSeeder extends Seeder
{
    private const AGE_GROUPS = ['6-8', '9-12', '13-18', '19-49', '50+'];

    public function run(): void
    {
        // [view, section, label, [6-8, 9-12, 13-18, 19-49, 50+], interpretation]
        $front = [
            ['A1',  'Body Alignment',          [0, 0, 0, 0, 0], 'Vertical neutral'],
            ['A2',  'Head Tilt',               [0, 0, 0, 0, 0], 'Horizontal neutral'],
            ['A3',  'Shoulder Alignment',      [0, 0, 0, 0, 0], 'Level shoulders'],
            ['A4',  'Axillae Alignment',       [0, 0, 0, 0, 0], 'Symmetrical'],
            ['A5',  'Ribcage Tilt',            [0, 0, 0, 0, 0], 'Level rib cage'],
            ['A6',  'Trunk Alignment',         [0, 0, 0, 0, 0], 'Midline'],
            ['A7',  'Pelvic Tilt / Obliquity', [0, 0, 0, 0, 0], 'Level pelvis'],
            ['A8',  'Right Knee Angle',        [0, 0, 0, 0, 0], 'Neutral frontal alignment'],
            ['A9',  'Left Knee Angle',         [0, 0, 0, 0, 0], 'Neutral frontal alignment'],
            ['A10', 'Right Foot Rotation',     [5, 7, 7, 7, 8], 'External foot progression'],
            ['A11', 'Left Foot Rotation',      [5, 7, 7, 7, 8], 'External foot progression'],
        ];

        $back = [
            ['B1', 'Body Alignment',          [0, 0, 0, 0, 0], 'Vertical neutral'],
            ['B2', 'Head Tilt',               [0, 0, 0, 0, 0], 'Head level'],
            ['B3', 'Shoulder Alignment',      [0, 0, 0, 0, 0], 'Shoulders level'],
            ['B4', 'Axillae Alignment',       [0, 0, 0, 0, 0], 'Symmetrical'],
            ['B5', 'Trunk Alignment',         [0, 0, 0, 0, 0], 'Midline'],
            ['B6', 'Pelvic Tilt / Obliquity', [0, 0, 0, 0, 0], 'Pelvis level'],
            ['B7', 'Knee Alignment',          [0, 0, 0, 0, 0], 'Neutral'],
            ['B8', 'Feet Alignment',          [0, 0, 0, 0, 0], 'Neutral rear-foot alignment'],
        ];

        $side = [
            ['C1',              'Body Alignment',      [0, 0, 0, 0, 0], '+/- deviation = anterior/posterior body lean'],
            ['side_cva',        'CVA / Head Reference', [55, 53, 50, 50, 48], 'Downward CVA = increased Forward Head Posture'],
            ['C2',              'Head Shift',          [0, 0, 0, 0, 0], 'Anterior displacement up = forward head shift'],
            ['C3',              'Shoulder Angle',      [0, 0, 0, 0, 0], 'Anterior deviation up = shoulder protraction/rounded shoulder'],
            ['side_kyphosis',   'Thoracic Kyphosis',   [40, 44, 45, 40, 46], 'Up = increased thoracic kyphosis; Down = reduced thoracic curvature'],
            ['C4',              'Pelvic Tilt (APT)',   [7, 8, 9, 10, 10], 'Up = increased anterior pelvic tilt; Down = posterior tilt tendency'],
            ['side_lordosis',   'Lumbar Lordosis',     [54, 55, 55, 45, 40], 'Up = increased lordosis; Down = reduced/flattened lordosis'],
            ['C5',              'Knee',                [0, 0, 0, 0, 0], 'Flexion = flexed-knee; extension beyond neutral = hyperextension'],
            ['C6',              'Tibia Alignment',     [0, 0, 0, 0, 0], 'Deviation = altered anterior/posterior lower-leg alignment'],
            ['C7',              'Foot/Ankle Angle',    [0, 0, 0, 0, 0], 'Deviation = altered sagittal foot/ankle alignment'],
        ];

        // Right and left sagittal reference values are identical.
        $rightSide = $this->mapSideSections($side, 'C');
        $leftSide = $this->mapSideSections($side, 'D');

        $rows = [
            ['front', $front],
            ['back', $back],
            ['right_side', $rightSide],
            ['left_side', $leftSide],
        ];

        foreach ($rows as [$view, $params]) {
            foreach ($params as [$section, $label, $references, $interpretation]) {
                foreach (self::AGE_GROUPS as $i => $ageGroup) {
                    PostureSetting::updateOrCreate(
                        ['view' => $view, 'section' => $section, 'age_group' => $ageGroup],
                        [
                            'label' => $label,
                            'reference_value' => $references[$i],
                            'interpretation' => $interpretation,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Prefix measured side sections with the given view letter (C/D) while
     * leaving clinical-only keys (side_*) untouched.
     */
    private function mapSideSections(array $rows, string $letter): array
    {
        return array_map(function ($row) use ($letter) {
            $section = str_starts_with($row[0], 'side_')
                ? $row[0]
                : $letter . substr($row[0], 1);

            return [$section, $row[1], $row[2], $row[3]];
        }, $rows);
    }
}
