<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns CADANGAN INTERVENSI into a media-aware program catalogue.
 *
 * - posture_interventions gains a `program` (Aquatic Exercise / Massage Therapy
 *   Plan / General Exercise), a `level` chip and two image sources: an uploaded
 *   file (`image_path`, public disk) and an external link (`image_url`).
 * - The generated recommendation rows gain the same `program` + `image_url`, so
 *   the app renders the image the admin attached to the source intervention.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posture_interventions', function (Blueprint $table) {
            // See config('sparisk.intervention_programs').
            $table->string('program')->default('general_exercise')->after('category');
            $table->string('level')->nullable()->after('program');
            // Uploaded file on the public disk (storage/app/public/{image_path}).
            $table->string('image_path')->nullable()->after('duration_minutes');
            // External image link; takes precedence over the uploaded file.
            $table->string('image_url', 1024)->nullable()->after('image_path');
            $table->index(['program', 'posture_type', 'age_group']);
        });

        Schema::table('exercise_recommendations', function (Blueprint $table) {
            $table->string('program')->default('aquatic_exercise')->after('engine');
            $table->string('image_url', 1024)->nullable()->after('video_url');
            // Admin-authored "3 x 10 reps" style prescription, which does not fit
            // the integer `sets` / `reps` columns.
            $table->string('sets_reps')->nullable()->after('image_url');
        });

        Schema::table('massage_recommendations', function (Blueprint $table) {
            $table->string('program')->default('massage_therapy')->after('body_area');
            $table->string('image_url', 1024)->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('posture_interventions', function (Blueprint $table) {
            $table->dropIndex(['program', 'posture_type', 'age_group']);
            $table->dropColumn(['program', 'level', 'image_path', 'image_url']);
        });

        Schema::table('exercise_recommendations', function (Blueprint $table) {
            $table->dropColumn(['program', 'image_url', 'sets_reps']);
        });

        Schema::table('massage_recommendations', function (Blueprint $table) {
            $table->dropColumn(['program', 'image_url']);
        });
    }
};
