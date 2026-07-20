<?php

use App\Http\Controllers\Web\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/patients/photo/{path}', [PatientController::class, 'photo'])->where('path', '.*')->name('patients.photo');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
Route::post('/patients/create', [PatientController::class, 'create'])->name('patients.create');
Route::post('/patients/update', [PatientController::class, 'update'])->name('patients.update');
Route::post('/patients/destroy', [PatientController::class, 'destroy'])->name('patients.destroy');
