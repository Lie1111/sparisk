<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Null until the participant has answered the Neurodevelopmental
            // Profile, so the app only asks once and can skip it afterwards.
            $table->string('neuro_profile')->nullable()->after('special_needs_type');
            // Multi-select condition keys (see config('sparisk.neuro_conditions')).
            $table->json('neuro_conditions')->nullable()->after('neuro_profile');
            // Free text captured when the "Other" condition is selected.
            $table->string('neuro_conditions_other')->nullable()->after('neuro_conditions');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['neuro_profile', 'neuro_conditions', 'neuro_conditions_other']);
        });
    }
};
