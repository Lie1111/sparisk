<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_severity_bands', function (Blueprint $table) {
            $table->id();
            $table->string('level')->unique();      // stable key used by the decision engine (normal/mild/moderate/severe)
            $table->decimal('min', 8, 2);           // lower bound (inclusive)
            $table->decimal('max', 8, 2)->nullable(); // upper bound (exclusive); null = no upper bound
            $table->string('label');                // display label (NORMAL/MILD/MODERATE/SEVERE)
            $table->string('color');                // green/yellow/orange/red
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_severity_bands');
    }
};
