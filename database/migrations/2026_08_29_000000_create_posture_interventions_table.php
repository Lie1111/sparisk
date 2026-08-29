<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_interventions', function (Blueprint $table) {
            $table->id();
            // Stable slug from config('sparisk.intervention_posture_types')
            $table->string('posture_type');
            $table->string('posture_label');            // human readable label
            $table->enum('age_group', ['6-8', '9-12', '13-18', '19-49', '50+'])->default('13-18');
            $table->enum('category', ['exercise', 'stretching', 'strengthening', 'postural_awareness', 'manual_therapy', 'lifestyle'])->default('exercise');
            $table->string('title');                    // exercise / intervention name
            $table->text('description')->nullable();
            $table->string('sets_reps')->nullable();    // e.g. "3 sets x 10 reps"
            $table->string('frequency')->nullable();    // e.g. "Daily", "3x per week"
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->index(['posture_type', 'age_group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_interventions');
    }
};
