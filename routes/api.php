<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReportController;
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
    Route::post('/courses', [CourseController::class, 'store'])->middleware('role:lecturer');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('role:lecturer');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('role:lecturer');
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->middleware('role:student');

    // Material routes
    Route::post('/materials', [MaterialController::class, 'store'])->middleware('role:lecturer');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

    // Assignments
    Route::post('/assignments', [AssignmentController::class, 'store'])->middleware('role:lecturer');

    // Submissions
    Route::post('/submissions', [SubmissionController::class, 'store'])->middleware('role:student');
    Route::post('/submissions/{id}/grade', [SubmissionController::class, 'grade'])->middleware('role:lecturer');

    // Discussions
    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::post('/discussions/{id}/replies', [DiscussionController::class, 'reply']);

    // Reports
    Route::get('/reports/courses', [ReportController::class, 'courseStatistics']);
    Route::get('/reports/assignments', [ReportController::class, 'assignmentStatistics']);
    Route::get('/reports/students/{id}', [ReportController::class, 'studentStatistics']);
});
