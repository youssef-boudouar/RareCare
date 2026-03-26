<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('chat', [ChatController::class, 'chat']);

    // Admin only: delete
    Route::middleware('role:admin')->group(function () {
        Route::delete('patients/{patient}', [PatientController::class, 'destroy']);
    });

    // Admin + Doctor: create and update
    Route::middleware('role:admin,doctor')->group(function () {
        Route::post('patients', [PatientController::class, 'store']);
        Route::put('patients/{patient}', [PatientController::class, 'update']);
    });

    // All authenticated users: read
    Route::get('patients', [PatientController::class, 'index']);
    Route::get('patients/{patient}', [PatientController::class, 'show']);
});
