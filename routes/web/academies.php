<?php

use App\Http\Controllers\Web\AcademyController;
use Illuminate\Support\Facades\Route;

Route::get('/academies', [AcademyController::class, 'index'])->name('academies.index');
Route::get('/academies/{academy}', [AcademyController::class, 'show'])->name('academies.show');
