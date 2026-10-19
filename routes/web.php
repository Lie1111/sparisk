<?php

use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? to_route('dashboard')
        : to_route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    foreach (glob(__DIR__ . "/web/*.php") as $file) {
        require $file;
    }
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
