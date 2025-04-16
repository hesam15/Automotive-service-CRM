<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceCenterController;
use App\Http\Controllers\Auth\VerifyPhoneController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function() {
    return redirect()->route('home');
});

// Send Verification Phone Code
Route::controller(VerifyPhoneController::class)->group(function () {
    Route::post('/send-verification-code', 'send');
    Route::post('/verify-code', 'verify');
});

// Register User
Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('register', 'create')->name('register');
    Route::post('register', 'store')->name('registerUser');
});

// Create Service Center
Route::prefix('dashboard/serviceCenters/create')->name("serviceCenters.")->controller(ServiceCenterController::class)->group(function () {
    Route::get('/{user}', "create")->name("create");
    Route::post('/{user}', 'store')->name("store");
})->middleware(['auth', 'role:adminstrator', 'can:create_serviceCetners']);