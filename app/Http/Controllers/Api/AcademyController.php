<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use App\Models\AcademyMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $academies = Academy::query()
            ->withCount('members')
            ->orderBy('name')
            ->get();

        return response()->json($academies);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'state' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['owner_id'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('academies', 'public');
        }

        $academy = Academy::create($validated);

        return response()->json($academy, 201);
    }

    public function show(Academy $academy): JsonResponse
    {
        $academy->load([
            'owner',
            'members' => function ($q) { $q->with('patient')->where('active', true); },
        ]);

        $academy->members_count = $academy->members->count();

        return response()->json($academy);
    }

    public function update(Request $request, Academy $academy): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'nullable|string|max:500',
            'state' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('academies', 'public');
        }

        $academy->update($validated);

        return response()->json($academy);
    }

    public function addMember(Request $request, Academy $academy): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'role' => 'in:student,instructor,coach',
        ]);

        $member = AcademyMember::firstOrCreate(
            [
                'academy_id' => $academy->id,
                'patient_id' => $request->patient_id,
            ],
            [
                'role' => $request->role ?? 'student',
                'joined_at' => now(),
                'active' => true,
            ]
        );

        if (!$member->wasRecentlyCreated) {
            $member->update(['active' => true, 'role' => $request->role ?? 'student']);
        }

        return response()->json($member->load('patient'));
    }

    public function removeMember(Academy $academy, AcademyMember $member): JsonResponse
    {
        if ($member->academy_id !== $academy->id) {
            return response()->json(['error' => 'Member does not belong to this academy.'], 422);
        }

        $member->update(['active' => false]);

        return response()->json(['message' => 'Member removed.']);
    }

    public function members(Academy $academy): JsonResponse
    {
        $members = $academy->members()
            ->with('patient')
            ->where('active', true)
            ->get();

        return response()->json($members);
    }

    public function students(Academy $academy): JsonResponse
    {
        $students = $academy->members()
            ->with(['patient' => function ($q) {
                $q->with(['postureAssessments' => function ($q) {
                    $q->latest('assessment_date')->limit(1);
                }]);
            }])
            ->where('active', true)
            ->where('role', 'student')
            ->get();

        return response()->json($students);
    }
}
