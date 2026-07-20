<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\ProgressRecord;
use App\Services\Sparisk\SpariskInterpretationEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function __construct(
        private SpariskInterpretationEngine $interpretationEngine
    ) {}

    /**
     * Compare two assessments and generate progress record.
     */
    public function compare(Request $request): JsonResponse
    {
        $request->validate([
            'from_assessment_id' => 'required|exists:posture_assessments,id',
            'to_assessment_id' => 'required|exists:posture_assessments,id|different:from_assessment_id',
        ]);

        $from = PostureAssessment::with('measurements')->findOrFail($request->from_assessment_id);
        $to = PostureAssessment::with('measurements')->findOrFail($request->to_assessment_id);

        if ($from->patient_id !== $to->patient_id) {
            return response()->json(['error' => 'Assessments must belong to the same patient.'], 422);
        }

        $trend = $this->interpretationEngine->calculateTrend($to, $from);

        $scoreDiff = ($to->overall_score ?? 0) - ($from->overall_score ?? 0);
        $progressLevel = match (true) {
            $scoreDiff >= 10 => 'high_improvement',
            $scoreDiff >= 3 => 'moderate_improvement',
            $scoreDiff >= -3 => 'stable',
            default => 'regression',
        };

        $summary = config("sparisk.clinical_summaries.{$progressLevel}");

        $progress = ProgressRecord::create([
            'patient_id' => $from->patient_id,
            'from_assessment_id' => $from->id,
            'to_assessment_id' => $to->id,
            'improvement_fields' => $trend['improved'],
            'attention_fields' => $trend['need_attention'],
            'overall_progress' => $progressLevel,
            'summary' => $summary,
        ]);

        $to->update(['overall_progress' => $progressLevel]);

        return response()->json([
            'progress' => $progress,
            'trend' => $trend,
            'score_diff' => $scoreDiff,
            'progress_level' => $progressLevel,
        ]);
    }

    /**
     * Get progress timeline for a patient.
     */
    public function timeline(Patient $patient): JsonResponse
    {
        $assessments = $patient->postureAssessments()
            ->with('measurements')
            ->orderBy('assessment_date', 'asc')
            ->get();

        $progressRecords = $patient->progressRecords()
            ->with(['fromAssessment', 'toAssessment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'patient' => $patient->only(['id', 'name']),
            'assessments' => $assessments,
            'progress_records' => $progressRecords,
        ]);
    }

    /**
     * Get trend data for charts.
     */
    public function trends(Patient $patient): JsonResponse
    {
        $assessments = $patient->postureAssessments()
            ->with('measurements')
            ->orderBy('assessment_date', 'asc')
            ->get();

        $trendData = [];
        foreach ($assessments as $assessment) {
            $trendData[] = [
                'time_mark' => $assessment->time_mark,
                'date' => $assessment->assessment_date->format('Y-m-d'),
                'overall_score' => $assessment->overall_score,
                'measurements' => $assessment->measurements->map(fn($m) => [
                    'section' => $m->section,
                    'label' => $m->label,
                    'value' => $m->value,
                    'severity' => $m->severity,
                ]),
            ];
        }

        return response()->json([
            'patient_id' => $patient->id,
            'patient_name' => $patient->name,
            'data_points' => $trendData,
        ]);
    }
}
