<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::prefix('booking/{booking}/report')->controller(ReportController::class)->name('report.')->group(function () {
    Route::get('/list', 'index')->name('index')->can('index', 'report');

    Route::group([], function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    })->can('create', 'report');

    Route::get('/{report}', 'show')->name('show');
    
    Route::group([], function () {
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'destroy')->name('destroy');
    })->can('update', 'report');

    Route::get('/{report}/print', 'print')->name('print');
});