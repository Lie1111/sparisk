<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;
use App\Models\Report;
use App\Services\Sparisk\SpariskInterpretationEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private SpariskInterpretationEngine $interpretationEngine) {}

    /**
     * Generate a report for an assessment.
     */
    public function generate(Request $request, PostureAssessment $postureAssessment): JsonResponse
    {
        $request->validate([
            'report_type' => 'required|in:individual,parent,professional,academy',
        ]);

        $assessment = $postureAssessment->load([
            'patient', 'measurements', 'images', 'classifications',
            'exerciseRecommendations', 'massageRecommendations', 'weeklyPrograms'
        ]);

        $reportData = $this->buildReportData($assessment, $request->report_type);

        $report = Report::create([
            'posture_assessment_id' => $assessment->id,
            'patient_id' => $assessment->patient_id,
            'report_type' => $request->report_type,
            'report_data' => $reportData,
            'generated_at' => now(),
        ]);

        return response()->json([
            'report' => $report,
            'data' => $reportData,
        ]);
    }

    /**
     * Download/View a report.
     */
    public function show(Report $report): JsonResponse
    {
        $report->load(['assessment', 'patient']);

        return response()->json($report);
    }

    /**
     * List reports for a patient.
     */
    public function patientReports(Request $request): JsonResponse
    {
        $reports = Report::where('patient_id', $request->patient_id)
            ->with('assessment')
            ->orderBy('generated_at', 'desc')
            ->get();

        return response()->json($reports);
    }

    /**
     * Build structured report data according to SPARISK report format.
     */
    private function buildReportData(PostureAssessment $assessment, string $type): array
    {
        $interpreted = collect($this->interpretationEngine->interpret($assessment))->keyBy('section');

        $viewMeasurements = [];
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $viewMeasurements[$view] = $assessment->measurements
                ->where('view', $view)
                ->values()
                ->map(function (PostureMeasurement $measurement) use ($interpreted) {
                    $details = $interpreted->get($measurement->section);

                    return array_merge($measurement->toArray(), [
                        'alignment_label' => $details['alignment_label'] ?? null,
                        'alignment_color' => $details['alignment_color'] ?? null,
                        'position_note' => $details['position_note'] ?? null,
                        'interpretation' => $details['interpretation'] ?? $measurement->status_text,
                        'review_required' => (bool) $measurement->review_required,
                    ]);
                })
                ->toArray();
        }

        $exercises = $assessment->exerciseRecommendations->map(fn($e) => [
            'name' => $e->exercise_name,
            'level' => $e->program_level,
            'difficulty' => $e->difficulty,
            'duration' => $e->estimated_duration_minutes,
            'instructions' => $e->instructions,
            'stage' => $e->progression_stage,
        ]);

        $massages = $assessment->massageRecommendations->map(fn($m) => [
            'area' => $m->body_area,
            'priority' => $m->priority_stars,
            'duration' => $m->duration_minutes,
            'frequency' => $m->frequency,
            'instructions' => $m->instructions,
        ]);

        $weeklyProgram = $assessment->weeklyPrograms->groupBy('day_of_week');

        return [
            'participant_profile' => [
                'name' => $assessment->patient->name,
                'age' => $assessment->patient->age,
                'gender' => $assessment->patient->gender,
                'height' => $assessment->patient->height,
                'weight' => $assessment->patient->weight,
                'diagnosis' => $assessment->patient->diagnosis,
            ],
            'assessment_info' => [
                'date' => $assessment->assessment_date->format('d/m/Y'),
                'time_mark' => $assessment->time_mark,
                'overall_score' => $assessment->overall_score,
                'overall_status' => $assessment->overall_status,
            ],
            'posture_images' => $assessment->images->map(fn($img) => [
                'view' => $img->view,
                'url' => $img->image_path ? asset('storage/' . $img->image_path) : null,
            ]),
            'measurements_by_view' => $viewMeasurements,
            'posture_classification' => [
                'primary' => $assessment->posture_classification === config('sparisk.unclassified.code')
                    ? config('sparisk.unclassified.display')
                    : $assessment->posture_classification,
                'code' => $assessment->posture_classification,
                'review_status' => $assessment->review_status,
                'suspected_pattern' => $assessment->suspected_pattern,
                'secondary_pattern' => $assessment->secondary_pattern,
                'asymmetry_flag' => (bool) $assessment->asymmetry_flag,
                'confidence_level' => $assessment->confidence_level,
                'details' => $assessment->classifications->toArray(),
            ],
            'symmetry' => $this->interpretationEngine->generateSymmetry($assessment),
            'overall_interpretation' => $this->interpretationEngine->generateOverallInterpretation($assessment),
            'primary_findings' => $assessment->primary_findings ? json_decode($assessment->primary_findings, true) : [],
            'clinical_interpretation' => $assessment->clinical_summary,
            'aquatic_recommendations' => $exercises,
            'massage_recommendations' => $massages,
            'weekly_program' => $weeklyProgram,
            'progress' => [
                'improvement' => $assessment->biggest_improvement ? json_decode($assessment->biggest_improvement, true) : [],
                'attention' => $assessment->need_attention ? json_decode($assessment->need_attention, true) : [],
                'overall' => $assessment->overall_progress,
            ],
            'report_type' => $type,
            'generated_at' => now()->toDateTimeString(),
        ];
    }
}
