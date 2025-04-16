<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::prefix("bookings")->name('bookings.')->controller(BookingController::class)->group(function () {
    Route::get('/list', 'index')->name('index')->can('view_bookings');

    Route::prefix('/{customer}')->group(function() {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    })->can('create', 'booking');

    Route::prefix('/{customer}')->group(function () {
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'destroy')->name('destroy');

        Route::post('/update-status', 'updateStatus')->name('updateStatus');
    })->can('update', 'booking');
});