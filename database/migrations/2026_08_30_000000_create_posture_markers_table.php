<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_markers', function (Blueprint $table) {
            $table->id();
            $table->string('view');                     // front, back, right_side, left_side
            $table->string('gender')->default('all');   // male, female, all
            $table->string('type')->default('tight');   // tight (red), weak (green), normal
            $table->string('label')->nullable();         // human readable marker label
            $table->string('muscle')->nullable();        // muscle / region name
            $table->decimal('x', 6, 4);                  // normalized center x (0..1)
            $table->decimal('y', 6, 4);                  // normalized center y (0..1)
            $table->decimal('width', 6, 4)->default(0.12);
            $table->decimal('height', 6, 4)->default(0.12);
            $table->string('condition')->nullable();     // optional posture condition key
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['view', 'gender']);
            $table->index('condition');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_markers');
    }
};
