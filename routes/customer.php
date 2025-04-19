<?php

use Illuminate\Support\Facades\Route;

Route::get('/customer', function() {
    return view('customer.dashboard');
});