<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_assessment_id')->nullable()->constrained('posture_assessments')->nullOnDelete();
            $table->foreignId('to_assessment_id')->nullable()->constrained('posture_assessments')->nullOnDelete();
            $table->json('improvement_fields')->nullable();
            $table->json('attention_fields')->nullable();
            $table->enum('overall_progress', [
                'high_improvement', 'moderate_improvement', 'stable', 'regression'
            ])->nullable();
            $table->string('comparison_image_path')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_records');
    }
};
