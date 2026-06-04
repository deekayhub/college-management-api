<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;




Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working',
    ]);
});

Route::middleware('throttle:5,1')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function (){
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);

    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    Route::get('/enrollments', [EnrollmentController::class, 'index']); 
    Route::post('/enrollments', [EnrollmentController::class, 'store']); 
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy']);

    Route::get('logout', [AuthController::class, 'logout']);

});
