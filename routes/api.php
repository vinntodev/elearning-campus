<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\SubmissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store'])->middleware('role:dosen');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('role:dosen');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('role:dosen');
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->middleware('role:mahasiswa');

    // Material routes
    Route::post('/materials', [MaterialController::class, 'store'])->middleware('role:dosen');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

    // Assignments
    Route::post('/assignments', [AssignmentController::class, 'store'])->middleware('role:dosen');

    // Submissions
    Route::post('/submissions', [SubmissionController::class, 'store'])->middleware('role:mahasiswa');
    Route::post('/submissions/{id}/grade', [SubmissionController::class, 'grade'])->middleware('role:dosen');

    // Discussions
    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::post('/discussions/{id}/replies', [DiscussionController::class, 'reply']);
});
