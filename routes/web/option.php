<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OptionsController;

Route::prefix('options')->controller(OptionsController::class)->name('options.')->group(function () {
    Route::get('/', 'index')->name('index')->can('view_options');

    Route::group([], function () {
        Route::view('/create', 'admin.options.create')->name('create');
        Route::post('/create', 'store')->name('store');
    })->can('create_options');

    Route::prefix('/{option}')->group(function () {
        Route::get("/", 'edit')->name('edit');
        Route::post("/update", 'update')->name('update');
        Route::post("/delete", 'destroy')->name('destroy');
    })->can('update', 'option');
});