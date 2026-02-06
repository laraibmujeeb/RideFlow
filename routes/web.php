<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RideController;

Route::get('/', function () {
    return redirect()->route('admin.rides.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('rides', [RideController::class, 'index'])->name('rides.index');
    Route::get('rides/{ride}', [RideController::class, 'show'])->name('rides.show');
});
