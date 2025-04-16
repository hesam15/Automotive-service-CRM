<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/users')->controller(UserController::class)->name("users.")->group(function () {
    Route::get('/', 'index')->name('index')->can('view_users');
    Route::get('/profile', 'profile')->name('profile');

    Route::can('create_users')->prefix("/create")->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });

    Route::post('/{user}/delete', 'destroy')->name('destroy')->can('delete', 'user');

    Route::prefix('/{user}')->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/edit', 'update')->name('update');

        Route::post('/updatePhone', 'updatePhone')->name('update.phone');

        Route::post('/asignRole', 'assignRole')->name('asignRole');
    })->can('update', 'user');

    Route::get('/create/apiKey', 'createApiKey')->name("create.api-key")->can('create_api_key');
});