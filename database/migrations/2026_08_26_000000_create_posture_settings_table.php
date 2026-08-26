<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posture_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('view', ['front', 'back', 'right_side', 'left_side']);
            $table->string('section');               // measurement code (A1..D7) or a clinical key (side_cva etc.)
            $table->string('label');                 // human readable name (SATA parameter)
            $table->enum('age_group', ['6-8', '9-12', '13-18', '19-49', '50+']);
            $table->decimal('reference_value', 8, 2); // SATA fixed reference angle (degrees)
            $table->text('interpretation')->nullable();
            $table->timestamps();

            $table->unique(['view', 'section', 'age_group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posture_settings');
    }
};
