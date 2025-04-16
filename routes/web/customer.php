<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::prefix('/customers')->controller(CustomerController::class)->name('customers.')->group(function () {
    Route::get('/', 'index')->name('index');

    Route::prefix("/create")->group(function () {
        Route::view('/', 'admin.customers.create')->name('create');
        Route::post('/', 'store')->name('store');
    })->can('create_customers');

    Route::prefix('/{customer}')->group(function () {
        Route::get('/', 'show')->name('profile');
        Route::get('/bookings', 'bookings')->name('bookings')->can('view_bookings');
    })->can('show', 'customer');

    Route::group([], function () {
        Route::post('/{customer}', 'destroy')->name('destroy');
        Route::post('/{customer}/update', 'update')->name('update');
    })->can('update', 'customer');
})->can('view_customers');