<?php

use App\Http\Controllers\Web\PostureMarkerController;
use Illuminate\Support\Facades\Route;

Route::get('/posture-markers', [PostureMarkerController::class, 'index'])->name('posture-markers.index');
Route::post('/posture-markers/store', [PostureMarkerController::class, 'store'])->name('posture-markers.store');
Route::post('/posture-markers/update', [PostureMarkerController::class, 'update'])->name('posture-markers.update');
Route::post('/posture-markers/destroy', [PostureMarkerController::class, 'destroy'])->name('posture-markers.destroy');
