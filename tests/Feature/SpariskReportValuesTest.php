<?php

use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\User;
use App\Services\Sparisk\SpariskDecisionEngine;
use App\Services\Sparisk\SpariskInterpretationEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;
use Database\Seeders\PostureSettingSeeder;

/**
 * Regression values from the SATA report the client reviewed (33 markers,
 * Detailed Angle Breakdown, pages 5–8). The measured angles below are the
 * values the fixed definitions produce for the same subject (Abu, 11, male):
 *
 *   - every side-view sign is anterior-positive, so the left profile (D)
 *     reports the same direction as the right profile (C);
 *   - the shared clinical angles come from the right-side reference view;
 *   - B8 is the mean foot progression (A10/A11), not the ankle→heel angle.
 *
 * Each row asserts deviation = measured − reference against the seeded
 * reference and the On Point / Near Point / Off Point band it lands in.
 */
beforeEach(function () {
    $this->seed(PostureSettingSeeder::class);
});

function sparisk_report_assessment(array $values): PostureAssessment
{
    $patient = Patient::create(['name' => 'Abu', 'age' => 11, 'gender' => 'male']);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
        'measurement_version' => 2,
    ]);

    app(SpariskMeasurementEngine::class)->process($values, $assessment);

    return $assessment->fresh();
}

dataset('abu report markers', [
    // Front view (A)
    'A1' => ['A1', -1.18, 0.00, -1.18, 'on_point'],
    'A2' => ['A2', 3.59, 0.00, 3.59, 'on_point'],
    'A3' => ['A3', -2.11, 0.00, -2.11, 'on_point'],
    'A4' => ['A4', -3.18, 0.00, -3.18, 'near_point'],
    'A5' => ['A5', -1.44, 0.00, -1.44, 'on_point'],
    'A6' => ['A6', -1.96, 0.00, -1.96, 'on_point'],
    'A7' => ['A7', -0.67, 0.00, -0.67, 'on_point'],
    'A8' => ['A8', -5.26, 0.00, -5.26, 'near_point'],
    'A9' => ['A9', -3.99, 0.00, -3.99, 'on_point'],
    'A10' => ['A10', 11.01, 7.00, 4.01, 'on_point'],
    'A11' => ['A11', 0.32, 7.00, -6.68, 'on_point'],
    // Back view (B)
    'B1' => ['B1', 0.49, 0.00, 0.49, 'on_point'],
    'B2' => ['B2', -1.05, 0.00, -1.05, 'on_point'],
    'B3' => ['B3', -0.32, 0.00, -0.32, 'on_point'],
    'B4' => ['B4', 4.25, 0.00, 4.25, 'near_point'],
    'B5' => ['B5', 1.38, 0.00, 1.38, 'on_point'],
    'B6' => ['B6', 0.43, 0.00, 0.43, 'on_point'],
    'B7' => ['B7', -4.47, 0.00, -4.47, 'on_point'],
    'B8' => ['B8', 5.67, 7.00, -1.33, 'on_point'],
    // Right side (C)
    'C1' => ['C1', 1.89, 0.00, 1.89, 'on_point'],
    'C2' => ['C2', 18.19, 0.00, 18.19, 'off_point'],
    'C3' => ['C3', -1.40, 0.00, -1.40, 'on_point'],
    'C4' => ['C4', 0.49, 0.00, 0.49, 'on_point'],
    'C5' => ['C5', -18.10, -7.70, -10.40, 'off_point'],
    'C6' => ['C6', 9.59, 0.00, 9.59, 'near_point'],
    'C7' => ['C7', 0.60, 0.00, 0.60, 'on_point'],
    // Left side (D) — same anatomical sign as the right side
    'D1' => ['D1', 2.65, 0.00, 2.65, 'on_point'],
    'D2' => ['D2', 4.17, 0.00, 4.17, 'on_point'],
    'D3' => ['D3', 0.61, 0.00, 0.61, 'on_point'],
    'D4' => ['D4', 3.71, 0.00, 3.71, 'on_point'],
    'D5' => ['D5', -17.02, -7.70, -9.32, 'off_point'],
    'D6' => ['D6', 12.47, 0.00, 12.47, 'off_point'],
    'D7' => ['D7', -0.79, 0.00, -0.79, 'on_point'],
]);

const ABU_VALUES = [
    'A1' => -1.18, 'A2' => 3.59, 'A3' => -2.11, 'A4' => -3.18, 'A5' => -1.44,
    'A6' => -1.96, 'A7' => -0.67, 'A8' => -5.26, 'A9' => -3.99, 'A10' => 11.01, 'A11' => 0.32,
    'B1' => 0.49, 'B2' => -1.05, 'B3' => -0.32, 'B4' => 4.25, 'B5' => 1.38, 'B6' => 0.43,
    'B7' => -4.47, 'B8' => 5.67,
    'C1' => 1.89, 'C2' => 18.19, 'C3' => -1.40, 'C4' => 0.49, 'C5' => -18.10, 'C6' => 9.59, 'C7' => 0.60,
    'D1' => 2.65, 'D2' => 4.17, 'D3' => 0.61, 'D4' => 3.71, 'D5' => -17.02, 'D6' => 12.47, 'D7' => -0.79,
    'side_cva' => 35.81, 'side_kyphosis' => 57.59, 'side_lordosis' => 45.11,
];

