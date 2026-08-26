<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->decimal('reference_value', 8, 2)->nullable()->after('value');
            $table->decimal('deviation', 8, 2)->nullable()->after('reference_value');
        });
    }

    public function down(): void
    {
        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->dropColumn(['reference_value', 'deviation']);
        });
    }
};
