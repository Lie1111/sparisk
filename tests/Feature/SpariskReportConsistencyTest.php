<?php

use App\Models\AssessmentImage;
use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\PostureSetting;
use App\Services\Sparisk\SpariskInterpretationEngine;
use App\Services\Sparisk\SpariskMeasurementEngine;
use App\Services\Sparisk\SpariskReportPdfService;
use Database\Seeders\PostureSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PostureSettingSeeder::class);
});

/**
 * The level lines the photo draws per view, and the row codes they report.
 * Mirrors SpariskReportPdfService::OV_LEVEL_SECTIONS; the photo label of the
 * alignment tag is OV_ALIGNMENT_SECTION.
 */
const SPARISK_OVERLAY_LEVELS = [
    'right_side' => ['Head' => 'C2', 'Shoulders' => 'C3', 'Pelvis' => 'C4', 'Femur' => 'C5', 'Tibia' => 'C6', 'Foot' => 'C7'],
    'left_side' => ['Head' => 'D2', 'Shoulders' => 'D3', 'Pelvis' => 'D4', 'Femur' => 'D5', 'Tibia' => 'D6', 'Foot' => 'D7'],
    'front' => ['Head' => 'A2', 'Acrom.' => 'A3', 'Axill.' => 'A4', 'Ribc.' => 'A5', 'Torso' => 'A6', 'ASIS' => 'A7'],
    'back' => ['Ears' => 'B2', 'Shoulders' => 'B3', 'Axillae' => 'B4', 'Top of the curve' => 'B5', 'PSIS' => 'B6', 'Knees' => 'B7', 'Feet' => 'B8'],
];

/** 33 MediaPipe pose landmarks in normalized photo space. */
function sparisk_landmarks(array $points): array
{
    $landmarks = array_fill(0, 33, ['x' => 0.5, 'y' => 0.5]);

    foreach ($points as $index => $point) {
        $landmarks[$index] = ['x' => $point[0], 'y' => $point[1]];
    }

    return $landmarks;
}

/**
 * An assessment with one captured photo, so the report can draw the overlay
 * exactly as it does for a real screening.
 */
function sparisk_photo_assessment(string $view, array $angles, array $points = [], int $age = 30): PostureAssessment
{
    $patient = Patient::create(['name' => 'Report Patient', 'age' => $age]);

    $assessment = PostureAssessment::create([
        'patient_id' => $patient->id,
        'assessment_date' => now()->toDateString(),
        'time_mark' => 'TM1',
    ]);

    app(SpariskMeasurementEngine::class)->process($angles, $assessment);

    if ($points !== []) {
        Storage::disk('public')->put('patients/report-test.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8AAAwAB/AF+7gAAAABJRU5ErkJggg=='
        ));

        AssessmentImage::create([
            'posture_assessment_id' => $assessment->id,
            'view' => $view,
            'image_path' => 'patients/report-test.png',
            'landmarks' => sparisk_landmarks($points),
            'order_index' => 1,
        ]);
    }

    return $assessment->fresh();
}

/** The report data the PDF template renders, built through the real service. */
function sparisk_report_data(PostureAssessment $assessment): array
{
    $service = app(SpariskReportPdfService::class);
    $build = new ReflectionMethod($service, 'buildData');
    $build->setAccessible(true);

    return $build->invoke($service, $assessment, false);
}

/** Side-profile landmarks the overlay draws its levels from. */
const SPARISK_SIDE_POINTS = [
    'ear' => 8, 'shoulder' => 12, 'hip' => 24, 'knee' => 26, 'ankle' => 28, 'foot' => 32,
];

function sparisk_side_points(): array
{
    $points = [];
    $y = 0.10;

    foreach (SPARISK_SIDE_POINTS as $index) {
        $points[$index] = [0.48, $y];
        $y += 0.14;
    }

    return $points;
}

it('prints the stored Body Alignment deviation on the photo, not a second measurement', function () {
    $assessment = sparisk_photo_assessment(
        'right_side',
        ['C1' => 2.4, 'C2' => 14.6, 'C3' => -78.9, 'C4' => -21.0, 'C5' => -12.2, 'C6' => 2.9, 'C7' => -8.5],
        sparisk_side_points(),
    );

    $data = sparisk_report_data($assessment);
    $rows = collect($data['views']['right_side']['rows'])->keyBy('section');
    $overlay = $data['images']['right_side']['overlay'];

    $stored = (float) $assessment->measurements()->where('section', 'C1')->value('deviation_angle');
    $storedText = rtrim(rtrim(number_format(abs($stored), 1, '.', ''), '0'), '.');

    // The client's report showed -5.2° on the body and +2.4° in the table.
    expect($overlay['angle'])->toBe($rows['C1']['deviation'].'°')
        ->and($overlay['angle'])->toBe(($stored > 0 ? '+' : '-').$storedText.'°');
});

