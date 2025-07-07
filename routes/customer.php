<?php

use App\Http\Controllers\ClientSideController;
use App\Models\ServiceCenter;
use Hekmatinasser\Jalali\Jalali;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClientSideController::class, 'index']);

Route::get('/service-centers/{service_center}', [ClientSideController::class, 'show'])->name('show.serviceCenter');