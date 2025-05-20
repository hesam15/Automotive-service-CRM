<?php

use App\Services\DatePicker;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckServiceCenter;
use App\Http\Controllers\DashboardController;

require __DIR__.'/web/admin/beforeCompleteLogin.php';

Route::middleware(['auth', 'verified', CheckServiceCenter::class, 'role:adminstrator|expert|clerk'])->group(function () {    
    Route::prefix('serviceCenters/{service_center}/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //Service Center
        require __DIR__.'/web/admin/serviceCenter.php';

        //Users
        require __DIR__.'/web/admin/user.php';

        //Customers
        require __DIR__.'/web/admin/customer.php';

        //Bookings
        require __DIR__.'/web/admin/booking.php';

        //Cars
        require __DIR__.'/web/admin/car.php';

        //Reports
        require __DIR__.'/web/admin/report.php';

        //Options
        require __DIR__.'/web/admin/option.php';

        Route::get('/datepicker-settings', [DatePicker::class, 'getSettings']);
        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });
});