it('reproduces the report deviation and status for every marker', function (string $section, float $measured, float $reference, float $deviation, string $status) {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    $row = $assessment->measurements()->where('section', $section)->first();

    expect($row)->not->toBeNull()
        ->and((float) $row->measured_angle)->toBe($measured)
        ->and((float) $row->reference_value)->toBe($reference)
        ->and(round((float) $row->deviation_angle, 2))->toBe($deviation)
        ->and($row->status_code)->toBe($status);
})->with('abu report markers');

it('uses a zero reference for the shoulder and pelvic sagittal definitions', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);

    // The published 69.2° shoulder and 12.6° pelvic-tilt norms describe a
    // different axis; applying them produced the −70.6° shoulders and the
    // false Flatback. Both now use the definitional neutral on their own axis.
    foreach (['C3', 'D3', 'C4', 'D4'] as $section) {
        $row = $assessment->measurements()->where('section', $section)->first();
        expect((float) $row->reference_value)->toBe(0.0)
            ->and($row->reference_level)->toBe('sata_validated');
    }

    $b8 = $assessment->measurements()->where('section', 'B8')->first();
    expect((float) $b8->reference_value)->toBe(7.0)
        ->and(app(SpariskMeasurementEngine::class)->toleranceFor($b8))->toBe(10.0);
});

it('classifies the corrected report as Kyphosis instead of Flatback', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);

    $result = app(SpariskDecisionEngine::class)->decide($assessment);
    $assessment->refresh();

    expect($result['posture_classification'])->toBe('Kyphosis')
        ->and($assessment->posture_classification)->toBe('Kyphosis')
        ->and($assessment->review_status)->toBeNull();

    // Flatback must not match at all: the pelvic axis is on point and the
    // thoracic curve is ABOVE target, which is the Flatback exclusion.
    $flatback = collect($result['decisions'])->firstWhere('rule_id', 'FLATBACK');
    expect($flatback === null || $flatback['matched'] === false)->toBeTrue();

    $kyphosis = collect($result['decisions'])->firstWhere('rule_id', 'KYPHOSIS');
    expect($kyphosis['status'])->toBe('confirmed')
        ->and($kyphosis['matched_sections'])->toContain('side_kyphosis')
        ->and($kyphosis['matched_sections'])->toContain('side_cva');

    // The report hierarchy: confirmed primary = Kyphosis, confirmed secondary
    // = Flexed Knee, and the forward-head reading is only a suspicion.
    expect($result['suspected_pattern'])->toBe('MODERATE_FORWARD_HEAD')
        ->and($result['secondary_pattern'])->toBe('MODERATE_FLEXED_KNEE');

    $summary = app(SpariskInterpretationEngine::class)->classificationSummary($assessment);

    expect($summary['main_finding'])->toContain('Kyphotic Posture')
        ->and($summary['primary_label'])->toBe('Possible Kyphotic Pattern');
});

it('prints the corrected shoulder deviation and main finding in the report', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $service = app(\App\Services\Sparisk\SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);
    $data = $build->invoke($service, $assessment->fresh(), true);

    $c3 = collect($data['views']['right_side']['rows'])->firstWhere('section', 'C3');
    $d3 = collect($data['views']['left_side']['rows'])->firstWhere('section', 'D3');
    $c4 = collect($data['views']['right_side']['rows'])->firstWhere('section', 'C4');
    $b8 = collect($data['views']['back']['rows'])->firstWhere('section', 'B8');

    expect($c3['deviation'])->toBe('-1.4')
        ->and($c3['reference'])->toBe('0')
        ->and($c3['tolerance'])->toBe('5')
        ->and($d3['deviation'])->toBe('+0.6')
        ->and($c4['deviation'])->toBe('+0.5')
        ->and($b8['measured'])->toBe('5.7')
        ->and($b8['reference'])->toBe('7')
        ->and($data['classification']['main_finding'])->toContain('Kyphotic Posture');
});

it('does not print the point-band threshold rule anywhere in the pdf', function (bool $extended) {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $service = app(\App\Services\Sparisk\SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);
    $data = $build->invoke($service, $assessment->fresh(), $extended);

    $html = view('pdf.assessment-report', $data)->render();

    // The client asked for the rule wording and the per-row helper lines to be
    // taken out of the report: no threshold sentence, no tolerance figure, no
    // axis/sign annotation and no inline "Measured · Ref" line.
    expect($html)->not->toContain(
        'tolerance',
        'Threshold rule',
        'threshold rule',
        'On Point:',
        '× tolerance',
        'Axis:',
        '+ =',
        '±',
        '· Ref',
    );
})->with([true, false]);

