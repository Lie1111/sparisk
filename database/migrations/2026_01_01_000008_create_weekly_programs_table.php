<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->integer('week_number')->default(1);
            $table->enum('day_of_week', [
                'monday', 'tuesday', 'wednesday', 'thursday',
                'friday', 'saturday', 'sunday'
            ]);
            $table->enum('activity_type', [
                'aquatic_therapy', 'massage', 'stretching',
                'balance_exercise', 'strength_training',
                'rest', 'family_activity', 'custom'
            ]);
            $table->string('activity_title')->nullable();
            $table->text('activity_details')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('exercise_ids')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_programs');
    }
};
