<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthScreening;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthScreeningController extends Controller
{
    public function store(Request $request, Patient $patient): JsonResponse
    {
        $validated = $request->validate([
            'fear_of_water' => 'boolean',
            'history_of_seizure' => 'boolean',
            'heart_disease' => 'boolean',
            'asthma' => 'boolean',
            'neck_pain' => 'boolean',
            'back_pain' => 'boolean',
            'hip_pain' => 'boolean',
            'can_follow_instruction' => 'boolean',
            'can_stand_independently' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = $patient->id;
        $screening = HealthScreening::create($validated);

        $safetyLevel = $screening->computeSafetyLevel();
        $screening->update(['safety_level' => $safetyLevel]);

        return response()->json($screening, 201);
    }

    public function show(HealthScreening $healthScreening): JsonResponse
    {
        $healthScreening->load('patient');
        return response()->json($healthScreening);
    }

    public function index(Patient $patient): JsonResponse
    {
        $screenings = $patient->healthScreenings()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($screenings);
    }
}
