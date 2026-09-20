<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bmi_references', function (Blueprint $table) {
            $table->id();
            $table->string('age_group');                 // SATA age group key (6-8 / 9-12 / 13-18 / 19-49 / 50+)
            $table->unsignedTinyInteger('age_years')->nullable(); // exact age for children; null for adult bands
            $table->string('gender')->nullable();        // male / female for children; null = shared adult cut-off
            $table->decimal('min_bmi', 5, 2);            // lower bound (inclusive)
            $table->decimal('max_bmi', 5, 2)->nullable(); // upper bound (exclusive); null = no upper bound
            $table->string('category');                  // display category (Underweight / Normal / Overweight / ...)
            $table->string('source');                    // provenance of the reference values
            $table->boolean('is_verified')->default(false); // false until SATA confirms against the clinical dataset
            $table->timestamps();

            // One band per age group + exact age + gender + lower bound.
            $table->unique(['age_group', 'age_years', 'gender', 'min_bmi'], 'bmi_references_band_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bmi_references');
    }
};
