<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Plain-English labels for the sagittal findings.
 *
 * "Upper-Back Posture Indicator", "Lower-Back Posture Indicator" and "Tibia
 * Alignment" read as clinical jargon and did not tell the reader where the
 * issue is. Renamed to body-location-first wording so the PDF findings point
 * at the body part:
 *
 *   Upper-Back Posture Indicator  -> Upper Back Curve
 *   Lower-Back Posture Indicator  -> Lower Back Curve
 *   Tibia Alignment               -> Lower Leg Alignment
 *   Tibia                         -> Lower Leg
 *   CVA / Head Reference          -> Head Position
 */
return new class extends Migration
{
    private const MAP = [
        'Upper-Back Posture Indicator' => 'Upper Back Curve',
        'Lower-Back Posture Indicator' => 'Lower Back Curve',
        'Tibia Alignment'              => 'Lower Leg Alignment',
        'Tibia'                        => 'Lower Leg',
        'CVA / Head Reference'         => 'Head Position',
    ];

    public function up(): void
    {
        foreach (self::MAP as $from => $to) {
            DB::table('posture_settings')
                ->where('label', $from)
                ->update(['label' => $to]);

            DB::table('posture_measurements')
                ->where('label', $from)
                ->update(['label' => $to]);
        }
    }

    public function down(): void
    {
        foreach (array_reverse(self::MAP, true) as $to => $from) {
            DB::table('posture_settings')
                ->where('label', $to)
                ->update(['label' => $from]);

            DB::table('posture_measurements')
                ->where('label', $to)
                ->update(['label' => $from]);
        }
    }
};
