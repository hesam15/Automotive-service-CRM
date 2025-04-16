<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceCenterController;

Route::prefix('serviceCenters')->controller(ServiceCenterController::class)->name("serviceCenter.")->group(function () {
    Route::get('/edit/{serviceCenter}', 'edit')->name("edit");
    Route::put('/update/{serviceCenter}', 'update')->name("update");

    Route::post('/delete/{serviceCenter}', 'delete')->name("destroy");
})->can('update', 'serviceCenter');