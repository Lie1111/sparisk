<?php

use App\Http\Controllers\Api\AcademyController;
use App\Http\Controllers\Api\HealthScreeningController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PostureAssessmentController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPARISK - SATA API Routes
|--------------------------------------------------------------------------
| Posture Assessment & Aquatic Therapy Platform
*/

Route::middleware('auth:sanctum')->group(function () {

    // Patients
    Route::apiResource('patients', PatientController::class);
    Route::get('patients/{patient}/assessments', [PatientController::class, 'assessments']);

    // Health Screenings
    Route::get('patients/{patient}/health-screenings', [HealthScreeningController::class, 'index']);
    Route::post('patients/{patient}/health-screenings', [HealthScreeningController::class, 'store']);
    Route::get('health-screenings/{healthScreening}', [HealthScreeningController::class, 'show']);

    // Posture Assessments - Full Pipeline
    Route::post('patients/{patient}/assessments', [PostureAssessmentController::class, 'store']);
    Route::get('assessments/{postureAssessment}', [PostureAssessmentController::class, 'show']);
    Route::delete('assessments/{postureAssessment}', [PostureAssessmentController::class, 'destroy']);

    // Progress Tracking
    Route::post('progress/compare', [ProgressController::class, 'compare']);
    Route::get('patients/{patient}/progress/timeline', [ProgressController::class, 'timeline']);
    Route::get('patients/{patient}/progress/trends', [ProgressController::class, 'trends']);

    // Reports
    Route::post('assessments/{postureAssessment}/reports', [ReportController::class, 'generate']);
    Route::get('reports/{report}', [ReportController::class, 'show']);
    Route::get('reports/patient', [ReportController::class, 'patientReports']);

    // Academies
    Route::apiResource('academies', AcademyController::class)->except(['edit', 'create']);
    Route::post('academies/{academy}/members', [AcademyController::class, 'addMember']);
    Route::delete('academies/{academy}/members/{member}', [AcademyController::class, 'removeMember']);
    Route::get('academies/{academy}/members', [AcademyController::class, 'members']);
    Route::get('academies/{academy}/students', [AcademyController::class, 'students']);
});
