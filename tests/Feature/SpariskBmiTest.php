<?php

use App\Models\BmiReference;
use App\Models\Patient;
use App\Models\User;
use App\Services\Sparisk\SpariskBmiEngine;
use Database\Seeders\BmiReferenceSeeder;
use Laravel\Sanctum\Sanctum;

it('calculates BMI from age, gender, height and weight', function () {
    $result = app(SpariskBmiEngine::class)->calculate(10, 'male', 140, 35);

    expect($result['valid'])->toBeTrue()
        ->and($result['bmi'])->toBe(17.9)
        ->and($result['bmi_display'])->toBe('17.9 kg/m²')
        ->and($result['category'])->toBe('Normal')
        ->and($result['age_group'])->toBe('9-12')
        ->and($result['age_group_label'])->toBe('9–12 years');
});

it('converts height from centimetres to metres before calculating', function () {
    $result = app(SpariskBmiEngine::class)->calculate(30, 'male', 170, 65);

    // 170 cm and 1.70 m describe the same person: 65 / 1.70² = 22.5
    expect($result['bmi'])->toBe(22.5)
        ->and($result['height_m'])->toBe(1.7);
});

it('classifies children by exact age and gender instead of the adult cut-off', function () {
    $engine = app(SpariskBmiEngine::class);

    // 21.4 kg/m² would be a healthy adult BMI, but at age 10 it is obesity for a
    // boy (+2SD = 21.4) and only overweight for a girl (+2SD = 22.6).
    expect($engine->calculate(10, 'male', 140, 42)['category'])->toBe('Obesity')
        ->and($engine->calculate(10, 'female', 140, 42)['category'])->toBe('Overweight')
        ->and($engine->calculate(10, 'female', 140, 42)['age_group'])->toBe('9-12');
});

it('moves the child cut-off as the exact age changes', function () {
    $engine = app(SpariskBmiEngine::class);

    // Same BMI (17.5) is normal at 6 years (+1SD = 16.8 for boys, +2SD = 18.5)
    // but normal too at 12 (+1SD = 19.9): the bands must still be age driven.
    $six = $engine->calculate(6, 'male', 120, 25.2);
    $twelve = $engine->calculate(12, 'male', 150, 39.4);

    expect($six['bmi'])->toBe(17.5)
        ->and($six['category'])->toBe('Overweight')
        ->and($twelve['bmi'])->toBe(17.5)
        ->and($twelve['category'])->toBe('Normal');
});

it('uses the shared adult classification from 19 years upwards', function () {
    $engine = app(SpariskBmiEngine::class);

    expect($engine->calculate(19, 'male', 170, 50)['category'])->toBe('Underweight')
        ->and($engine->calculate(30, 'male', 170, 65)['category'])->toBe('Normal')
        ->and($engine->calculate(30, 'female', 170, 65)['category'])->toBe('Normal')
        ->and($engine->calculate(30, 'male', 170, 80)['category'])->toBe('Overweight')
        ->and($engine->calculate(30, 'male', 170, 95)['category'])->toBe('Obesity I')
        ->and($engine->calculate(30, 'male', 170, 110)['category'])->toBe('Obesity II')
        ->and($engine->calculate(30, 'male', 170, 125)['category'])->toBe('Obesity III')
        ->and($engine->calculate(55, 'female', 170, 95)['category'])->toBe('Obesity I')
        ->and($engine->calculate(55, 'female', 170, 95)['age_group'])->toBe('50+');
});

it('validates age, gender, height and weight before classifying', function () {
    $engine = app(SpariskBmiEngine::class);

    // `errors` is transported as a JSON object, so it is read back as an array.
    $errors = fn (...$args) => (array) $engine->calculate(...$args)['errors'];

    expect($errors(null, 'male', 140, 35))->toHaveKey('age')
        ->and($errors(3, 'male', 140, 35))->toHaveKey('age')
        ->and($errors(10, null, 140, 35))->toHaveKey('gender')
        ->and($errors(10, 'male', 0, 35))->toHaveKey('height')
        ->and($errors(10, 'male', 140, 0))->toHaveKey('weight')
        ->and($engine->calculate(10, 'male', 140, 0)['valid'])->toBeFalse()
        ->and($engine->calculate(10, 'male', 140, 0)['bmi'])->toBeNull();
});

it('always returns an empty errors object, never a list, for a valid input', function () {
    $payload = app(SpariskBmiEngine::class)->calculate(10, 'male', 140, 35);

    // A list would crash clients that read `errors` as a field => message map.
    expect(json_encode($payload['errors']))->toBe('{}')
        ->and(json_encode($payload))->toContain('"errors":{}');
});

it('requires a gender for children but not for adults', function () {
    $engine = app(SpariskBmiEngine::class);

    expect($engine->calculate(10, null, 140, 35)['valid'])->toBeFalse()
        ->and($engine->calculate(30, null, 170, 65)['valid'])->toBeTrue()
        ->and($engine->calculate(30, null, 170, 65)['category'])->toBe('Normal');
});

it('seeds an official age and gender reference band for every child year', function () {
    $this->seed(BmiReferenceSeeder::class);

    expect(BmiReference::where('age_years', 10)->where('gender', 'male')->count())->toBe(5)
        ->and(BmiReference::count())->toBe((13 * 2 * 5) + (2 * 6))
        ->and(BmiReference::where('is_verified', false)->count())->toBe(BmiReference::count());
});

it('resolves the category from the editable reference table, not from code', function () {
    $this->seed(BmiReferenceSeeder::class);

    $engine = app(SpariskBmiEngine::class);

    expect($engine->calculate(10, 'male', 140, 35)['category'])->toBe('Normal');

    // SATA updates the clinical reference; the engine needs no change.
    BmiReference::where('age_group', '9-12')
        ->where('age_years', 10)
        ->where('gender', 'male')
        ->where('category', 'Normal')
        ->update(['category' => 'Healthy Range']);

    expect($engine->calculate(10, 'male', 140, 35)['category'])->toBe('Healthy Range');
});

it('exposes the same BMI result through the API', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/bmi/calculate', [
        'age' => 10, 'gender' => 'male', 'height' => 140, 'weight' => 35,
    ])
        ->assertOk()
        ->assertJsonPath('bmi', 17.9)
        ->assertJsonPath('category', 'Normal')
        ->assertJsonPath('valid', true);
});

it('returns the BMI of a stored participant through the API', function () {
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create([
        'name' => 'Aina', 'age' => 10, 'gender' => 'male', 'height' => 140, 'weight' => 35,
    ]);

    $this->getJson("/api/patients/{$patient->id}/bmi")
        ->assertOk()
        ->assertJsonPath('category', 'Normal');

    $this->getJson("/api/patients/{$patient->id}")
        ->assertOk()
        ->assertJsonPath('bmi.category', 'Normal')
        ->assertJsonPath('bmi.bmi', 17.9);
});
