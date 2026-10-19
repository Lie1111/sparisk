<?php

use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\PostureMarker;
use App\Models\User;
use App\Services\Sparisk\SpariskInterpretationEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;
use Database\Seeders\PostureMarkerSeeder;
use Database\Seeders\PostureSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function sparisk_marker(string $severity, string $type): PostureMarker
{
    return PostureMarker::create([
        'view' => 'front',
        'gender' => 'male',
        'severity' => $severity,
        'type' => $type,
        'label' => 'Neck / SCM',
        'muscle' => 'Upper Trapezius / SCM',
        'x' => 0.5,
        'y' => 0.1,
    ]);
}

it('prints one label per marker, driven by the severity band', function () {
    expect(sparisk_marker('normal', 'tight')->status_label)->toBe('Normal')
        ->and(sparisk_marker('normal', 'weak')->status_label)->toBe('Normal')
        ->and(sparisk_marker('moderate', 'tight')->status_label)->toBe('Tight')
        ->and(sparisk_marker('severe', 'tight')->status_label)->toBe('Severe');
});

it('never calls a weak muscle tight', function () {
    // Core, Glutes, Lats and the deep abdominals are weak muscles and also
    // appear in the moderate / severe sets; the label must not contradict them.
    expect(sparisk_marker('moderate', 'weak')->status_label)->toBe('Weak')
        ->and(sparisk_marker('severe', 'weak')->status_label)->toBe('Severe');
});

it('exposes the label to the admin page and the mobile app', function () {
    $marker = sparisk_marker('moderate', 'tight');

    expect($marker->toArray())->toHaveKey('status_label', 'Tight');

    $grouped = PostureMarker::groupedByView('male', null, 'moderate');

    expect($grouped['front'][0]['status_label'])->toBe('Tight')
        ->and($grouped['front'][0])->toHaveKey('regions');
});

it('maps every muscle to the region that grades it', function () {
    $this->seed(PostureMarkerSeeder::class);

    $front = PostureMarker::where('view', 'front')->where('gender', 'male')
        ->where('severity', 'normal')->whereNull('condition')->get()->keyBy('label');

    expect($front['Calf L']->regions)->toBe(['lower_limb'])
        ->and($front['Neck / SCM']->regions)->toBe(['head'])
        ->and($front['Chest L']->regions)->toBe(['shoulder', 'thoracic'])
        // The biceps has no angle of its own, so it keeps its set's band.
        ->and($front['Biceps L']->regions)->toBe([])
        ->and($front['Biceps L']->toArray())->toHaveKey('regions');
});

it('labels every measurement with its body region for the muscle colouring', function () {
    $this->seed(PostureSettingSeeder::class);

    $patient = Patient::create(['name' => 'Region Patient', 'age' => 30]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    app(SpariskMeasurementEngine::class)->process(['A2' => 1.0, 'A8' => 3.0], $assessment);

    $payload = collect(app(SpariskInterpretationEngine::class)->interpret($assessment))->keyBy('section');

    expect($payload['A2']['region'])->toBe('head')
        ->and($payload['A8']['region'])->toBe('lower_limb');
});

it('carries the body region on the payload the app reads back', function () {
    $this->seed(PostureSettingSeeder::class);

    $patient = Patient::create(['name' => 'Region Patient', 'age' => 30]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    app(SpariskMeasurementEngine::class)->process(['A2' => 1.0, 'A8' => 3.0], $assessment);

    $engine = app(SpariskInterpretationEngine::class);

    // The app opens an assessment through show(), which reads the enriched
    // payload. `region` is what lets a muscle take the band of its own
    // findings, so losing it here left every marker reading "Normal".
    $enriched = collect($engine->enrichMeasurements($assessment))->keyBy('section');

    expect($enriched['A2'])->toHaveKey('region')
        ->and($enriched['A2']['region'])->toBe('head')
        ->and($enriched['A8']['region'])->toBe('lower_limb');

    // The grouped view feeds the web pages from the same builder.
    $byView = $engine->measurementsByView($assessment);

    expect($byView['front'][0])->toHaveKey('region');
});

it('serves the body region on the assessment the app opens', function () {
    $this->seed(PostureSettingSeeder::class);
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create(['name' => 'Region Patient', 'age' => 30]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    app(SpariskMeasurementEngine::class)->process(['A2' => 1.0, 'A8' => 3.0], $assessment);

    // GET /api/assessments/{id} is what the app calls when a patient's result
    // is opened. Without `region` on these rows the character view has nothing
    // to grade a muscle by and every marker falls back to "Normal".
    $rows = collect($this->getJson("/api/assessments/{$assessment->id}")
        ->assertOk()
        ->json('measurements'))->keyBy('section');

    expect($rows['A2'])->toHaveKey('region')
        ->and($rows['A2']['region'])->toBe('head')
        ->and($rows['A8']['region'])->toBe('lower_limb');
});
