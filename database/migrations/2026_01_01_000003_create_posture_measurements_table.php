<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->enum('view', ['front', 'back', 'right_side', 'left_side']);
            $table->string('section');
            $table->string('label');
            $table->decimal('value', 8, 2);
            $table->string('unit')->default('°');
            $table->enum('severity', ['normal', 'mild', 'moderate', 'severe'])->nullable();
            $table->string('status_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_measurements');
    }
};
