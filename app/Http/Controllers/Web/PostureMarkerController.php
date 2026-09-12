<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PostureMarker;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Admin editor for the red / green body markers drawn on the posture
 * character images. Markers are configured per view + gender and consumed by
 * the mobile app to point at the patient's tight (red) and weak (green)
 * regions.
 */
class PostureMarkerController extends Controller
{
    private const VIEWS = ['front', 'back', 'right_side', 'left_side'];
    // Male and female markers are independent; 'all' is intentionally not
    // offered here so a resize on one image never affects the other.
    private const GENDERS = ['male', 'female'];
    private const TYPES = ['tight', 'weak', 'normal'];
    // Marker sets are configured per result severity. 'moderate' covers the
    // SATA mild/moderate (mid) band.
    private const SEVERITIES = ['normal', 'moderate', 'severe'];

    public function index()
    {
        $markers = PostureMarker::orderBy('view')
            ->orderBy('gender')
            ->orderBy('order_index')
            ->orderBy('id')
            ->get();

        return Inertia::render('posture-marker/index', [
            'markers' => $markers,
            'views' => self::VIEWS,
            'genders' => self::GENDERS,
            'types' => self::TYPES,
            'severities' => self::SEVERITIES,
            'conditions' => config('sparisk.posture_conditions'),
            'characterImages' => [
                'male' => [
                    'front' => asset('storage/character/men-front.png'),
                    'back' => asset('storage/character/men-back.png'),
                    'right_side' => asset('storage/character/men-right.png'),
                    'left_side' => asset('storage/character/men-left.png'),
                ],
                'female' => [
                    'front' => asset('storage/character/women-front.png'),
                    'back' => asset('storage/character/women-back.png'),
                    'right_side' => asset('storage/character/women-right.png'),
                    'left_side' => asset('storage/character/women-left.png'),
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        PostureMarker::create($validated);

        return back()->with('success', 'Marker created.');
    }

    public function update(Request $request)
    {
        $validated = $this->validatePayload($request);
        $marker = PostureMarker::findOrFail($validated['id']);
        unset($validated['id']);

        $marker->update($validated);

        return back()->with('success', 'Marker updated.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:posture_markers,id'],
        ]);

        PostureMarker::findOrFail($validated['id'])->delete();

        return back()->with('success', 'Marker deleted.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'id' => ['nullable', 'integer', 'exists:posture_markers,id'],
            'view' => ['required', 'string', 'in:' . implode(',', self::VIEWS)],
            'gender' => ['required', 'string', 'in:' . implode(',', self::GENDERS)],
            'severity' => ['required', 'string', 'in:' . implode(',', self::SEVERITIES)],
            'type' => ['required', 'string', 'in:' . implode(',', self::TYPES)],
            'color' => ['nullable', 'string', 'max:20'],
            'label' => ['nullable', 'string', 'max:255'],
            'muscle' => ['nullable', 'string', 'max:255'],
            'x' => ['required', 'numeric', 'min:0', 'max:1'],
            'y' => ['required', 'numeric', 'min:0', 'max:1'],
            'width' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'height' => ['required', 'numeric', 'min:0.01', 'max:1'],
            'condition' => ['nullable', 'string', 'max:255'],
            'order_index' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
