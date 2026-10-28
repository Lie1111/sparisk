<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MuscleBodyImage;
use App\Models\MuscleCondition;
use App\Models\MuscleHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

/**
 * Admin editor for the muscle map: the posture conditions the app detects,
 * their patient wording, and the weak / tight / severe highlights drawn on the
 * boy and girl muscle body images.
 */
class PostureMarkerController extends Controller
{
    private const GENDERS = ['male', 'female'];

    public function index()
    {
        $conditions = MuscleCondition::with('highlights')->orderBy('order_index')->orderBy('id')->get();

        return Inertia::render('posture-marker/index', [
            'conditions' => $conditions,
            'statuses' => config('sparisk.muscle_statuses'),
            'views' => config('sparisk.muscle_views'),
            'bodyImages' => MuscleBodyImage::urlsByGender(),
            'customImages' => MuscleBodyImage::all(['gender', 'view'])->map(fn($i) => "{$i->gender}.{$i->view}")->values(),
            'classificationOptions' => $this->classificationOptions(),
        ]);
    }

    // ---- conditions ----

    public function storeCondition(Request $request)
    {
        $validated = $this->validateCondition($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['order_index'] = (int) MuscleCondition::max('order_index') + 1;

        MuscleCondition::create($validated);

        return back()->with('success', 'Condition created.');
    }

    public function updateCondition(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:muscle_conditions,id']]);
        $condition = MuscleCondition::findOrFail($request->integer('id'));

        $validated = $this->validateCondition($request);
        // Measurement triggers are authored in the seeder; the editor only
        // changes the classification list, so keep the stored sections.
        $validated['detection'] = [
            'classifications' => $validated['detection']['classifications'] ?? [],
            'sections' => $condition->detection['sections'] ?? [],
        ];

        $condition->update($validated);

        return back()->with('success', 'Condition updated.');
    }

    public function destroyCondition(Request $request)
    {
        $validated = $request->validate(['id' => ['required', 'integer', 'exists:muscle_conditions,id']]);

        MuscleCondition::findOrFail($validated['id'])->delete();

        return back()->with('success', 'Condition deleted.');
    }

    // ---- highlights ----

    public function storeHighlight(Request $request)
    {
        MuscleHighlight::create($this->validateHighlight($request));

        return back()->with('success', 'Muscle highlight added.');
    }

    public function updateHighlight(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:muscle_highlights,id']]);

        MuscleHighlight::findOrFail($request->integer('id'))->update($this->validateHighlight($request));

        return back();
    }

    public function destroyHighlight(Request $request)
    {
        $validated = $request->validate(['id' => ['required', 'integer', 'exists:muscle_highlights,id']]);

        MuscleHighlight::findOrFail($validated['id'])->delete();

        return back()->with('success', 'Muscle highlight removed.');
    }

    /**
     * Replace one gender's highlights of a condition with a copy of the other
     * gender's, so a map drawn on the boy can start the girl's.
     */
    public function copyHighlights(Request $request)
    {
        $validated = $request->validate([
            'muscle_condition_id' => ['required', 'integer', 'exists:muscle_conditions,id'],
            'from' => ['required', Rule::in(self::GENDERS)],
            'to' => ['required', Rule::in(self::GENDERS), 'different:from'],
        ]);

        $condition = MuscleCondition::with('highlights')->findOrFail($validated['muscle_condition_id']);

        $condition->highlights()->where('gender', $validated['to'])->delete();

        foreach ($condition->highlights->where('gender', $validated['from']) as $highlight) {
            $copy = $highlight->replicate();
            $copy->gender = $validated['to'];
            $copy->save();
        }

        return back()->with('success', 'Highlights copied.');
    }

    /**
     * Replace one side view's highlights of a condition with the other side's,
     * mirrored, so the left side can start from the right side's map.
     */
    public function mirrorSide(Request $request)
    {
        $validated = $request->validate([
            'muscle_condition_id' => ['required', 'integer', 'exists:muscle_conditions,id'],
            'gender' => ['required', Rule::in(self::GENDERS)],
            'from' => ['required', Rule::in(['right_side', 'left_side'])],
        ]);

        $to = $validated['from'] === 'right_side' ? 'left_side' : 'right_side';
        $condition = MuscleCondition::with('highlights')->findOrFail($validated['muscle_condition_id']);

        $condition->highlights()->where('gender', $validated['gender'])->where('view', $to)->delete();

        $sources = $condition->highlights
            ->where('gender', $validated['gender'])
            ->where('view', $validated['from']);

        foreach ($sources as $highlight) {
            $copy = $highlight->replicate();
            $copy->view = $to;
            $copy->x = 1 - $highlight->x;
            $copy->rotation = -$highlight->rotation;
            $copy->points = $highlight->points
                ? array_map(fn(array $p) => [-$p[0], $p[1]], $highlight->points)
                : null;
            $copy->save();
        }

        return back()->with('success', 'Side mirrored.');
    }

    // ---- body images ----

    public function uploadBodyImage(Request $request)
    {
        $validated = $request->validate([
            'gender' => ['required', Rule::in(self::GENDERS)],
            'view' => ['required', Rule::in(array_keys(config('sparisk.muscle_views')))],
            'image' => ['required', 'image', 'max:8192'],
        ]);

        $existing = MuscleBodyImage::where('gender', $validated['gender'])->where('view', $validated['view'])->first();
        $this->deleteStoredImage($existing?->path);

        MuscleBodyImage::updateOrCreate(
            ['gender' => $validated['gender'], 'view' => $validated['view']],
            ['path' => $request->file('image')->store('muscle-body', 'public')],
        );

        return back()->with('success', 'Body image updated.');
    }

    public function resetBodyImage(Request $request)
    {
        $validated = $request->validate([
            'gender' => ['required', Rule::in(self::GENDERS)],
            'view' => ['required', Rule::in(array_keys(config('sparisk.muscle_views')))],
        ]);

        $existing = MuscleBodyImage::where('gender', $validated['gender'])->where('view', $validated['view'])->first();

        if ($existing) {
            $this->deleteStoredImage($existing->path);
            $existing->delete();
        }

        return back()->with('success', 'Body image reset to the default.');
    }

    // ---- helpers ----

    private function validateCondition(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'effects' => ['nullable', 'string', 'max:2000'],
            'detection' => ['nullable', 'array'],
            'detection.classifications' => ['nullable', 'array'],
            'detection.classifications.*' => ['string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function validateHighlight(Request $request): array
    {
        return $request->validate([
            'muscle_condition_id' => ['required', 'integer', 'exists:muscle_conditions,id'],
            'gender' => ['required', Rule::in(self::GENDERS)],
            'view' => ['required', Rule::in(array_keys(config('sparisk.muscle_views')))],
            'muscle' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(array_keys(config('sparisk.muscle_statuses')))],
            'x' => ['required', 'numeric', 'min:0', 'max:1'],
            'y' => ['required', 'numeric', 'min:0', 'max:1'],
            'width' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'height' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'rotation' => ['nullable', 'integer', 'min:-180', 'max:180'],
            'points' => ['nullable', 'array', 'min:3', 'max:40'],
            'points.*' => ['array', 'size:2'],
            'points.*.*' => ['numeric', 'min:-1', 'max:1'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /**
     * Decision-engine classifications a condition can be linked to, keyed the
     * way the muscle map matches them ("Flat Back" -> FLAT_BACK).
     *
     * @return array<string, string>
     */
    private function classificationOptions(): array
    {
        $options = [];

        foreach (config('sparisk.decision_rules', []) as $rule) {
            $name = $rule['classification'] ?? null;
            if (!$name || ($rule['severity'] ?? null) === 'normal') {
                continue;
            }
            $options[trim(preg_replace('/[^A-Z0-9]+/', '_', strtoupper($name)), '_')] = $name;
        }

        return $options;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name, '_') ?: 'condition';
        $slug = $base;
        $i = 2;

        while (MuscleCondition::where('slug', $slug)->exists()) {
            $slug = $base . '_' . $i++;
        }

        return $slug;
    }

    private function deleteStoredImage(?string $path): void
    {
        // The default body images are shared; only an uploaded copy is removed.
        $defaults = collect(config('sparisk.muscle_default_images'))->flatten()->all();

        if (!empty($path) && !in_array($path, $defaults, true) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
