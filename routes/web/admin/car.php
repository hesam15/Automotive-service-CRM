<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::prefix('cars')->name('cars.')->controller(CarController::class)->group(function () {
    Route::get('/list', 'index')->name('index')->can('view_cars');

    Route::prefix('/{customer}')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    })->can('create', 'car');

    Route::prefix('/{car}')->group(function () {
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'destroy')->name('destroy');
    })->can('update', 'car');
});