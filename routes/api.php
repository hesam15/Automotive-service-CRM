<?php

use App\Services\DatePicker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerifyPhoneTokensController;


Route::middleware("auth:sanctum")->group(function() {
    Route::prefix('users')->name("api.users.")->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name("index");
        Route::post('/create', 'create');

        Route::get('/{user}', 'show')->name("show");
    });

    Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
});

Route::post('/users/create', [UserController::class, 'store']);