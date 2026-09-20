<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentImage;
use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\PostureIntervention;
use App\Services\Sparisk\SpariskAquaticRecommendationEngine;
use App\Services\Sparisk\SpariskDecisionEngine;
use App\Services\Sparisk\SpariskInterpretationEngine;
use App\Services\Sparisk\SpariskMassageRecommendationEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;
use App\Services\Sparisk\SpariskPersonalProgramEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostureAssessmentController extends Controller
{
    public function __construct(
        private SpariskMeasurementEngine $measurementEngine,
        private SpariskInterpretationEngine $interpretationEngine,
        private SpariskDecisionEngine $decisionEngine,
        private SpariskAquaticRecommendationEngine $aquaticEngine,
        private SpariskMassageRecommendationEngine $massageEngine,
        private SpariskPersonalProgramEngine $programEngine,
    ) {}

    /**
     * Full assessment pipeline:
     * 1. Upload images
     * 2. Process measurements via SME
     * 3. Interpret via SIE
     * 4. Classify/Decide via SDE
     * 5. Generate exercise recs via SARE
     * 6. Generate massage recs via SMRE
     * 7. Generate weekly program via SPPE
     */
    public function store(Request $request, Patient $patient): JsonResponse
    {
        $request->validate([
            'assessment_date' => 'required|date',
            'health_screening_id' => 'nullable|exists:health_screenings,id',
            'measurements' => 'required|array',
            'measurements.*.section' => 'required|string',
            'measurements.*.value' => 'required|numeric',
            'images' => 'nullable|array',
            'images.*.view' => 'required|in:front,back,right_side,left_side',
            'images.*.file' => 'required|image|max:10240',
            'images.*.highlights' => 'nullable',
        ]);

        $previousAssessments = $patient->postureAssessments()->count();
        $timeMark = 'TM' . ($previousAssessments + 1);

        DB::beginTransaction();
        try {
            $assessment = PostureAssessment::create([
                'patient_id' => $patient->id,
                'health_screening_id' => $request->health_screening_id,
                'assessment_date' => $request->assessment_date,
                'time_mark' => $timeMark,
            ]);

            $landmarks = collect($request->measurements)->mapWithKeys(fn($m) => [
                $m['section'] => $m['value']
            ])->toArray();

            $this->measurementEngine->process($landmarks, $assessment);

            $overallScore = $this->measurementEngine->calculateOverallScore($assessment);

            $interpretations = $this->interpretationEngine->interpret($assessment);
            $symmetry = $this->interpretationEngine->generateSymmetry($assessment);
            $overallInterpretation = $this->interpretationEngine->generateOverallInterpretation($assessment);
            $primaryFindings = $this->interpretationEngine->generatePrimaryFindings($assessment);
            $clinicalSummary = $this->interpretationEngine->generateClinicalSummary($assessment);

            $decisionResult = $this->decisionEngine->decide($assessment);
            $globalAnalysis = $this->decisionEngine->generateGlobalAnalysis($assessment);

            $aquaticRecs = $this->aquaticEngine->recommend($assessment);
            $massageRecs = $this->massageEngine->recommend($assessment);
            $weeklyProgram = $this->programEngine->generate($assessment);

            $assessment->update([
                'overall_score' => $overallScore,
                'overall_status' => $overallScore >= 80 ? 'GOOD' : ($overallScore >= 60 ? 'MODERATE' : 'NEEDS IMPROVEMENT'),
                'clinical_summary' => $clinicalSummary,
                'primary_findings' => json_encode($primaryFindings),
            ]);

            if ($request->has('images')) {
                foreach ($request->images as $index => $imageData) {
                    if (isset($imageData['file']) && $imageData['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $path = $imageData['file']->store('assessments/' . $assessment->id, 'public');
                        AssessmentImage::create([
                            'posture_assessment_id' => $assessment->id,
                            'view' => $imageData['view'],
                            'image_path' => $path,
                            'highlights' => $this->decodeHighlights($imageData['highlights'] ?? null),
                            'order_index' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            $assessment->load([
                'measurements', 'images', 'classifications',
                'exerciseRecommendations', 'massageRecommendations', 'weeklyPrograms'
            ]);

            $this->syncInterventionImages($assessment);

            return response()->json([
                'assessment' => $assessment,
                'decision_result' => $decisionResult,
                'global_analysis' => $globalAnalysis,
                'measurements' => $interpretations,
                'symmetry' => $symmetry,
                'overall_interpretation' => $overallInterpretation,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * List all posture assessments across patients, newest first.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PostureAssessment::with(['patient' => fn($q) => $q->select(['id', 'name', 'age', 'gender'])]);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $assessments = $query->orderBy('assessment_date', 'desc')
            ->paginate($request->get('per_page', 50));

        return response()->json($assessments);
    }

    public function show(PostureAssessment $postureAssessment): JsonResponse
    {
        $postureAssessment->load([
            'patient', 'healthScreening',
            'measurements', 'images', 'classifications',
            'exerciseRecommendations', 'massageRecommendations', 'weeklyPrograms',
            'reports'
        ]);

        $this->syncInterventionImages($postureAssessment);

        return response()->json(array_merge($postureAssessment->toArray(), [
            'measurements' => $this->interpretationEngine->enrichMeasurements($postureAssessment),
            // User-facing pattern wording, so the app never renders the stored
            // clinical classification key directly.
            'classifications' => $this->interpretationEngine->enrichClassifications($postureAssessment),
            'classification' => $this->interpretationEngine->classificationSummary($postureAssessment),
            'overall_interpretation' => $this->interpretationEngine->generateOverallInterpretation($postureAssessment),
        ]));
    }

    /**
     * Overlay the current admin image onto an assessment's recommendations.
     *
     * Recommendations copy the image when they are generated, so an image
     * uploaded to Intervention Recommendations afterwards would keep the old
     * value forever. The admin table is the source of truth, so the image is
     * re-resolved on every read.
     */
    private function syncInterventionImages(PostureAssessment $assessment): void
    {
        $postureType = $this->measurementEngine->resolveInterventionPostureType($assessment->posture_classification);
        $ageGroup = $this->measurementEngine->resolveAgeGroup($assessment->patient?->age);

        // The adolescent band is the authored fallback, so it is applied first
        // and the assessment's own band is allowed to overwrite it.
        $bands = $ageGroup === '13-18' ? ['13-18'] : ['13-18', $ageGroup];

        $interventions = PostureIntervention::query()
            ->where('posture_type', $postureType)
            ->whereIn('age_group', $bands)
            ->get(['program', 'title', 'age_group', 'image_path', 'image_url']);

        $images = [];

        foreach ($bands as $band) {
            foreach ($interventions->where('age_group', $band) as $intervention) {
                if (!empty($intervention->image_src)) {
                    $images[$intervention->program . '|' . $intervention->title] = $intervention->image_src;
                }
            }
        }

        foreach ($assessment->exerciseRecommendations as $recommendation) {
            $image = $images['aquatic_exercise|' . $recommendation->exercise_name] ?? null;

            if ($image !== null) {
                $recommendation->image_url = $image;
            }
        }

        foreach ($assessment->massageRecommendations as $recommendation) {
            $image = $images['massage_therapy|' . $recommendation->body_area] ?? null;

            if ($image !== null) {
                $recommendation->image_url = $image;
            }
        }
    }

    /**
     * Normalises the per-image highlight zones coming from the app.
     *
     * The Flutter app sends `images[i][highlights]` as a JSON-encoded array of
     * rects (left/top/width/height in normalized 0..1 coords + type). This
     * accepts either an already-decoded array or a JSON string.
     */
    private function decodeHighlights(mixed $raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode((string) $raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    public function destroy(PostureAssessment $postureAssessment): JsonResponse
    {
        foreach ($postureAssessment->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $postureAssessment->delete();

        return response()->json(['message' => 'Assessment deleted.']);
    }
}
