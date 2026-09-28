<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\Sparisk\SpariskBmiEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct(private SpariskBmiEngine $bmiEngine) {}

    public function index(Request $request): JsonResponse
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if (!$request->filled('archived')) {
            $query->active();
        }

        if ($request->filled('special_needs')) {
            $query->where('special_needs_type', $request->special_needs);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $patients = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($patients);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:5120',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'state' => 'nullable|string|max:100',
            'diagnosis' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'special_needs_type' => ['nullable', Rule::in([
                'autism', 'adhd', 'cerebral_palsy', 'down_syndrome',
                'developmental_delay', 'other', 'none'
            ])],
        ] + $this->neuroProfileRules());

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $validated['user_id'] = $request->user()?->id;

        $patient = Patient::create($validated);

        return response()->json($patient, 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        $patient->load([
            'healthScreenings' => function ($q) {
                $q->latest('created_at');
            },
            'postureAssessments' => function ($q) {
                $q->latest('assessment_date')->limit(10);
            },
        ]);

        return response()->json(array_merge($patient->toArray(), [
            'bmi' => $this->bmiEngine->forPatient($patient),
        ]));
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'photo' => 'nullable|image|max:5120',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'state' => 'nullable|string|max:100',
            'diagnosis' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'special_needs_type' => ['nullable', Rule::in([
                'autism', 'adhd', 'cerebral_palsy', 'down_syndrome',
                'developmental_delay', 'other', 'none'
            ])],
        ] + $this->neuroProfileRules());

        if ($request->hasFile('photo')) {
            if ($patient->photo) {
                Storage::disk('public')->delete($patient->photo);
            }
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient->update($validated);

        return response()->json($patient);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->update(['archived_at' => now()]);

        return response()->json(['message' => 'Patient archived.']);
    }

    public function assessments(Patient $patient): JsonResponse
    {
        $assessments = $patient->postureAssessments()
            ->with(['measurements', 'classifications', 'images'])
            ->orderBy('assessment_date', 'desc')
            ->get();

        return response()->json($assessments);
    }

    /**
     * Validation rules for the Neurodevelopmental Profile. The allowed options
     * come from config('sparisk.neuro_*') so the app, the web form and the API
     * always accept exactly the same values.
     */
    private function neuroProfileRules(): array
    {
        return [
            'neuro_profile' => ['nullable', Rule::in(array_keys(config('sparisk.neuro_profiles')))],
            'neuro_conditions' => 'nullable|array',
            'neuro_conditions.*' => ['string', Rule::in(array_keys(config('sparisk.neuro_conditions')))],
            'neuro_conditions_other' => 'nullable|string|max:255',
        ];
    }
}
