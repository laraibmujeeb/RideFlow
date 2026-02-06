<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\DriverController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('passenger')->group(function () {
    Route::post('rides', [PassengerController::class, 'create']);
    Route::post('rides/{id}/approve-driver', [PassengerController::class, 'approveDriver']);
    Route::post('rides/{id}/complete', [PassengerController::class, 'completeRide']);
});

Route::prefix('driver')->group(function () {
    Route::post('location', [DriverController::class, 'updateLocation']);
    Route::get('rides/nearby', [DriverController::class, 'getNearbyRides']);
    Route::post('rides/{id}/request', [DriverController::class, 'requestRide']);
    Route::post('rides/{id}/complete', [DriverController::class, 'completeRide']);
});
