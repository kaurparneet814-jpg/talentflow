<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\InterviewController;
use App\Http\Controllers\Api\TechnicalTaskController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/jobs', [JobController::class, 'index']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    
    Route::post('/jobs/{jobId}/apply',[ApplicationController::class, 'store']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);

    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews', [InterviewController::class, 'store']);

    Route::put('/interviews/{id}', [InterviewController::class, 'update']);
    Route::post('/technical-tasks', [TechnicalTaskController::class, 'store']);

    Route::put('/technical-tasks/{id}/status',[TechnicalTaskController::class, 'updateStatus']);
    Route::post('/resumes', [ResumeController::class, 'store']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    

});
