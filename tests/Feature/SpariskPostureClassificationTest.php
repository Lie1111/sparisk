<?php

use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Services\Sparisk\SpariskDecisionEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;

/**
 * Build an assessment from raw landmark angles and run the measurement engine.
 */
function sparisk_assessment(array $landmarks, int $age = 30): PostureAssessment
{
    $patient = Patient::create(['name' => 'Test Patient', 'age' => $age]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    app(SpariskMeasurementEngine::class)->process($landmarks, $assessment);

    return $assessment->fresh();
}

it('normalises an orientation angle near -180 to a near-zero deviation', function () {
    $engine = app(SpariskMeasurementEngine::class);
    $def = config('sparisk.measurements.A3');

    expect($engine->normaliseAngle(-179.97, $def))->toBe(0.03)
        ->and($engine->normaliseAngle(0.03, $def))->toBe(0.03)
        ->and($engine->normaliseAngle(179.97, $def))->toBe(-0.03);
});

it('does not treat a near-180 front-view shoulder reading as a severe deviation', function () {
    $assessment = sparisk_assessment(['A3' => -179.97]);
    $measurement = $assessment->measurements()->where('section', 'A3')->first();

    expect($measurement->alignment_status)->toBe('normal')
        ->and((float) $measurement->deviation)->toBe(0.03)
        ->and($measurement->review_required)->toBeFalse()
        ->and($measurement->status_text)->toBe(config('sparisk.alignment_status_descriptions.normal'));
});

it('flags a measurement that cannot be normalised for review instead of scoring it', function () {
    $assessment = sparisk_assessment(['A1' => 999.0]);
    $measurement = $assessment->measurements()->where('section', 'A1')->first();

    expect($measurement->review_required)->toBeTrue()
        ->and($measurement->alignment_status)->toBe('review')
        ->and($measurement->deviation)->toBeNull()
        ->and((float) $measurement->value)->toBe(999.0);
});

it('returns UNCLASSIFIED with measurement review required when the pattern is not confirmable', function () {
    $assessment = sparisk_assessment([
        'A1' => 0.5, 'A2' => 0.4, 'A3' => 6.0, 'A4' => 0.5, 'A5' => 0.5, 'A6' => 0.5,
        'A7' => 0.8, 'A8' => -7.0, 'A9' => -1.0, 'A10' => 3.0, 'A11' => -3.0,
        'C3' => 2.0, 'C4' => 8.0, 'C5' => 2.0,
        'D3' => 1.0, 'D4' => 1.0,
        'side_cva' => 50.0, 'side_kyphosis' => 40.0, 'side_lordosis' => 45.0,
    ]);

    $result = app(SpariskDecisionEngine::class)->decide($assessment);

    expect($result['posture_classification'])->toBe('UNCLASSIFIED')
        ->and($result['posture_classification_display'])->toBe('Unclassified – Measurement Review Required')
        ->and($result['review_status'])->toBe('MEASUREMENT_REVIEW_REQUIRED')
        ->and($result['suspected_pattern'])->toBe('RIGHT_FLEXED_KNEE')
        ->and($result['secondary_pattern'])->toBe('MILD_FORWARD_SHOULDER')
        ->and($result['asymmetry_flag'])->toBeTrue()
        ->and($result['confidence_level'])->toBe('LOW');

    expect($assessment->fresh()->posture_classification)->toBe('UNCLASSIFIED')
        ->and($assessment->fresh()->review_status)->toBe('MEASUREMENT_REVIEW_REQUIRED');
});

it('confirms kyphosis when the combined measurements clearly support the pattern', function () {
    $assessment = sparisk_assessment([
        'A1' => 0.5, 'A2' => 0.4, 'A3' => 0.5,
        'A7' => 0.5, 'A8' => -1.0, 'A9' => -1.0,
        'C3' => 12.0, 'C4' => 3.0, 'C5' => 1.0,
        'D3' => 12.0, 'D4' => 1.0,
        'side_cva' => 40.0, 'side_kyphosis' => 58.0, 'side_lordosis' => 45.0,
    ]);

    $result = app(SpariskDecisionEngine::class)->decide($assessment);

    expect($result['posture_classification'])->toBe('Kyphosis')
        ->and($result['review_status'])->toBeNull()
        ->and($result['confidence_level'])->toBe('HIGH');
});

it('returns measurement review required when no measurements were captured', function () {
    $patient = Patient::create(['name' => 'Empty Patient', 'age' => 30]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    $result = app(SpariskDecisionEngine::class)->decide($assessment);

    expect($result['posture_classification'])->toBe('UNCLASSIFIED')
        ->and($result['review_status'])->toBe('MEASUREMENT_REVIEW_REQUIRED')
        ->and($result['review_reason'])->toBe('INSUFFICIENT_DATA');
});
