<?php

use App\Models\ServiceCenter;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return view('customer.index', ['serviceCenters' => ServiceCenter::all()]);
});