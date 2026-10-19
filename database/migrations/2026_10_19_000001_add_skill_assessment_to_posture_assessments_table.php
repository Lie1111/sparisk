<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links a posture assessment back to the skills checklist answered for the same
 * session, mirroring `health_screening_id`. The checklist is completed before
 * the photos are taken, so the link is written when the assessment is saved.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->foreignId('skill_assessment_id')
                ->nullable()
                ->after('health_screening_id')
                ->constrained('skill_assessments')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('skill_assessment_id');
        });
    }
};
