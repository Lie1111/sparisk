<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Male and female character images must be adjustable independently, so any
 * shared ('all') marker is duplicated into a separate male and female row.
 */
return new class extends Migration
{
    public function up(): void
    {
        $shared = DB::table('posture_markers')->where('gender', 'all')->get();

        foreach ($shared as $marker) {
            foreach (['male', 'female'] as $gender) {
                DB::table('posture_markers')->insert([
                    'view' => $marker->view,
                    'gender' => $gender,
                    'type' => $marker->type,
                    'label' => $marker->label,
                    'muscle' => $marker->muscle,
                    'x' => $marker->x,
                    'y' => $marker->y,
                    'width' => $marker->width,
                    'height' => $marker->height,
                    'condition' => $marker->condition,
                    'order_index' => $marker->order_index,
                    'is_active' => $marker->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('posture_markers')->where('id', $marker->id)->delete();
        }
    }

    public function down(): void
    {
        // Best effort: drop the male duplicates and re-share the female rows.
        DB::table('posture_markers')->where('gender', 'male')->delete();
        DB::table('posture_markers')->where('gender', 'female')->update(['gender' => 'all']);
    }
};
