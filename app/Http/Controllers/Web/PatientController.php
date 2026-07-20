<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $allowedSorts = ['name', 'age', 'created_at', 'updated_at'];
        $sort = in_array($request->sort, $allowedSorts, true) ? $request->sort : 'updated_at';
        $order = $request->order === 'asc' ? 'asc' : 'desc';

        $query = Patient::query();

        $q = $request->q ?? '';
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('diagnosis', 'like', "%{$q}%")
                    ->orWhere('state', 'like', "%{$q}%");
            });
        }

        if (!$request->filled('archived')) {
            $query->active();
        }

        $patients = $query
            ->orderBy($sort, $order)
            ->paginate($perPage = 25, $columns = ['*'], $pageName = 'patients');

        $patients->setPath('');

        $patients = $patients->through(function (Patient $patient) {
            return [
                'id' => $patient->id,
                'user_id' => $patient->user_id,
                'name' => $patient->name,
                'photo' => $patient->photo ? route('patients.photo', ['path' => $patient->photo]) : null,
                'age' => $patient->age,
                'gender' => $patient->gender,
                'height' => $patient->height,
                'weight' => $patient->weight,
                'state' => $patient->state,
                'diagnosis' => $patient->diagnosis,
                'emergency_contact_name' => $patient->emergency_contact_name,
                'emergency_contact_phone' => $patient->emergency_contact_phone,
                'special_needs_type' => $patient->special_needs_type,
                'assessment_count' => $patient->postureAssessments()->count(),
                'updated_at' => $patient->updated_at,
            ];
        });

        return Inertia::render('patients/index', [
            'patients' => $patients,
            'query' => $request->q ?? '',
        ]);
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'postureAssessments' => function ($q) {
                $q->with(['classifications', 'images'])
                    ->latest('assessment_date');
            },
            'healthScreenings' => function ($q) {
                $q->latest('created_at');
            }
        ]);

        return Inertia::render('patients/show', [
            'patient' => [
                'id' => $patient->id,
                'user_id' => $patient->user_id,
                'name' => $patient->name,
                'photo' => $patient->photo ? route('patients.photo', ['path' => $patient->photo]) : null,
                'age' => $patient->age,
                'gender' => $patient->gender,
                'height' => $patient->height,
                'weight' => $patient->weight,
                'state' => $patient->state,
                'diagnosis' => $patient->diagnosis,
                'emergency_contact_name' => $patient->emergency_contact_name,
                'emergency_contact_phone' => $patient->emergency_contact_phone,
                'special_needs_type' => $patient->special_needs_type,
                'archived_at' => $patient->archived_at,
                'posture_assessments' => $patient->postureAssessments,
                'health_screenings' => $patient->healthScreenings,
                'updated_at' => $patient->updated_at,
                'created_at' => $patient->created_at,
            ],
        ]);
    }

    public function photo(string $path)
    {
        if (str_contains($path, '..')) {
            abort(400);
        }
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        return response()->file(Storage::disk('public')->path($path));
    }

    public function create(Request $request)
    {
        $genders = ['male', 'female', 'other'];
        $needs = ['none', 'autism', 'adhd', 'cerebral_palsy', 'down_syndrome', 'developmental_delay', 'other'];

        try {
            $data = [
                'name' => $request->input('name'),
                'age' => $this->nullIfEmpty($request->input('age')),
                'gender' => $this->nullIfEmpty($request->input('gender')),
                'height' => $this->nullIfEmpty($request->input('height')),
                'weight' => $this->nullIfEmpty($request->input('weight')),
                'state' => $this->nullIfEmpty($request->input('state')),
                'diagnosis' => $this->nullIfEmpty($request->input('diagnosis')),
                'emergency_contact_name' => $this->nullIfEmpty($request->input('emergency_contact_name')),
                'emergency_contact_phone' => $this->nullIfEmpty($request->input('emergency_contact_phone')),
                'special_needs_type' => $this->nullIfEmpty($request->input('special_needs_type')),
            ];

            $validated = validator($data, [
                'name' => ['required', 'string', 'max:255'],
                'age' => ['nullable', 'integer', 'min:0', 'max:150'],
                'gender' => ['nullable', 'string', 'in:'.implode(',', $genders)],
                'height' => ['nullable', 'numeric', 'min:0', 'max:300'],
                'weight' => ['nullable', 'numeric', 'min:0', 'max:500'],
                'state' => ['nullable', 'string', 'max:100'],
                'diagnosis' => ['nullable', 'string', 'max:255'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
                'special_needs_type' => ['nullable', 'string', 'in:'.implode(',', $needs)],
                'photo' => ['nullable', 'image', 'max:5120'],
            ])->validate();

            $patient = Patient::create($validated);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                if ($file && $file->isValid()) {
                    try {
                        Storage::disk('public')->makeDirectory('patients');
                        $path = Storage::disk('public')->putFile('patients', $file);
                        $patient->update(['photo' => $path]);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Photo upload failed: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('patients.index', ['patients' => $request->patients]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Patient create failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create patient.');
        }
    }

    public function update(Request $request)
    {
        $genders = ['male', 'female', 'other'];
        $needs = ['none', 'autism', 'adhd', 'cerebral_palsy', 'down_syndrome', 'developmental_delay', 'other'];

        try {
            $data = [
                'id' => $request->input('id'),
                'name' => $request->input('name'),
                'age' => $this->nullIfEmpty($request->input('age')),
                'gender' => $this->nullIfEmpty($request->input('gender')),
                'height' => $this->nullIfEmpty($request->input('height')),
                'weight' => $this->nullIfEmpty($request->input('weight')),
                'state' => $this->nullIfEmpty($request->input('state')),
                'diagnosis' => $this->nullIfEmpty($request->input('diagnosis')),
                'emergency_contact_name' => $this->nullIfEmpty($request->input('emergency_contact_name')),
                'emergency_contact_phone' => $this->nullIfEmpty($request->input('emergency_contact_phone')),
                'special_needs_type' => $this->nullIfEmpty($request->input('special_needs_type')),
            ];

            $validated = validator($data, [
                'id' => ['required', 'integer', 'exists:patients,id'],
                'name' => ['required', 'string', 'max:255'],
                'age' => ['nullable', 'integer', 'min:0', 'max:150'],
                'gender' => ['nullable', 'string', 'in:'.implode(',', $genders)],
                'height' => ['nullable', 'numeric', 'min:0', 'max:300'],
                'weight' => ['nullable', 'numeric', 'min:0', 'max:500'],
                'state' => ['nullable', 'string', 'max:100'],
                'diagnosis' => ['nullable', 'string', 'max:255'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
                'special_needs_type' => ['nullable', 'string', 'in:'.implode(',', $needs)],
                'photo' => ['nullable', 'image', 'max:5120'],
            ])->validate();

            $patient = Patient::findOrFail($validated['id']);
            unset($validated['id']);

            $patient->update($validated);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                if ($file && $file->isValid()) {
                    try {
                        Storage::disk('public')->makeDirectory('patients');
                        if ($patient->photo) {
                            Storage::disk('public')->delete($patient->photo);
                        }
                        $path = Storage::disk('public')->putFile('patients', $file);
                        $patient->update(['photo' => $path]);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Photo upload failed: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('patients.index', ['patients' => $request->patients]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Patient update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update patient.');
        }
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:patients,id'],
        ]);

        $patient = Patient::findOrFail($validated['id']);

        DB::transaction(function () use ($patient) {
            if ($patient->photo) {
                Storage::disk('public')->delete($patient->photo);
            }
            $patient->postureAssessments()->delete();
            $patient->delete();
        });

        return redirect()->route('patients.index', ['patients' => $request->patients]);
    }

    private function nullIfEmpty(mixed $value): mixed
    {
        if ($value === 'null' || $value === '' || $value === null) {
            return null;
        }
        return $value;
    }
}
