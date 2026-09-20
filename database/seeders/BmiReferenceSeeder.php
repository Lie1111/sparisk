<?php

namespace Database\Seeders;

use App\Models\BmiReference;
use Illuminate\Database\Seeder;

/**
 * Seeds the SATA BMI reference bands (age + gender) from config/sparisk.php.
 *
 * Children (6–18) are seeded per exact age + gender from the WHO 2007
 * BMI-for-age z-scores; adults (19+) share the SATA adult cut-offs.
 * Rows are written with `is_verified = false` until SATA confirms the values
 * against the approved clinical dataset; once verified the table can be edited
 * directly without touching the calculation engine.
 */
class BmiReferenceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (BmiReference::seedRows() as $row) {
            BmiReference::firstOrCreate(
                [
                    'age_group' => $row['age_group'],
                    'age_years' => $row['age_years'],
                    'gender' => $row['gender'],
                    'min_bmi' => $row['min_bmi'],
                ],
                [
                    'max_bmi' => $row['max_bmi'],
                    'category' => $row['category'],
                    'source' => $row['source'],
                    'is_verified' => $row['is_verified'],
                ]
            );
        }
    }
}
