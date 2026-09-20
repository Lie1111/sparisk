<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\Sparisk\SpariskBmiEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * BMI calculation for the mobile app and any other API client.
 *
 * The classification itself lives in SpariskBmiEngine, so this endpoint only
 * validates the transport payload and returns the engine's result — the app
 * never re-implements the SATA reference lookup.
 */
class BmiController extends Controller
{
    public function __construct(private SpariskBmiEngine $bmiEngine) {}

    /**
     * Calculate BMI for raw inputs (used for the live preview while a
     * participant's age, gender, height and weight are being entered).
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
        ]);

        return response()->json($this->bmiEngine->calculate(
            $validated['age'] ?? null,
            $validated['gender'] ?? null,
            $validated['height'] ?? null,
            $validated['weight'] ?? null,
        ));
    }

    /**
     * Calculate BMI for a stored participant.
     */
    public function forPatient(Patient $patient): JsonResponse
    {
        return response()->json($this->bmiEngine->forPatient($patient));
    }
}
