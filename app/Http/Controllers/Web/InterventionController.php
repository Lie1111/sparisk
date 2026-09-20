<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureIntervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InterventionController extends Controller
{
    /**
     * Render the CADANGAN INTERVENSI editor.
     *
     * Admin can set up exercise / intervention suggestions per program
     * (Aquatic Exercise, Massage Therapy Plan, General Exercise), posture type
     * and age group (defaulting to the adolescent 13-18 band). Each entry can
     * carry an image, which the app renders on the Program tab.
     */
    public function index()
    {
        $interventions = PostureIntervention::orderBy('program')
            ->orderBy('posture_type')
            ->orderBy('age_group')
            ->orderBy('order_index')
            ->get();

        return Inertia::render('intervention/index', [
            'interventions' => $interventions,
            'postureTypes' => config('sparisk.intervention_posture_types'),
            'ageGroups' => config('sparisk.age_groups'),
            'categories' => config('sparisk.intervention_categories'),
            'programs' => config('sparisk.intervention_programs'),
            'levels' => config('sparisk.intervention_levels'),
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
            ->orderBy('program')
            ->orderBy('category')
            ->orderBy('order_index')
            ->get();

        return Inertia::render('intervention/show', [
            'interventions' => $interventions,
            'postureTypes' => $postureTypes,
            'ageGroups' => $ageGroups,
            'categories' => $categories,
            'programs' => config('sparisk.intervention_programs'),
            'levels' => config('sparisk.intervention_levels'),
            'posture' => $posture,
            'age' => $age,
        ]);
    }

    /**
     * Create a new intervention for a program + posture type + age group.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $image = $this->resolveImage($request, null);

        PostureIntervention::create([
            'posture_type' => $validated['posture_type'],
            'posture_label' => config('sparisk.intervention_posture_types.' . $validated['posture_type'])
                ?? $validated['posture_type'],
            'age_group' => $validated['age_group'],
            'category' => $validated['category'],
            'program' => $validated['program'],
            'level' => $validated['level'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sets_reps' => $validated['sets_reps'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'image_path' => $image['image_path'],
            'image_url' => $image['image_url'],
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

        $image = $this->resolveImage($request, $intervention);

        $intervention->update([
            'posture_type' => $validated['posture_type'],
            'posture_label' => config('sparisk.intervention_posture_types.' . $validated['posture_type'])
                ?? $validated['posture_type'],
            'age_group' => $validated['age_group'],
            'category' => $validated['category'],
            'program' => $validated['program'],
            'level' => $validated['level'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sets_reps' => $validated['sets_reps'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'image_path' => $image['image_path'],
            'image_url' => $image['image_url'],
            'order_index' => $validated['order_index'] ?? 0,
        ]);

        return back()->with('success', 'Intervention updated.');
    }

    /**
     * Delete an intervention row and the image uploaded with it.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:posture_interventions,id'],
        ]);

        $intervention = PostureIntervention::findOrFail($validated['id']);

        $this->deleteStoredImage($intervention->image_path);
        $intervention->delete();

        return back()->with('success', 'Intervention deleted.');
    }

    /**
     * Works out the image to store.
     *
     * A newly uploaded file replaces whatever was there; `remove_image` clears
     * it; otherwise the existing values are kept. `image_url` always mirrors the
     * submitted field so an admin can paste a link instead of uploading.
     *
     * @return array{image_path: ?string, image_url: ?string}
     */
    private function resolveImage(Request $request, ?PostureIntervention $existing): array
    {
        $imagePath = $existing?->image_path;
        $imageUrl = $request->filled('image_url') ? $request->string('image_url')->toString() : null;

        if ($request->hasFile('image_file')) {
            $this->deleteStoredImage($imagePath);
            $imagePath = $request->file('image_file')->store('interventions', 'public');
        } elseif ($request->boolean('remove_image')) {
            $this->deleteStoredImage($imagePath);
            $imagePath = null;
        }

        // An uploaded file is stored locally, so a stale external link from a
        // previous edit must not keep overriding it.
        if ($request->hasFile('image_file') && !$request->filled('image_url')) {
            $imageUrl = null;
        }

        return ['image_path' => $imagePath, 'image_url' => $imageUrl];
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'id' => ['nullable', 'integer', 'exists:posture_interventions,id'],
            'posture_type' => ['required', 'string'],
            'age_group' => ['required', 'string'],
            'category' => ['required', 'string'],
            'program' => ['required', 'string', 'in:' . implode(',', array_keys(config('sparisk.intervention_programs')))],
            'level' => ['nullable', 'string', 'in:' . implode(',', array_keys(config('sparisk.intervention_levels')))],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sets_reps' => ['nullable', 'string', 'max:100'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:600'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url', 'max:1024'],
            'image_file' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
