<?php

use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware(['permission:can:view:user']);
Route::post('/users/create', [UserController::class, 'create'])->name('users.create')->middleware(['permission:can:create:user']);
Route::post('/users/destroy', [UserController::class, 'destroy'])->name('users.destroy')->middleware(['permission:can:delete:user']);
Route::post('/users/update', [UserController::class, 'update'])->name('users.update')->middleware(['permission:can:update:user']);