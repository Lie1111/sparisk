<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SATA Posture Reference Table v1 — Evidence Based.
 *
 * A few parameters (Pelvic Tilt, Knee) carry sex-specific normative values.
 * `sex_if_required` on the parameter is a boolean flag; the `sex` column on
 * the row holds the sex the reference applies to ('male' / 'female'), so the
 * seeder can expand one locked parameter into sex-specific rows and the engine
 * can resolve the correct reference for the patient.
 *
 * `sex_if_required` stays a boolean flag (0/1) on every row of the parameter.
 */
return new class extends Migration
{
    public function up(): void
    {
        // The original (view, section, age_group) unique index cannot hold both
        // the male and female reference for the same parameter, so it is
        // replaced by one that also includes the sex column.
        Schema::table('posture_settings', function (Blueprint $table) {
            $table->dropUnique('posture_settings_view_section_age_group_unique');
            $table->string('sex')->nullable()->after('sex_if_required');
            $table->unique(['view', 'section', 'age_group', 'sex'], 'posture_settings_reference_unique');
        });

        // The boolean flag is stored as 0/1 so the Eloquent boolean cast is
        // consistent across every driver.
        Schema::table('posture_settings', function (Blueprint $table) {
            $table->boolean('sex_if_required')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('posture_settings', function (Blueprint $table) {
            $table->dropUnique('posture_settings_reference_unique');
            $table->dropColumn('sex');
            $table->unique(['view', 'section', 'age_group']);
        });
    }
};
