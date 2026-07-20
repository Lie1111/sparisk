<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->string('engine')->default('SARE');
            $table->string('exercise_name');
            $table->string('program_level')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('estimated_duration_minutes')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('progression_stage')->nullable();
            $table->text('instructions')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_recommendations');
    }
};
