<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sends guests to the login screen', function () {
    $this->get('/')->assertRedirect(route('login', absolute: false));
});

it('sends authenticated users to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertRedirect(route('dashboard', absolute: false));
});
