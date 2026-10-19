<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\SkillAssessment;
use App\Services\Sparisk\AquaticSkillsProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The SATA Aquatic Skills Assessment checklist and the progress dashboard it
 * feeds, for the mobile app.
 */
class SkillAssessmentController extends Controller
{
    public function __construct(private AquaticSkillsProgressService $progress) {}

    /**
     * The checklist definition, so the app renders the wording from the backend
     * rather than keeping a second copy that can drift.
     */
    public function catalogue(): JsonResponse
    {
        $catalogue = config('sparisk.aquatic_skills');

        return response()->json([
            'version' => $catalogue['version'],
            'categories' => $catalogue['categories'],
            'items' => collect($catalogue['items'])
                ->map(fn (array $item, int $number) => [
                    'number' => $number,
                    'category' => $item['category'],
                    'text' => $item['text'],
                ])
                ->values(),
            'max_score' => count($catalogue['items']),
        ]);
    }

    /**
     * Record a sitting. Every item must be answered: the form has only a yes and
     * a no, so a missing answer would silently score as a no.
     */
    public function store(Request $request, Patient $patient): JsonResponse
    {
        $itemNumbers = array_keys(config('sparisk.aquatic_skills.items', []));

        $validated = $request->validate([
            'answers' => 'required|array',
            'assessed_on' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // `has` reports presence, not truthiness, so an explicit "no" still
        // counts as answered.
        $missing = array_values(array_filter(
            $itemNumbers,
            fn (int $number) => ! $request->has("answers.{$number}")
        ));

        if ($missing !== []) {
            return response()->json([
                'error' => 'Every checklist item must be answered.',
                'code' => 'incomplete_checklist',
                'missing' => $missing,
                'data' => null,
            ], 422);
        }

        $scored = SkillAssessment::score($validated['answers']);

        $assessment = $patient->skillAssessments()->create([
            'answers' => $scored['answers'],
            'total_score' => $scored['total_score'],
            'max_score' => $scored['max_score'],
            'category_scores' => $scored['category_scores'],
            'assessed_on' => $validated['assessed_on'] ?? now()->toDateString(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Skills checklist saved',
            'data' => $assessment,
        ], 201);
    }

    /**
     * Every sitting for a patient, newest last, with the dashboard figures.
     */
    public function index(Patient $patient): JsonResponse
    {
        return response()->json($this->progress->forPatient($patient));
    }
}