it('prints each side line and table row with the same stored value', function () {
    $assessment = sparisk_photo_assessment(
        'right_side',
        ['C1' => 2.4, 'C2' => 14.6, 'C3' => -78.9, 'C4' => -21.0, 'C5' => -12.2, 'C6' => 2.9, 'C7' => -8.5],
        sparisk_side_points(),
    );

    $data = sparisk_report_data($assessment);
    $rows = collect($data['views']['right_side']['rows'])->keyBy('section');

    foreach ($data['images']['right_side']['overlay']['levels'] as $level) {
        $code = SPARISK_OVERLAY_LEVELS['right_side'][$level['label']];

        expect($level['deg'])->toBe($rows[$code]['deviation'].'°');
    }
});

it('prints both sides on the paired front lines', function () {
    $assessment = sparisk_photo_assessment(
        'front',
        ['A1' => 0.7, 'A2' => -1.3, 'A3' => 0.2, 'A4' => 2.8, 'A5' => -3.7, 'A6' => 1.1, 'A7' => 3.9, 'A8' => -4.3, 'A9' => -4.9, 'A10' => 40.2, 'A11' => 29.9],
        [
            7 => [0.46, 0.10], 8 => [0.54, 0.10],
            11 => [0.40, 0.22], 12 => [0.60, 0.22],
            23 => [0.42, 0.52], 24 => [0.58, 0.52],
            25 => [0.42, 0.72], 26 => [0.58, 0.72],
            27 => [0.44, 0.92], 28 => [0.56, 0.92],
        ],
    );

    $data = sparisk_report_data($assessment);
    $levels = collect($data['images']['front']['overlay']['levels'])->keyBy('label');
    $rows = collect($data['views']['front']['rows'])->keyBy('section');

    expect($levels['Knees']['deg'])->toBe('R '.$rows['A8']['deviation'].'° · L '.$rows['A9']['deviation'].'°')
        ->and($levels['Feet']['deg'])->toBe('R '.$rows['A10']['deviation'].'° · L '.$rows['A11']['deviation'].'°')
        ->and($levels['Head']['deg'])->toBe($rows['A2']['deviation'].'°');
});

it('shows the published tolerance the point status was judged on', function () {
    $assessment = sparisk_photo_assessment('front', ['A1' => 2.4, 'A4' => 2.8]);

    $rows = collect(sparisk_report_data($assessment)['views']['front']['rows'])->keyBy('section');
    $api = collect(app(SpariskInterpretationEngine::class)->interpret($assessment))->keyBy('section');

    // Axillae (A4) is On Point at +2.8° because its published band is ±3°.
    expect($rows['A4']['status'])->toBe('On Point')
        ->and($rows['A4']['tolerance'])->toBe('3')
        ->and($rows['A1']['tolerance'])->toBe('3')
        ->and((float) $api['A4']['tolerance'])->toBe(3.0);
});

it('reports no tolerance band for a parameter that has no approved reference', function () {
    PostureSetting::where('view', 'right_side')->where('section', 'C3')->delete();

    $assessment = sparisk_photo_assessment(
        'right_side',
        ['C1' => 2.4, 'C3' => -78.9],
        sparisk_side_points(),
    );

    $data = sparisk_report_data($assessment);
    $rows = collect($data['views']['right_side']['rows'])->keyBy('section');
    $levels = collect($data['images']['right_side']['overlay']['levels'])->keyBy('label');

    expect($rows['C3']['tolerance'])->toBeNull()
        ->and($rows['C3']['reference'])->toBeNull()
        ->and($rows['C3']['status'])->toBe('Baseline / Change Tracking')
        ->and($levels['Shoulders']['deg'])->toBe($rows['C3']['deviation'].'°');
});

/** Upright landmarks with the head, shoulders, hips, knees and feet apart. */
function sparisk_upright_points(): array
{
    return [
        7 => [0.46, 0.10], 8 => [0.54, 0.10],
        11 => [0.40, 0.22], 12 => [0.60, 0.22],
        23 => [0.42, 0.52], 24 => [0.58, 0.52],
        25 => [0.42, 0.72], 26 => [0.58, 0.72],
        27 => [0.44, 0.92], 28 => [0.56, 0.92],
        31 => [0.44, 0.95], 32 => [0.56, 0.95],
    ];
}

