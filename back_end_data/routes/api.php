<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\GradeController;
use App\Http\Controllers\Api\Admin\AttendanceController;
use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\Student\DashboardController;
use Illuminate\Support\Facades\Route;

// CSRF cookie route for SPA authentication
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('students', StudentController::class);
        Route::apiResource('grades', GradeController::class);
        Route::apiResource('attendance', AttendanceController::class);
        Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
        Route::get('attendance/stats', [AttendanceController::class, 'stats']);
        Route::get('grades/statistics', [GradeController::class, 'statistics']);
        Route::get('analytics/overview', [AnalyticsController::class, 'overview']);
    });
    
    // Student routes
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('my-grades', [DashboardController::class, 'myGrades']);
        Route::get('my-attendance', [DashboardController::class, 'myAttendance']);
        Route::get('my-feedback', [DashboardController::class, 'myFeedback']);
    });
});
