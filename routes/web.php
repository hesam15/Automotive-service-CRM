<?php

use App\Services\DatePicker;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckServiceCenter;
use App\Http\Controllers\DashboardController;

require __DIR__.'/web/beforeCompleteLogin.php';

Route::middleware(['auth', 'verified', CheckServiceCenter::class, 'role:adminstrator|expert|clerk'])->group(function () {    
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //Service Center
        require __DIR__.'/web/serviceCenter.php';

        //Users
        require __DIR__.'/web/user.php';

        //Customers
        require __DIR__.'/web/customer.php';

        //Bookings
        require __DIR__.'/web/booking.php';

        //Cars
        require __DIR__.'/web/car.php';

        //Reports
        require __DIR__.'/web/report.php';

        //Options
        require __DIR__.'/web/option.php';

        Route::get('/datepicker-settings', [DatePicker::class, 'getSettings']);
        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });
});

require __DIR__.'/auth.php';