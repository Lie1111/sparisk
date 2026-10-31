<?php

use App\Http\Controllers\Api\AcademyController;
use App\Http\Controllers\Api\BmiController;
use App\Http\Controllers\Api\HealthScreeningController;
use App\Http\Controllers\Api\MuscleMapController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PostureAssessmentController;
use App\Http\Controllers\Api\PostureMarkerController;
use App\Http\Controllers\Api\PostureSettingController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SkillAssessmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPARISK - SATA API Routes
|--------------------------------------------------------------------------
| Posture Assessment & Aquatic Therapy Platform
*/

Route::middleware('auth:sanctum')->group(function () {

    // Patients
    Route::apiResource('patients', PatientController::class)->names('api.patients');
    Route::get('patients/{patient}/assessments', [PatientController::class, 'assessments']);

    // BMI - central SATA age + gender classification
    Route::post('bmi/calculate', [BmiController::class, 'calculate']);
    Route::get('patients/{patient}/bmi', [BmiController::class, 'forPatient']);

    // Health Screenings
    Route::get('patients/{patient}/health-screenings', [HealthScreeningController::class, 'index']);
    Route::post('patients/{patient}/health-screenings', [HealthScreeningController::class, 'store']);
    Route::get('health-screenings/{healthScreening}', [HealthScreeningController::class, 'show']);

    // SATA Aquatic Skills Assessment checklist and Student Progress Dashboard
    Route::get('skill-assessment/catalogue', [SkillAssessmentController::class, 'catalogue']);
    Route::get('patients/{patient}/skill-assessments', [SkillAssessmentController::class, 'index']);
    Route::post('patients/{patient}/skill-assessments', [SkillAssessmentController::class, 'store']);

    // Posture Assessments - Full Pipeline
    Route::post('patients/{patient}/assessments', [PostureAssessmentController::class, 'store']);
    Route::get('assessments', [PostureAssessmentController::class, 'index']);
    Route::get('assessments/{postureAssessment}', [PostureAssessmentController::class, 'show']);
    Route::delete('assessments/{postureAssessment}', [PostureAssessmentController::class, 'destroy']);

    // Posture Settings - SATA Age-Based Reference
    Route::get('posture-settings', [PostureSettingController::class, 'index']);

    // Posture Markers - body character overlay markers
    Route::get('posture-markers', [PostureMarkerController::class, 'index']);

    // Muscle Map - detected conditions with weak / tight muscle highlights
    Route::get('muscle-map', [MuscleMapController::class, 'index']);
    Route::get('assessments/{postureAssessment}/muscle-map', [MuscleMapController::class, 'forAssessment']);
    Route::get('assessments/{postureAssessment}/program', [MuscleMapController::class, 'program']);

    // Progress Tracking
    Route::post('progress/compare', [ProgressController::class, 'compare']);
    Route::get('patients/{patient}/progress/timeline', [ProgressController::class, 'timeline']);
    Route::get('patients/{patient}/progress/trends', [ProgressController::class, 'trends']);

    // Reports
    Route::post('assessments/{postureAssessment}/reports', [ReportController::class, 'generate']);
    Route::get('assessments/{postureAssessment}/pdf', [ReportController::class, 'pdf']);
    Route::get('reports/{report}', [ReportController::class, 'show']);
    Route::get('reports/patient', [ReportController::class, 'patientReports']);

    // Academies
    Route::apiResource('academies', AcademyController::class)->except(['edit', 'create'])->names('api.academies');
    Route::post('academies/{academy}/members', [AcademyController::class, 'addMember']);
    Route::delete('academies/{academy}/members/{member}', [AcademyController::class, 'removeMember']);
    Route::get('academies/{academy}/members', [AcademyController::class, 'members']);
    Route::get('academies/{academy}/students', [AcademyController::class, 'students']);
});
