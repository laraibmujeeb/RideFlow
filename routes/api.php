<?php

use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PassengerController;
use Illuminate\Support\Facades\Route;

Route::prefix('passenger')->name('passenger.')->group(function () {
    Route::post('rides', [PassengerController::class, 'create'])->name('rides.create');
    Route::post('rides/{ride}/approve-driver', [PassengerController::class, 'approveDriver'])->name('rides.approve-driver');
    Route::post('rides/{ride}/complete', [PassengerController::class, 'completeRide'])->name('rides.complete');
});

Route::prefix('driver')->name('driver.')->group(function () {
    Route::post('location', [DriverController::class, 'updateLocation'])->name('location.update');
    Route::get('rides/nearby', [DriverController::class, 'getNearbyRides'])->name('rides.nearby');
    Route::post('rides/{ride}/request', [DriverController::class, 'requestRide'])->name('rides.request');
    Route::post('rides/{ride}/complete', [DriverController::class, 'completeRide'])->name('rides.complete');
});
