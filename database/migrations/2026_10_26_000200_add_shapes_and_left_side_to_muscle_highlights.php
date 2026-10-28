<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A muscle outline as points in the highlight's own box (-0.5..0.5 on
        // each axis, before rotation). Null keeps the soft ellipse.
        Schema::table('muscle_highlights', function (Blueprint $table) {
            $table->json('points')->nullable()->after('rotation');
        });

        // The assessment captures the right and left side separately, so the
        // single side view becomes the right side and a left side is added.
        DB::table('muscle_highlights')->where('view', 'side')->update(['view' => 'right_side']);
        DB::table('muscle_body_images')->where('view', 'side')->update(['view' => 'right_side']);
    }

    public function down(): void
    {
        DB::table('muscle_highlights')->where('view', 'left_side')->delete();
        DB::table('muscle_body_images')->where('view', 'left_side')->delete();
        DB::table('muscle_highlights')->where('view', 'right_side')->update(['view' => 'side']);
        DB::table('muscle_body_images')->where('view', 'right_side')->update(['view' => 'side']);

        Schema::table('muscle_highlights', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
