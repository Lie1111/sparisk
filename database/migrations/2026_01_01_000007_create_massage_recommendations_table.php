<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('massage_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posture_assessment_id')->constrained()->cascadeOnDelete();
            $table->string('body_area');
            $table->integer('priority_stars')->default(1);
            $table->text('instructions')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('frequency')->nullable();
            $table->string('video_url')->nullable();
            $table->text('safety_notes')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('massage_recommendations');
    }
};
