<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerifyPhoneTokensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware("auth:sanctum")->group(function() {
    Route::prefix('users')->name("users.")->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name("index");
        Route::get('/users/{user}', 'show')->name("show");
    });
});

Route::post('/users/create', [UserController::class, 'store']);