<?php

use App\Models\PostureMarker;
use Database\Seeders\PostureMarkerSeeder;
use Database\Seeders\SataPostureMarkerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PostureMarkerSeeder::class);
    $this->seed(SataPostureMarkerSeeder::class);
});

$geometry = fn (PostureMarker $m) => sprintf('%.4f,%.4f %.4fx%.4f', $m->x, $m->y, $m->width, $m->height);

it('draws every muscle in the same place in every set', function () use ($geometry) {
    $offenders = [];

    foreach (['male', 'female'] as $gender) {
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $rows = PostureMarker::where('gender', $gender)->where('view', $view)->get();

            foreach ($rows->groupBy('label') as $label => $group) {
                $placements = $group->map($geometry)->unique();

                if ($placements->count() > 1) {
                    // A highlight that sits somewhere else in one set lands off
                    // the muscle, which is exactly how the male calves drifted.
                    $offenders[] = sprintf('%s %s / %s -> %s', $gender, $view, $label, $placements->implode(' | '));
                }
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('gives every severity set the same muscles as the normal set', function () {
    foreach (['male', 'female'] as $gender) {
        foreach (['front', 'back', 'right_side', 'left_side'] as $view) {
            $sets = PostureMarker::where('gender', $gender)->where('view', $view)->whereNull('condition')
                ->get()
                ->groupBy('severity');

            $normal = $sets->get('normal', collect())->pluck('label')->sort()->values();

            foreach (['moderate', 'severe'] as $severity) {
                expect($sets->get($severity, collect())->pluck('label')->sort()->values()->all())
                    ->toBe($normal->all(), "{$gender} {$view} {$severity} set does not match the normal set");
            }
        }
    }
});
