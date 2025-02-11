<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\TravelRequestStatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('travel-requests')->group(function () {
            Route::post('status-change', [TravelRequestStatusController::class, 'changeStatus']);
            Route::post('/', [TravelRequestController::class, 'create']);
            Route::get('/', [TravelRequestController::class, 'list']);
            Route::get('{external_id}', [TravelRequestController::class, 'show']);
        });
    });
});