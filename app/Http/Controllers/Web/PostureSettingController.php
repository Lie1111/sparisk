<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PostureSettingController extends Controller
{
    /**
     * Render the posture settings editor (SATA Age-Based Posture Reference).
     */
    public function index()
    {
        $settings = PostureSetting::orderByRaw("FIELD(view, 'front', 'back', 'right_side', 'left_side')")
            ->orderBy('section')
            ->orderBy('age_group')
            ->get();

        return Inertia::render('posture-setting/index', [
            'settings' => $settings,
            'ageGroups' => config('sparisk.age_groups'),
            'severityBands' => config('sparisk.severity_bands'),
        ]);
    }

    /**
     * Update the reference value for a batch of rows.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|integer|exists:posture_settings,id',
            'settings.*.value' => 'required|numeric|min:-180|max:180',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['settings'] as $item) {
                PostureSetting::where('id', $item['id'])
                    ->update(['reference_value' => $item['value']]);
            }
        });

        return back()->with('success', 'Posture settings updated.');
    }
}
