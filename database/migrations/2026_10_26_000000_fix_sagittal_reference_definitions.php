<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Correct the SATA references whose definition did not match the angle the app
 * actually measures, and version the measurement formula so a stored
 * assessment can be recomputed exactly once.
 *
 *   C3/D3 Shoulder — the app measures the acromion over the hip (0° = stacked).
 *                    The published 64.5°/69.2°/72.4° photogrammetry shoulder
 *                    angle uses a different axis, so it is replaced by the
 *                    definitional neutral 0° ±5°.
 *   C4/D4 Pelvic   — the app measures the hip over the ankle (0° = stacked).
 *                    The published 12.6°/14.7°/−12.26° ASIS–PSIS tilt values
 *                    use a different axis, so they are replaced by 0° ±5°.
 *   B8    Feet     — the old ankle→heel angle measured foot anatomy (~47°).
 *                    B8 now uses the A10/A11 foot-progression measurement with
 *                    the same external-progression reference (5°/7°/7°/7°/8°).
 */
return new class extends Migration
{
    private const AGE_GROUPS = ['6-8', '9-12', '13-18', '19-49', '50+'];

    private const SAGITTAL_SECTIONS = [
        ['view' => 'right_side', 'section' => 'C3', 'label' => 'Shoulder Alignment'],
        ['view' => 'left_side', 'section' => 'D3', 'label' => 'Shoulder Alignment'],
        ['view' => 'right_side', 'section' => 'C4', 'label' => 'Pelvic Alignment'],
        ['view' => 'left_side', 'section' => 'D4', 'label' => 'Pelvic Alignment'],
    ];

    public function up(): void
    {
        if (! Schema::hasColumn('posture_assessments', 'measurement_version')) {
            Schema::table('posture_assessments', function (Blueprint $table) {
                $table->unsignedTinyInteger('measurement_version')
                    ->default(1)
                    ->after('time_mark');
            });
        }

        $now = now();

        // Any sex-specific C4/D4 rows come from the old ASIS–PSIS tilt table.
        DB::table('posture_settings')
            ->whereIn('section', ['C4', 'D4'])
            ->whereNotNull('sex')
            ->delete();

        foreach (self::SAGITTAL_SECTIONS as $row) {
            foreach (self::AGE_GROUPS as $ageGroup) {
                DB::table('posture_settings')->updateOrInsert(
                    [
                        'view' => $row['view'],
                        'section' => $row['section'],
                        'age_group' => $ageGroup,
                        'sex' => null,
                    ],
                    [
                        'label' => $row['label'],
                        'reference_value' => 0.0,
                        'normal_min' => -5.0,
                        'normal_max' => 5.0,
                        'reference_source' => 'SATA validated — definitional neutral: segment stacked over the plumb line (0°)',
                        'reference_level' => 'sata_validated',
                        'sex_if_required' => false,
                        'interpretation' => 'Compared against the SATA-validated reference.',
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }

        // B8: same external foot-progression reference as A10/A11.
        $footReferences = ['6-8' => 5.0, '9-12' => 7.0, '13-18' => 7.0, '19-49' => 7.0, '50+' => 8.0];
        $footTolerance = (float) config('sparisk.severity_thresholds.foot.mild', 10);

        foreach (self::AGE_GROUPS as $ageGroup) {
            $value = $footReferences[$ageGroup];

            DB::table('posture_settings')->updateOrInsert(
                [
                    'view' => 'back',
                    'section' => 'B8',
                    'age_group' => $ageGroup,
                    'sex' => null,
                ],
                [
                    'label' => 'Feet Alignment',
                    'reference_value' => $value,
                    'normal_min' => $value - $footTolerance,
                    'normal_max' => $value + $footTolerance,
                    'reference_source' => 'SATA validated — external foot progression reference (A10/A11 axis)',
                    'reference_level' => 'sata_validated',
                    'sex_if_required' => false,
                    'interpretation' => 'Compared against the SATA-validated reference.',
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('posture_assessments', 'measurement_version')) {
            Schema::table('posture_assessments', function (Blueprint $table) {
                $table->dropColumn('measurement_version');
            });
        }
    }
};
