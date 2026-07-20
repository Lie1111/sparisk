<?php

use Illuminate\Support\Facades\Route;


require __DIR__ . '/api/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    foreach (glob(__DIR__ . "/api/*.php") as $file) {
        if ($file !== __DIR__ . "/api/auth.php" && $file !== __DIR__ . "/api/download.php") {
            require $file;
        }
    }
});