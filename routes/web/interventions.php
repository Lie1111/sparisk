<?php

use App\Http\Controllers\Web\InterventionController;
use Illuminate\Support\Facades\Route;

Route::get('/interventions', [InterventionController::class, 'index'])->name('interventions.index');
Route::get('/interventions/{posture}/{age}', [InterventionController::class, 'show'])->name('interventions.show');
Route::post('/interventions/store', [InterventionController::class, 'store'])->name('interventions.store');
Route::post('/interventions/update', [InterventionController::class, 'update'])->name('interventions.update');
Route::post('/interventions/destroy', [InterventionController::class, 'destroy'])->name('interventions.destroy');
