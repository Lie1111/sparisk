<?php

use App\Models\PostureIntervention;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Old intervention keys whose muscle-map condition is spelt differently. */
    private const RENAMED = [
        'flatback' => 'flat_back',
        'swayback' => 'sway_back',
    ];

    public function up(): void
    {
        // The weekdays an item runs on, so the weekly program is built from
        // the plan itself instead of a fixed template.
        Schema::table('posture_interventions', function (Blueprint $table) {
            $table->json('days')->nullable()->after('frequency');
        });

        // Treatment plans are keyed by the muscle-map condition slug.
        foreach (self::RENAMED as $from => $to) {
            DB::table('posture_interventions')->where('posture_type', $from)->update(['posture_type' => $to]);
        }

        foreach (DB::table('posture_interventions')->get(['id', 'program', 'frequency']) as $row) {
            DB::table('posture_interventions')->where('id', $row->id)->update([
                'days' => json_encode(PostureIntervention::defaultDays($row->frequency, $row->program)),
            ]);
        }
    }

    public function down(): void
    {
        foreach (self::RENAMED as $from => $to) {
            DB::table('posture_interventions')->where('posture_type', $to)->update(['posture_type' => $from]);
        }

        Schema::table('posture_interventions', function (Blueprint $table) {
            $table->dropColumn('days');
        });
    }
};
