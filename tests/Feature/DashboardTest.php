<?php

use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertOk();
});

test('the dashboard reports the captured screening data', function () {
    $user = User::factory()->create();
    $patient = Patient::create(['name' => 'Ali', 'age' => 10]);

    PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'overall_score' => 46,
        'overall_status' => 'NEEDS IMPROVEMENT',
        'review_status' => 'MEASUREMENT_REVIEW_REQUIRED',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('stats.patients.total', 1)
            ->where('stats.assessments.total', 1)
            ->where('stats.assessments.average_score', fn ($score) => (float) $score === 46.0)
            ->where('stats.review.outstanding', 1)
            ->has('trend', 6)
            ->has('status_mix.buckets', 4)
            ->has('recent_assessments', 1)
            ->has('needs_attention', 1)
        );
});

test('the dashboard renders before any screening is captured', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('stats.patients.total', 0)
            ->where('stats.assessments.total', 0)
            ->has('recent_assessments', 0)
            ->has('needs_attention', 0)
            ->has('problem_areas', 0)
        );
});

test('the dashboard leaves participants who are on track out of the attention list', function () {
    $patient = Patient::create(['name' => 'Siti', 'age' => 12]);

    PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'overall_score' => 92,
        'overall_status' => 'GOOD',
    ]);

    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertInertia(fn ($page) => $page->has('needs_attention', 0));
});
