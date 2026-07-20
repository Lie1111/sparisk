<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    foreach (glob(__DIR__ . "/web/*.php") as $file) {
        require $file;
    }
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
