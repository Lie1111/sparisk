<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->enum('classification_type', ['primary', 'secondary']);
            $table->string('classification_name');
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_classifications');
    }
};
