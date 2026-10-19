<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the patient-facing "what is the problem" result produced by the
 * decision pipeline, so every presentation layer (admin and app) renders the
 * same wording instead of rebuilding it.
 *
 * The value is a JSON object: { summary: string, problems: [ { section, view,
 * area, severity, severity_label, deviation_angle, direction, finding } ] }.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->json('patient_problem')->nullable()->after('clinical_summary');
        });
    }

    public function down(): void
    {
        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->dropColumn('patient_problem');
        });
    }
};
