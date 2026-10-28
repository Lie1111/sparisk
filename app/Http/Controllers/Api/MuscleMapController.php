<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MuscleCondition;
use App\Models\PostureAssessment;
use App\Services\Sparisk\MuscleMapService;
use App\Services\Sparisk\TreatmentPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Serves the muscle map to the mobile app: which conditions an assessment
 * shows, what each means for the patient, and the weak / tight muscles drawn
 * on the boy or girl muscle body image.
 */
class MuscleMapController extends Controller
{
    public function __construct(private MuscleMapService $muscleMap) {}

    /**
     * Every active condition for one gender (reference / browsing).
     */
    public function index(Request $request): JsonResponse
    {
        $gender = $this->muscleMap->gender($request->query('gender'));

        $conditions = MuscleCondition::with('highlights')
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get()
            ->map(fn($condition) => $this->muscleMap->serializeCondition($condition, $gender))
            ->values();

        return response()->json(array_merge($this->muscleMap->legend($gender), ['conditions' => $conditions]));
    }

    /**
     * The treatment plan for one assessment: per detected condition, the
     * aquatic exercises, massage therapy and general exercises with the
     * weekdays each one runs on.
     */
    public function program(PostureAssessment $postureAssessment, TreatmentPlanService $plans): JsonResponse
    {
        $postureAssessment->loadMissing('patient');

        return response()->json($plans->forAssessment($postureAssessment));
    }

    /**
     * The conditions detected for one assessment, on the patient's gender.
     */
    public function forAssessment(PostureAssessment $postureAssessment): JsonResponse
    {
        $postureAssessment->loadMissing('patient');

        return response()->json($this->muscleMap->forAssessment($postureAssessment));
    }
}
