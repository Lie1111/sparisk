<?php

use App\Http\Controllers\PermissionController;

Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware(['permission:can:view:permission']);
Route::post('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware(['permission:can:create:permission']);
Route::post('/permissions/destroy', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware(['permission:can:delete:permission']);
Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update')->middleware(['permission:can:update:permission']);