<?php

use App\Models\Patient;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('stores the neurodevelopmental profile with its conditions through the API', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/patients', [
        'name' => 'Amir',
        'neuro_profile' => 'neurodivergent',
        'neuro_conditions' => ['asd', 'adhd', 'other'],
        'neuro_conditions_other' => 'Sensory processing differences',
    ])->assertCreated();

    $patient = Patient::firstOrFail();

    expect($patient->neuro_profile)->toBe('neurodivergent')
        ->and($patient->neuro_conditions)->toBe(['asd', 'adhd', 'other'])
        ->and($patient->neuro_conditions_other)->toBe('Sensory processing differences')
        ->and($patient->hasNeuroProfile())->toBeTrue();

    $response->assertJsonPath('neuro_profile_label', 'Neurodivergent')
        ->assertJsonPath('neuro_condition_labels.0', 'Autism Spectrum Disorder (ASD)')
        ->assertJsonPath('neuro_condition_labels.1', 'ADHD');
});

it('offers exactly the options listed in config so every platform agrees', function () {
    expect(array_keys(config('sparisk.neuro_profiles')))->toBe(['neurotypical', 'neurodivergent'])
        ->and(array_keys(config('sparisk.neuro_conditions')))->toBe([
            'asd', 'adhd', 'gdd', 'dcd', 'intellectual_disability',
            'down_syndrome', 'dyslexia', 'other',
        ]);
});

it('rejects a neurodevelopmental profile or condition outside the configured list', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/patients', [
        'name' => 'Amir',
        'neuro_profile' => 'something_else',
    ])->assertStatus(422)->assertJsonValidationErrors('neuro_profile');

    $this->postJson('/api/patients', [
        'name' => 'Amir',
        'neuro_profile' => 'neurodivergent',
        'neuro_conditions' => ['not_a_condition'],
    ])->assertStatus(422)->assertJsonValidationErrors('neuro_conditions.0');
});

it('leaves the stored profile untouched when the update does not send it', function () {
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create([
        'name' => 'Amir',
        'neuro_profile' => 'neurodivergent',
        'neuro_conditions' => ['adhd'],
    ]);

    // An older client that knows nothing about the profile must not erase it.
    $this->putJson("/api/patients/{$patient->id}", ['name' => 'Amir Updated'])
        ->assertOk();

    expect($patient->fresh()->neuro_profile)->toBe('neurodivergent')
        ->and($patient->fresh()->neuro_conditions)->toBe(['adhd']);
});

it('can change the profile and clear the conditions when the participant is neurotypical', function () {
    Sanctum::actingAs(User::factory()->create());

    $patient = Patient::create([
        'name' => 'Amir',
        'neuro_profile' => 'neurodivergent',
        'neuro_conditions' => ['asd'],
    ]);

    $this->putJson("/api/patients/{$patient->id}", [
        'neuro_profile' => 'neurotypical',
        'neuro_conditions' => [],
    ])->assertOk();

    $patient->refresh();

    expect($patient->neuro_profile)->toBe('neurotypical')
        ->and($patient->neuro_conditions)->toBe([])
        ->and($patient->neuro_condition_labels)->toBe([])
        ->and($patient->neuro_profile_label)->toBe('Neurotypical');
});

it('reports that the profile has not been answered yet for legacy participants', function () {
    $patient = Patient::create(['name' => 'Legacy']);

    expect($patient->hasNeuroProfile())->toBeFalse()
        ->and($patient->neuro_profile_label)->toBeNull()
        ->and($patient->neuro_condition_labels)->toBe([]);
});
