<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureSeverityBand;
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

        $severityBands = PostureSeverityBand::orderBy('min')->get();
        if ($severityBands->isEmpty()) {
            $severityBands = collect(config('sparisk.severity_bands'));
        }

        return Inertia::render('posture-setting/index', [
            'settings' => $settings,
            'ageGroups' => config('sparisk.age_groups'),
            'severityBands' => $severityBands,
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

    /**
     * Update the SATA fixed severity bands (ranges + display labels).
     *
     * The `level` key is intentionally read-only because the decision engine
     * references it directly (e.g. conditions matching 'severe').
     */
    public function updateSeverityBands(Request $request)
    {
        $validated = $request->validate([
            'bands' => 'required|array|min:1',
            'bands.*.id' => 'required|integer|exists:posture_severity_bands,id',
            'bands.*.min' => 'required|numeric|min:0',
            'bands.*.max' => 'nullable|numeric|gt:bands.*.min',
            'bands.*.label' => 'required|string|max:50',
            'bands.*.color' => 'required|string|in:green,yellow,orange,red',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['bands'] as $band) {
                PostureSeverityBand::where('id', $band['id'])->update([
                    'min' => $band['min'],
                    'max' => $band['max'],
                    'label' => $band['label'],
                    'color' => $band['color'],
                ]);
            }
        });

        return back()->with('success', 'Severity bands updated.');
    }
}
