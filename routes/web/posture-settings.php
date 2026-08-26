<?php

use App\Http\Controllers\Web\PostureSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/posture-settings', [PostureSettingController::class, 'index'])->name('posture-settings.index');
Route::post('/posture-settings/update', [PostureSettingController::class, 'update'])->name('posture-settings.update');
