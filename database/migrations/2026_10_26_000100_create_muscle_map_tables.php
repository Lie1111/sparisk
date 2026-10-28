<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A posture condition the app can detect (Kyphosis, Flat Back, ...)
        // with the plain-English wording shown to the patient.
        Schema::create('muscle_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();   // what the condition is
            $table->text('effects')->nullable();       // one problem per line
            $table->json('detection')->nullable();     // {classifications: [...], sections: [...]}
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // A soft muscle highlight drawn on the muscle body image. Coordinates
        // are normalized (0..1) to the image; rotation is in degrees.
        Schema::create('muscle_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muscle_condition_id')->constrained()->cascadeOnDelete();
            $table->string('gender');                   // male, female
            $table->string('view');                     // front, back, side
            $table->string('muscle');
            $table->string('status')->default('tight'); // weak, tight, severe
            $table->decimal('x', 6, 4);
            $table->decimal('y', 6, 4);
            $table->decimal('width', 6, 4)->default(0.08);
            $table->decimal('height', 6, 4)->default(0.08);
            $table->smallInteger('rotation')->default(0);
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->index(['muscle_condition_id', 'gender', 'view']);
        });

        // The muscle anatomy body image per gender + view, uploaded by admins.
        Schema::create('muscle_body_images', function (Blueprint $table) {
            $table->id();
            $table->string('gender');
            $table->string('view');
            $table->string('path');
            $table->timestamps();

            $table->unique(['gender', 'view']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muscle_highlights');
        Schema::dropIfExists('muscle_body_images');
        Schema::dropIfExists('muscle_conditions');
    }
};
