<?php

use App\Http\Controllers\RoleController;

Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware(['permission:can:view:role']);
Route::post('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware(['permission:can:create:role']);
Route::post('/roles/destroy', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['permission:can:delete:role']);
Route::post('/roles/update', [RoleController::class, 'update'])->name('roles.update')->middleware(['permission:can:update:role']);