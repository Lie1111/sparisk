<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_markers', function (Blueprint $table) {
            // Which severity result this marker set belongs to:
            // normal, moderate (mild/mid) or severe.
            $table->string('severity')->default('normal')->after('gender');
            $table->index(['view', 'gender', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::table('posture_markers', function (Blueprint $table) {
            $table->dropIndex(['view', 'gender', 'severity']);
            $table->dropColumn('severity');
        });
    }
};
