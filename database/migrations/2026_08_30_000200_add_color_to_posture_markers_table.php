<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_markers', function (Blueprint $table) {
            // Optional per-marker override (named colour or #RRGGBB). When null
            // the app falls back to the type default (tight=red, weak=green).
            $table->string('color')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('posture_markers', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
