<?php

use App\Http\Controllers\Web\PostureMarkerController;
use Illuminate\Support\Facades\Route;

Route::get('/posture-markers', [PostureMarkerController::class, 'index'])->name('posture-markers.index');

Route::post('/posture-markers/conditions/store', [PostureMarkerController::class, 'storeCondition'])->name('posture-markers.conditions.store');
Route::post('/posture-markers/conditions/update', [PostureMarkerController::class, 'updateCondition'])->name('posture-markers.conditions.update');
Route::post('/posture-markers/conditions/destroy', [PostureMarkerController::class, 'destroyCondition'])->name('posture-markers.conditions.destroy');

Route::post('/posture-markers/highlights/store', [PostureMarkerController::class, 'storeHighlight'])->name('posture-markers.highlights.store');
Route::post('/posture-markers/highlights/update', [PostureMarkerController::class, 'updateHighlight'])->name('posture-markers.highlights.update');
Route::post('/posture-markers/highlights/destroy', [PostureMarkerController::class, 'destroyHighlight'])->name('posture-markers.highlights.destroy');
Route::post('/posture-markers/highlights/copy', [PostureMarkerController::class, 'copyHighlights'])->name('posture-markers.highlights.copy');
Route::post('/posture-markers/highlights/mirror-side', [PostureMarkerController::class, 'mirrorSide'])->name('posture-markers.highlights.mirror-side');

Route::post('/posture-markers/body-image', [PostureMarkerController::class, 'uploadBodyImage'])->name('posture-markers.body-image.upload');
Route::post('/posture-markers/body-image/reset', [PostureMarkerController::class, 'resetBodyImage'])->name('posture-markers.body-image.reset');
