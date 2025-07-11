<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\GradeController;
use App\Http\Controllers\Api\Admin\AttendanceController;
use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\Admin\SubjectController;
use App\Http\Controllers\Api\Admin\TermController;
use App\Http\Controllers\Api\Admin\ClassController;
use App\Http\Controllers\Api\Admin\SchoolClassController;
use App\Http\Controllers\Api\Student\DashboardController;
use App\Http\Controllers\Api\Student\ProfileController;
use App\Http\Controllers\Api\Teacher\ProfileController as TeacherProfileController;
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
    
    // Common routes (subjects, terms, and classes)
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/terms', [TermController::class, 'index']);
    Route::get('/classes', [SchoolClassController::class, 'index']);
    
    // Subject and class management (CRUD)
    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::post('/classes', [SchoolClassController::class, 'store']);
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Custom attendance routes (must be before apiResource)
        Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
        Route::get('attendance/stats', [AttendanceController::class, 'stats']);
        Route::get('attendance/by-date-class', [AttendanceController::class, 'getByDateAndClass']);
        Route::get('grades/statistics', [GradeController::class, 'statistics']);
        Route::get('analytics/overview', [AnalyticsController::class, 'overview']);
        
        // Resource routes
        Route::apiResource('students', StudentController::class);
        Route::apiResource('grades', GradeController::class);
        Route::apiResource('attendance', AttendanceController::class);
        Route::apiResource('subjects', SubjectController::class);
        Route::apiResource('terms', TermController::class);
    });
    
    // Teacher routes
    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        // Profile management
        Route::get('profile', [TeacherProfileController::class, 'show']);
        Route::put('profile', [TeacherProfileController::class, 'update']);
        Route::post('profile/image', [TeacherProfileController::class, 'uploadImage']);
        Route::delete('profile/image', [TeacherProfileController::class, 'deleteImage']);
    });
    
    // Student routes
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('my-grades', [DashboardController::class, 'myGrades']);
        Route::get('my-attendance', [DashboardController::class, 'myAttendance']);
        Route::get('my-feedback', [DashboardController::class, 'myFeedback']);
        
        // Profile management
        Route::get('profile', [ProfileController::class, 'show']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::post('profile/image', [ProfileController::class, 'uploadImage']);
        Route::delete('profile/image', [ProfileController::class, 'deleteImage']);
    });
});
