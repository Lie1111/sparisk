<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_images', function (Blueprint $table) {
            $table->json('highlights')->nullable()->after('landmarks');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_images', function (Blueprint $table) {
            $table->dropColumn('highlights');
        });
    }
};
