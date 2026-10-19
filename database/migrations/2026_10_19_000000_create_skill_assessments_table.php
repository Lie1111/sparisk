<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The SATA Aquatic Skills Assessment checklist: 35 yes/no items answered before
 * the posture capture.
 *
 * The score is stored rather than recomputed so an assessment keeps the mark it
 * was given even if the checklist is later reworded or rebalanced — the item
 * distribution is still marked "not yet confirmed" in the source document, so
 * history must not silently move.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            // Item number => answered yes. Keyed by the numbers in
            // config('sparisk.aquatic_skills.items') so a rewording never
            // renumbers a stored answer.
            $table->json('answers');

            $table->unsignedSmallInteger('total_score');
            $table->unsignedSmallInteger('max_score');

            // [{key, label, achieved, total}] frozen at the time of assessment,
            // so the progress chart keeps its denominator if the categories are
            // rebalanced later.
            $table->json('category_scores')->nullable();

            $table->date('assessed_on');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'assessed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_assessments');
    }
};
