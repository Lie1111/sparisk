<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureIntervention;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InterventionController extends Controller
{
    /**
     * Render the CADANGAN INTERVENSI editor.
     *
     * Admin can set up exercise / intervention suggestions per posture type
     * and age group (defaulting to the adolescent 13-18 band).
     */
    public function index()
    {
        $interventions = PostureIntervention::orderBy('posture_type')
            ->orderBy('age_group')
            ->orderBy('order_index')
            ->get();

        return Inertia::render('intervention/index', [
            'interventions' => $interventions,
            'postureTypes' => config('sparisk.intervention_posture_types'),
            'ageGroups' => config('sparisk.age_groups'),
            'categories' => config('sparisk.intervention_categories'),
        ]);
    }

    /**
     * Render a posture-specific intervention plan for a given age group.
     * Lists the interventions grouped by category for that posture type.
     */
    public function show(string $posture, string $age)
    {
        $postureTypes = config('sparisk.intervention_posture_types');
        $ageGroups = config('sparisk.age_groups');
        $categories = config('sparisk.intervention_categories');

        if (!isset($postureTypes[$posture])) {
            abort(404);
        }

        $age = array_key_exists($age, $ageGroups) ? $age : '13-18';

        $interventions = PostureIntervention::where('posture_type', $posture)
            ->where('age_group', $age)
            ->orderBy('category')
            ->orderBy('order_index')
            ->get();

        return Inertia::render('intervention/show', [
            'interventions' => $interventions,
            'postureTypes' => $postureTypes,
            'ageGroups' => $ageGroups,
            'categories' => $categories,
            'posture' => $posture,
            'age' => $age,
        ]);
    }

    /**
     * Create a new intervention for a posture type + age group.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        PostureIntervention::create([
            'posture_type' => $validated['posture_type'],
            'posture_label' => config('sparisk.intervention_posture_types.' . $validated['posture_type'])
                ?? $validated['posture_type'],
            'age_group' => $validated['age_group'],
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sets_reps' => $validated['sets_reps'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order_index' => $validated['order_index'] ?? 0,
        ]);

        return back()->with('success', 'Intervention added.');
    }

    /**
     * Update an existing intervention row.
     */
    public function update(Request $request)
    {
        $validated = $this->validatePayload($request);

        $intervention = PostureIntervention::findOrFail($validated['id']);

        $intervention->update([
            'posture_type' => $validated['posture_type'],
            'posture_label' => config('sparisk.intervention_posture_types.' . $validated['posture_type'])
                ?? $validated['posture_type'],
            'age_group' => $validated['age_group'],
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sets_reps' => $validated['sets_reps'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order_index' => $validated['order_index'] ?? 0,
        ]);

        return back()->with('success', 'Intervention updated.');
    }

    /**
     * Delete an intervention row.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:posture_interventions,id'],
        ]);

        PostureIntervention::findOrFail($validated['id'])->delete();

        return back()->with('success', 'Intervention deleted.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'id' => ['nullable', 'integer', 'exists:posture_interventions,id'],
            'posture_type' => ['required', 'string'],
            'age_group' => ['required', 'string'],
            'category' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sets_reps' => ['nullable', 'string', 'max:100'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