dataset('photo views', ['front', 'back', 'right_side', 'left_side']);

it('prints every photo label in the gutters, never on the patient', function (string $view) {
    $angles = match ($view) {
        'front' => ['A1' => 0.7, 'A2' => -1.3, 'A3' => 0.2, 'A4' => 2.8, 'A5' => -3.7, 'A6' => 1.1, 'A7' => 3.9, 'A8' => -4.3, 'A9' => -4.9, 'A10' => 40.2, 'A11' => 29.9],
        'back' => ['B1' => 0.4, 'B2' => -1.1, 'B3' => -0.3, 'B4' => 4.3, 'B5' => 1.3, 'B6' => 0.4, 'B7' => -3.8, 'B8' => 42.9],
        'right_side' => ['C1' => 2.3, 'C2' => 14.4, 'C3' => -69.3, 'C4' => -11.2, 'C5' => -11.4, 'C6' => 10.6, 'C7' => -0.7],
        'left_side' => ['D1' => -2.5, 'D2' => -2.5, 'D3' => -70.1, 'D4' => -16.3, 'D5' => -9.3, 'D6' => -12.5, 'D7' => -3.6],
    };

    $points = in_array($view, ['right_side', 'left_side'], true)
        ? sparisk_side_points()
        : sparisk_upright_points();

    $assessment = sparisk_photo_assessment($view, $angles, $points);
    $photo = sparisk_report_data($assessment)['images'][$view];
    $labels = $photo['overlay']['labels'];

    expect($labels)->not->toBeEmpty();

    $gutter = (new ReflectionClassConstant(SpariskReportPdfService::class, 'PHOTO_GUTTER'))->getValue();
    $labelWidth = (new ReflectionClassConstant(SpariskReportPdfService::class, 'LABEL_WIDTH'))->getValue();

    $left = array_values(array_filter($labels, fn (array $label) => $label['side'] === 'left'));
    $right = array_values(array_filter($labels, fn (array $label) => $label['side'] === 'right'));

    // A left label ends before the photo begins; a right label starts after it.
    expect($labelWidth)->toBeLessThanOrEqual($photo['img_left'])
        ->and($photo['box_w'] - $labelWidth)->toBeGreaterThanOrEqual($photo['img_left'] + $photo['img_w']);

    // The name column is complete for every level; the value column carries
    // the same stored reading as the overlay line.
    $levels = collect($photo['overlay']['levels'])->keyBy('label');
    foreach ($levels as $label => $level) {
        $named = collect($labels)->firstWhere('label', $label);

        expect($named)->not->toBeNull();

        if ($level['deg'] !== '') {
            $valued = collect($labels)->firstWhere('deg', $level['deg']);

            expect($valued)->not->toBeNull();
        }
    }

    // No two boxes in one gutter may overlap, and none may leave the plate.
    foreach ([$left, $right] as $column) {
        if ($column === []) {
            continue;
        }

        usort($column, fn (array $a, array $b) => $a['label_top'] <=> $b['label_top']);

        expect($column[0]['label_top'])->toBeGreaterThanOrEqual(0.0);

        for ($i = 1; $i < count($column); $i++) {
            expect($column[$i]['label_top'])
                ->toBeGreaterThanOrEqual($column[$i - 1]['label_top'] + $column[$i - 1]['label_height']);
        }

        $last = end($column);
        expect($last['label_top'] + $last['label_height'])->toBeLessThanOrEqual($photo['box_h']);
    }
})->with('photo views');

it('renders the report with the gutter labels without a template error', function () {
    $assessment = sparisk_photo_assessment(
        'front',
        ['A1' => 0.7, 'A2' => -1.3, 'A3' => 0.2, 'A4' => 2.8, 'A5' => -3.7, 'A6' => 1.1, 'A7' => 3.9, 'A8' => -4.3, 'A9' => -4.9, 'A10' => 40.2, 'A11' => 29.9],
        sparisk_upright_points(),
    );

    $data = sparisk_report_data($assessment);
    $service = app(SpariskReportPdfService::class);
    $make = new ReflectionMethod($service, 'make');
    $make->setAccessible(true);

    $pdf = $make->invoke($service, $data);

    expect($pdf->output())->toStartWith('%PDF');
});
