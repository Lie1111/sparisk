<?php

use App\Http\Controllers\Web\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('/assessments/{postureAssessment}', [AssessmentController::class, 'show'])->name('assessments.show');
Route::post('/assessments/{postureAssessment}/capture', [AssessmentController::class, 'storeCapture'])->name('assessments.capture');
