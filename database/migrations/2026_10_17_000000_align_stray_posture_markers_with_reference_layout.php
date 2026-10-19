<?php

use App\Models\PostureMarker;
use Illuminate\Database\Migrations\Migration;

/**
 * Re-aligns the markers that were left behind by an older reference layout.
 *
 * Every set — normal / moderate / severe, male and female, including the
 * condition (SATA figure) sets — draws a muscle in the same place, so a
 * highlight sits on the same spot whatever set the app picks. Three rows in the
 * generic `normal` sets were still on their pre-correction coordinates, which
 * is what made the left/right calves (male front) and the abdominal (female
 * left side) look off the muscle.
 *
 * The seeder (database/seeders/PostureMarkerSeeder.php) already carries the
 * reference geometry, so this only has to drag the stale rows onto it. Each
 * update is scoped to the exact old geometry, which makes the migration
 * idempotent and leaves deliberately edited markers alone.
 */
return new class extends Migration
{
    /**
     * [view, gender, label, stale geometry, reference geometry]
     *
     * Coordinates are stored at the column's 4-decimal precision, so the stale
     * values below are the exact stored numbers (0.3167, not 0.317).
     */
    private const ROWS = [
        ['front', 'male', 'Calf L', ['0.3167', '0.7828', '0.0850', '0.0800'], [0.382, 0.790, 0.085, 0.080]],
        ['front', 'male', 'Calf R', ['0.6667', '0.7811', '0.0850', '0.0800'], [0.618, 0.790, 0.085, 0.080]],
        ['left_side', 'female', 'Abdominal', ['0.4223', '0.4338', '0.1150', '0.1150'], [0.415, 0.430, 0.115, 0.115]],
    ];

    public function up(): void
    {
        foreach (self::ROWS as [$view, $gender, $label, $stale, $reference]) {
            PostureMarker::query()
                ->where('view', $view)
                ->where('gender', $gender)
                ->where('label', $label)
                ->whereNull('condition')
                ->where('x', $stale[0])
                ->where('y', $stale[1])
                ->where('width', $stale[2])
                ->where('height', $stale[3])
                ->update([
                    'x' => $reference[0],
                    'y' => $reference[1],
                    'width' => $reference[2],
                    'height' => $reference[3],
                ]);
        }
    }

    public function down(): void
    {
        foreach (self::ROWS as [$view, $gender, $label, $stale, $reference]) {
            PostureMarker::query()
                ->where('view', $view)
                ->where('gender', $gender)
                ->where('label', $label)
                ->whereNull('condition')
                ->where('x', $reference[0])
                ->where('y', $reference[1])
                ->where('width', $reference[2])
                ->where('height', $reference[3])
                ->update([
                    'x' => $stale[0],
                    'y' => $stale[1],
                    'width' => $stale[2],
                    'height' => $stale[3],
                ]);
        }
    }
};
