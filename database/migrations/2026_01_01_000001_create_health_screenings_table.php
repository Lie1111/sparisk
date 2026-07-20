<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->boolean('fear_of_water')->default(false);
            $table->boolean('history_of_seizure')->default(false);
            $table->boolean('heart_disease')->default(false);
            $table->boolean('asthma')->default(false);
            $table->boolean('neck_pain')->default(false);
            $table->boolean('back_pain')->default(false);
            $table->boolean('hip_pain')->default(false);
            $table->boolean('can_follow_instruction')->default(true);
            $table->boolean('can_stand_independently')->default(true);
            $table->enum('safety_level', ['low', 'medium', 'high'])->default('high');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_screenings');
    }
};
