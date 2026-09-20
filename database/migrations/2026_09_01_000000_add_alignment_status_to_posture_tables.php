<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the user-facing alignment reporting fields introduced with the
 * "Measurement Review Required" classification policy.
 *
 * - posture_measurements: alignment_status / position_note / review_required
 * - posture_assessments : review_status / suspected_pattern / secondary_pattern
 *                         / asymmetry_flag / confidence_level
 * - posture_classifications: severity is widened from enum to string so that
 *   neutral ('normal') and review outcomes can be stored as well.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->string('deviation_direction')->nullable()->after('deviation');
            $table->string('alignment_status')->nullable()->after('severity');
            $table->string('position_note')->nullable()->after('alignment_status');
            $table->boolean('review_required')->default(false)->after('position_note');
        });

        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->string('review_status')->nullable()->after('posture_classification');
            $table->string('suspected_pattern')->nullable()->after('review_status');
            $table->string('secondary_pattern')->nullable()->after('suspected_pattern');
            $table->boolean('asymmetry_flag')->default(false)->after('secondary_pattern');
            $table->string('confidence_level')->nullable()->after('asymmetry_flag');
        });

        Schema::table('posture_classifications', function (Blueprint $table) {
            $table->string('severity')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posture_classifications', function (Blueprint $table) {
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable()->change();
        });

        Schema::table('posture_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'review_status',
                'suspected_pattern',
                'secondary_pattern',
                'asymmetry_flag',
                'confidence_level',
            ]);
        });

        Schema::table('posture_measurements', function (Blueprint $table) {
            $table->dropColumn(['deviation_direction', 'alignment_status', 'position_note', 'review_required']);
        });
    }
};
