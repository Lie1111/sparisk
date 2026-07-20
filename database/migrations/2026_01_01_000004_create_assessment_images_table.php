<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->enum('view', ['front', 'back', 'right_side', 'left_side']);
            $table->string('image_path');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_images');
    }
};
