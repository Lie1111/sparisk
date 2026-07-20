<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('health_screening_id')->nullable()->constrained()->nullOnDelete();
            $table->date('assessment_date');
            $table->string('time_mark')->default('TM1');
            $table->integer('overall_score')->nullable();
            $table->string('overall_status')->nullable();
            $table->string('posture_classification')->nullable();
            $table->text('clinical_summary')->nullable();
            $table->text('primary_findings')->nullable();
            $table->text('need_attention')->nullable();
            $table->text('biggest_improvement')->nullable();
            $table->enum('overall_progress', [
                'high_improvement', 'moderate_improvement', 'stable', 'regression'
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_assessments');
    }
};