it('adds a note column after the status in the compact photo tables', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $service = app(\App\Services\Sparisk\SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);
    $data = $build->invoke($service, $assessment->fresh(), false);

    $html = view('pdf.assessment-report', $data)->render();

    // One compact table per view: Marker · Section · Deviation · Status · Note.
    expect(substr_count($html, '<th class="c-corr">Status</th>'))->toBe(4)
        ->and(substr_count($html, '<th>Note</th>'))->toBeGreaterThanOrEqual(4);
});

it('hides the standalone angle breakdown but keeps the photo tables', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $service = app(\App\Services\Sparisk\SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);

    foreach ([true, false] as $extended) {
        $data = $build->invoke($service, $assessment->fresh(), $extended);
        $html = view('pdf.assessment-report', $data)->render();

        // The standalone breakdown table is hidden at the client's request;
        // the four photo plates (image beside the compact table) stay.
        expect($html)->not->toContain('Detailed Angle Breakdown')
            ->and($html)->not->toContain('— Angle Breakdown')
            ->and(substr_count($html, '<th class="c-corr">Status</th>'))->toBe(4)
            ->and(substr_count($html, '<div class="plate-section">'))->toBe(4);
    }
});

it('does not print a confidence card in the classification summary', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $service = app(\App\Services\Sparisk\SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);
    $data = $build->invoke($service, $assessment->fresh(), false);

    $html = view('pdf.assessment-report', $data)->render();

    // The client asked for the Confidence card to be removed from the report.
    expect($html)->not->toContain('Confidence')
        ->and($html)->not->toContain('High Confidence')
        ->and(substr_count($html, '<span class="summary-label">'))->toBe(5);
});

it('uses plain-english finding labels that point to the body part', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    // Every stored sagittal measurement carries a body-location label, so the
    // report can never print the old clinical jargon.
    $labels = $assessment->measurements()->pluck('label');

    expect($labels)->toContain('Upper Back Curve')
        ->and($labels)->toContain('Lower Back Curve')
        ->and($labels)->toContain('Lower Leg Alignment')
        ->and($labels)->not->toContain('Upper-Back Posture Indicator')
        ->and($labels)->not->toContain('Lower-Back Posture Indicator')
        ->and($labels)->not->toContain('Tibia Alignment');
});

it('exposes the shared thresholds and definitions to the app', function () {
    Laravel\Sanctum\Sanctum::actingAs(User::factory()->create());

    $this->getJson('/api/posture-settings')
        ->assertOk()
        ->assertJsonPath('deviation_bands.on_point', 1)
        ->assertJsonPath('deviation_bands.near_point', 2)
        ->assertJsonPath('deviation_rule', fn ($value) => str_contains((string) $value, '2 × tolerance'))
        ->assertJsonPath('measurement_definitions.C3.axis', 'vertical plumb — acromion over the hip')
        ->assertJsonPath('measurement_definitions.C4.positive', 'hip anterior to the ankle')
        ->assertJsonPath('measurement_definitions.B8.axis', 'forward axis — foot progression (both feet)');
});

it('returns the corrected main finding in the save response the app shows', function () {
    $patient = Patient::create(['name' => 'Abu', 'age' => 11, 'gender' => 'male']);

    Laravel\Sanctum\Sanctum::actingAs(User::factory()->create());

    $measurements = collect(ABU_VALUES)
        ->map(fn ($value, $section) => ['section' => $section, 'value' => $value])
        ->values()
        ->all();

    $this->postJson("/api/patients/{$patient->id}/assessments", [
        'assessment_date' => now()->toDateString(),
        'measurement_version' => 2,
        'measurements' => $measurements,
    ])
        ->assertCreated()
        ->assertJsonPath('assessment.classification.primary_label', 'Possible Kyphotic Pattern')
        ->assertJsonPath('assessment.measurements', function ($rows) {
            $c3 = collect($rows)->firstWhere('section', 'C3');

            return (float) $c3['reference_value'] === 0.0
                && (float) $c3['tolerance'] === 5.0
                && $c3['definition']['axis'] === 'vertical plumb — acromion over the hip';
        });
});

it('renders the corrected result and threshold rule on the web assessment page', function () {
    $assessment = sparisk_report_assessment(ABU_VALUES);
    app(SpariskDecisionEngine::class)->decide($assessment);

    $this->actingAs(User::factory()->create())
        ->get("/assessments/{$assessment->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('assessments/show')
            ->where('classification.primary_label', 'Possible Kyphotic Pattern')
            ->where('classification.main_finding', fn ($value) => str_contains((string) $value, 'Kyphotic Posture'))
            ->where('deviationRule', fn ($value) => str_contains((string) $value, '1 × tolerance'))
            ->where('viewMeasurements.right_side', function ($rows) {
                $c3 = collect($rows)->firstWhere('section', 'C3');

                return (float) $c3['reference_value'] === 0.0
                    && (float) $c3['tolerance'] === 5.0
                    && $c3['definition']['axis'] === 'vertical plumb — acromion over the hip';
            })
        );
});
