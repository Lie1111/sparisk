<?php

use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\SkillAssessment;
use App\Models\User;
use App\Services\Sparisk\AquaticSkillsProgressService;
use App\Services\Sparisk\SpariskReportPdfService;
use Database\Seeders\PostureSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function sparisk_answers(int $yesCount): array
{
    $answers = [];
    foreach (array_keys(config('sparisk.aquatic_skills.items')) as $number) {
        $answers[$number] = $number <= $yesCount;
    }

    return $answers;
}

it('scores one point per item answered yes', function () {
    $scored = SkillAssessment::score(sparisk_answers(30));

    expect($scored['total_score'])->toBe(30)
        ->and($scored['max_score'])->toBe(35);
});

it('never counts a missing answer as an achievement', function () {
    // The paper form has only yes and no, so anything absent is a no.
    $scored = SkillAssessment::score([1 => true, 2 => true]);

    expect($scored['answers'][1])->toBeTrue()
        ->and($scored['answers'][2])->toBeTrue()
        ->and($scored['answers'][3])->toBeFalse()
        ->and($scored['total_score'])->toBe(2)
        ->and($scored['max_score'])->toBe(35);
});

it('groups the score into the four categories', function () {
    $scored = SkillAssessment::score(sparisk_answers(35));
    $byKey = collect($scored['category_scores'])->keyBy('key');

    // 9 / 6 / 9 / 11 items — the uneven split the percentage view exists for.
    expect($byKey['readiness']['total'])->toBe(9)
        ->and($byKey['basic_control']['total'])->toBe(6)
        ->and($byKey['basic_swimming']['total'])->toBe(9)
        ->and($byKey['advanced']['total'])->toBe(11)
        ->and($byKey['readiness']['achieved'])->toBe(9)
        ->and($byKey['advanced']['achieved'])->toBe(11);
});

it('scores a category out of its own items, not the whole checklist', function () {
    // All six control items achieved but the rest missed: the category is full
    // marks even though the overall score is low.
    $answers = [];
    foreach (array_keys(config('sparisk.aquatic_skills.items')) as $number) {
        $answers[$number] = false;
    }
    foreach (range(10, 15) as $number) {
        $answers[$number] = true;
    }

    $assessment = new SkillAssessment([
        'answers' => $answers,
        'total_score' => 6,
        'max_score' => 35,
        'category_scores' => SkillAssessment::score($answers)['category_scores'],
    ]);

    $byKey = collect($assessment->categoryPercentages())->keyBy('key');

    expect($byKey['basic_control']['percentage'])->toBe(100.0)
        ->and($byKey['readiness']['percentage'])->toBe(0.0)
        ->and($assessment->percentage)->toBe(17.1);
});

it('saves a sitting through the API and returns the score', function () {
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create(['name' => 'Skills Patient', 'age' => 10]);

    $response = $this->postJson("/api/patients/{$patient->id}/skill-assessments", [
        'answers' => sparisk_answers(30),
        'assessed_on' => '2026-10-19',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.total_score', 30)
        ->assertJsonPath('data.max_score', 35)
        ->assertJsonPath('data.percentage', 85.7);

    expect($patient->skillAssessments()->count())->toBe(1);
});

it('refuses a sitting with items left blank', function () {
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create(['name' => 'Skills Patient', 'age' => 10]);

    $answers = sparisk_answers(5);
    unset($answers[30], $answers[31]);

    $this->postJson("/api/patients/{$patient->id}/skill-assessments", ['answers' => $answers])
        ->assertStatus(422)
        ->assertJsonPath('code', 'incomplete_checklist')
        ->assertJsonPath('missing', [30, 31]);

    expect($patient->skillAssessments()->count())->toBe(0);
});

it('serves the checklist wording the app renders', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/skill-assessment/catalogue')
        ->assertOk()
        ->assertJsonPath('max_score', 35)
        ->assertJsonCount(35, 'items')
        ->assertJsonCount(4, 'categories')
        ->assertJsonPath('items.0.number', 1)
        ->assertJsonPath('items.34.number', 35);
});

it('builds the three dashboard series across sessions', function () {
    $patient = Patient::create(['name' => 'Progress Patient', 'age' => 10]);

    // Baseline of 9, then 21 more items at the second sitting.
    $first = SkillAssessment::score(sparisk_answers(9));
    $second = SkillAssessment::score(sparisk_answers(30));

    $patient->skillAssessments()->create($first + ['assessed_on' => '2026-08-01']);
    $patient->skillAssessments()->create($second + ['assessed_on' => '2026-10-01']);

    $dashboard = app(AquaticSkillsProgressService::class)->forPatient($patient->fresh());

    // 1. the line: one point per session, baseline first
    expect($dashboard['sessions'])->toHaveCount(2)
        ->and($dashboard['sessions'][0]['label'])->toBe('Baseline')
        ->and($dashboard['sessions'][0]['percentage'])->toBe(25.7)
        ->and($dashboard['sessions'][1]['label'])->toBe('Session 1')
        ->and($dashboard['sessions'][1]['percentage'])->toBe(85.7);

    // 2. the bars: each category out of its own items. 30 yes answers covers
    // all of readiness / control / basic swimming but only 25-30 of the eleven
    // advanced items, so the longest category reads lowest.
    $bars = collect($dashboard['categories'])->keyBy('key');
    expect($bars)->toHaveCount(4)
        ->and($bars['readiness']['percentage'])->toBe(100.0)
        ->and($bars['advanced']['percentage'])->toBe(54.5)
        ->and($bars['advanced']['total'])->toBe(11);

    // 3. the headline percentage and the movement from baseline
    expect($dashboard['current']['score'])->toBe(30)
        ->and($dashboard['current']['max'])->toBe(35)
        ->and($dashboard['current']['percentage'])->toBe(85.7)
        ->and($dashboard['current']['missed'])->toBe(5)
        ->and($dashboard['improvement']['items'])->toBe(21)
        ->and($dashboard['improvement']['percentage_points'])->toBe(60.0);
});

it('has no comparison to make from a single sitting', function () {
    $patient = Patient::create(['name' => 'New Patient', 'age' => 10]);
    $patient->skillAssessments()->create(
        SkillAssessment::score(sparisk_answers(12)) + ['assessed_on' => '2026-10-01']
    );

    $dashboard = app(AquaticSkillsProgressService::class)->forPatient($patient->fresh());

    expect($dashboard['sessions'])->toHaveCount(1)
        ->and($dashboard['sessions'][0]['label'])->toBe('Baseline')
        ->and($dashboard['improvement']['has_comparison'])->toBeFalse()
        ->and($dashboard['improvement']['items'])->toBeNull();
});

it('renders an empty dashboard for a patient with no sittings', function () {
    $patient = Patient::create(['name' => 'Untested Patient', 'age' => 10]);

    $dashboard = app(AquaticSkillsProgressService::class)->forPatient($patient);

    expect($dashboard['sessions'])->toBe([])
        ->and($dashboard['current'])->toBeNull()
        ->and($dashboard['categories'])->toHaveCount(4)
        ->and(collect($dashboard['categories'])->sum('total'))->toBe(35);
});

/** A patient with N skill sittings, plus a posture assessment to hang a report on. */
function aquatic_report_patient(int $sittings): PostureAssessment
{
    $patient = Patient::create(['name' => 'Skills Report Patient', 'age' => 10]);

    foreach (array_slice([9, 30, 12], 0, $sittings) as $index => $yesCount) {
        $patient->skillAssessments()->create(
            SkillAssessment::score(sparisk_answers($yesCount)) + ['assessed_on' => '2026-0'.($index + 7).'-01']
        );
    }

    return PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => '2026-10-01',
        'time_mark' => 'TM1',
    ])->fresh();
}

