<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SATA Posture Reference Table v1 — Evidence Based (DEVIATION ANGLE OUTPUT).
 *
 * Every measurement is stored as a deviation angle:
 *   Deviation Angle = Measured Angle − Applicable Reference Value
 *
 * The reference used for the calculation must be traceable, so each stored
 * measurement keeps the reference source, the reference level (published /
 * sata_validated / individual_baseline), the age group and — only when the
 * evidence requires it — the sex.
 *
 * posture_settings gains the same provenance columns, plus the approved
 * reference band (normal_min / normal_max). A parameter with no published or
 * SATA-validated reference is stored as reference_level = individual_baseline
 * with a NULL reference value: no population cut-off is invented.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->decimal('measured_angle', 8, 2)->nullable()->after('value');
            $table->decimal('deviation_angle', 8, 2)->nullable()->after('deviation');
            $table->string('reference_source')->nullable()->after('deviation_angle');
            $table->string('reference_level')->nullable()->after('reference_source');
            $table->string('age_group')->nullable()->after('reference_level');
            $table->string('sex_if_required')->nullable()->after('age_group');
            $table->string('status_code')->nullable()->after('sex_if_required');
            $table->string('posture_classification')->nullable()->after('status_code');
        });

        Schema::table('posture_settings', function (Blueprint $table) {
            $table->decimal('reference_value', 8, 2)->nullable()->change();
            $table->decimal('normal_min', 8, 2)->nullable()->after('reference_value');
            $table->decimal('normal_max', 8, 2)->nullable()->after('normal_min');
            $table->string('reference_source')->nullable()->after('normal_max');
            $table->string('reference_level')->nullable()->after('reference_source');
            $table->string('sex_if_required')->nullable()->after('reference_level');
        });
    }

    public function down(): void
    {
        Schema::table('posture_settings', function (Blueprint $table) {
            $table->dropColumn([
                'normal_min',
                'normal_max',
                'reference_source',
                'reference_level',
                'sex_if_required',
            ]);
            $table->decimal('reference_value', 8, 2)->nullable(false)->change();
        });

        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->dropColumn([
                'measured_angle',
                'deviation_angle',
                'reference_source',
                'reference_level',
                'age_group',
                'sex_if_required',
                'status_code',
                'posture_classification',
            ]);
        });
    }
};
