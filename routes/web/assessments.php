<?php

use App\Http\Controllers\Web\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('/assessments/{postureAssessment}', [AssessmentController::class, 'show'])->name('assessments.show');
Route::get('/assessments/{postureAssessment}/word', [AssessmentController::class, 'generateWord'])->name('assessments.word');
Route::post('/assessments/{postureAssessment}/capture', [AssessmentController::class, 'storeCapture'])->name('assessments.capture');
