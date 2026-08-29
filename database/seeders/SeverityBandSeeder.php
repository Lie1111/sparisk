<?php

namespace Database\Seeders;

use App\Models\PostureSeverityBand;
use Illuminate\Database\Seeder;

/**
 * Seeds the SATA Fixed Severity Bands from config/sparisk.php.
 *
 * Deviation = ABS(Clinical Angle - Age Reference) is mapped to a band.
 * Bands are evaluated top-down; `max` is exclusive.
 */
class SeverityBandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('sparisk.severity_bands') as $band) {
            PostureSeverityBand::firstOrCreate(
                ['level' => $band['level']],
                [
                    'min' => $band['min'],
                    'max' => $band['max'],
                    'label' => $band['label'],
                    'color' => $band['color'],
                ]
            );
        }
    }
}
