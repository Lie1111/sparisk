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
            'referenceLevels' => config('sparisk.reference_levels'),
        ]);
    }

    /**
     * Update the reference for a batch of rows.
     *
     * A parameter resolves its reference through the SATA hierarchy, so the
     * admin edits the whole reference block together — not just the centre
     * value:
     *   - reference_level    : published | sata_validated | individual_baseline
     *   - reference_value    : the reference centre (Level 1/2 only)
     *   - normal_min/max     : the published band the tolerance is read from
     *   - reference_source   : the real citation for the value
     *
     * Evidence gaps (`individual_baseline`) intentionally store NO reference:
     * the first assessment becomes the individual's baseline. Any reference
     * value sent for a gap is discarded rather than invented.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|integer|exists:posture_settings,id',
            'settings.*.reference_level' => 'nullable|string|in:published,sata_validated,individual_baseline',
            'settings.*.reference_value' => 'nullable|numeric|min:-180|max:180',
            'settings.*.normal_min' => 'nullable|numeric|min:-180|max:180',
            'settings.*.normal_max' => 'nullable|numeric|min:-180|max:180',
            'settings.*.reference_source' => 'nullable|string|max:255',
            'settings.*.sex' => 'nullable|string|in:male,female',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['settings'] as $item) {
                $level = $item['reference_level'] ?? null;
                $isBaseline = $level === 'individual_baseline';

                // An evidence gap has no population reference by definition.
                $reference = $isBaseline ? null : ($item['reference_value'] ?? null);
                $min = $isBaseline ? null : ($item['normal_min'] ?? null);
                $max = $isBaseline ? null : ($item['normal_max'] ?? null);

                if ($min !== null && $max !== null && (float) $max < (float) $min) {
                    [$min, $max] = [$max, $min];
                }

                PostureSetting::where('id', $item['id'])->update([
                    'reference_level' => $level,
                    'reference_value' => $reference,
                    'normal_min' => $min,
                    'normal_max' => $max,
                    'reference_source' => $isBaseline ? null : ($item['reference_source'] ?? null),
                    'sex' => $item['sex'] ?? null,
                ]);
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
