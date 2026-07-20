<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('academy_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['student', 'instructor', 'coach'])->default('student');
            $table->timestamp('joined_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['academy_id', 'patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_members');
        Schema::dropIfExists('academies');
    }
};
