<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store'])->middleware('role:dosen');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('role:dosen');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('role:dosen');
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->middleware('role:mahasiswa');
});