/** The data the PDF template renders, built through the real service. */
function aquatic_report_data(PostureAssessment $assessment): array
{
    $service = app(SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);

    return $build->invoke($service, $assessment, false);
}

it('passes the student progress dashboard to the patient page', function () {
    $user = User::factory()->create();
    $patient = aquatic_report_patient(2)->patient;

    $this->actingAs($user)
        ->get("/patients/{$patient->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('patients/show')
            ->has('skillsProgress.sessions', 2)
            ->where('skillsProgress.sessions.0.label', 'Baseline')
            ->where('skillsProgress.sessions.1.label', 'Session 1')
            ->where('skillsProgress.sessions.1.percentage', 85.7)
            ->where('skillsProgress.current.score', 30)
            ->has('skillsProgress.categories', 4)
            ->where('skillsProgress.improvement.has_comparison', true)
            ->where('skillsProgress.improvement.items', 21));
});

it('renders the three progress charts into the assessment report', function () {
    $this->seed(PostureSettingSeeder::class);

    $data = aquatic_report_data(aquatic_report_patient(3));

    expect($data['skills']['sessions'])->toHaveCount(3)
        ->and($data['skills']['chart']['uri'])->toStartWith('data:image/svg+xml;base64,');

    $svg = base64_decode(substr($data['skills']['chart']['uri'], strlen('data:image/svg+xml;base64,')));

    // The line chart is a real polyline with one marker per sitting, not a
    // placeholder image.
    expect($svg)->toContain('<polyline')
        ->toContain('<circle')
        ->toContain('Baseline')
        ->toContain('Session 2');

    $html = view('pdf.assessment-report', $data)->render();

    expect($html)
        ->toContain('Student Progress Dashboard')
        ->toContain('1. Overall progress')
        ->toContain('2. Progress by Assessment Category')
        ->toContain('3. Overall score as a percentage')
        ->toContain('data:image/svg+xml;base64,')
        ->toContain('skill-bar-fill')
        ->toContain('skill-score-fill');
});

it('does not invent a comparison from a single sitting in the report', function () {
    $this->seed(PostureSettingSeeder::class);

    $data = aquatic_report_data(aquatic_report_patient(1));
    $html = view('pdf.assessment-report', $data)->render();
    $svg = base64_decode(substr($data['skills']['chart']['uri'], strlen('data:image/svg+xml;base64,')));

    expect($html)->toContain('First sitting recorded')
        ->not->toContain('+0 items');

    // With one point there is no line to draw, only the marker.
    expect($svg)->toContain('<circle')
        ->not->toContain('<polyline');
});

it('omits the skills dashboard from the report when the patient has no sittings', function () {
    $this->seed(PostureSettingSeeder::class);

    $data = aquatic_report_data(aquatic_report_patient(0));
    $html = view('pdf.assessment-report', $data)->render();

    expect($data['skills']['sessions'])->toBe([])
        ->and($data['skills']['chart'])->toBeNull()
        ->and($html)->not->toContain('Student Progress Dashboard');
});
