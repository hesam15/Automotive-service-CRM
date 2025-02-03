<?php

use App\Models\User;
use App\Models\Booking;
use App\Services\DatePicker;
use App\Services\Notification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Notifications\Notification as NotificationsNotification;

    //verify phone
    Route::post('sendVerify', [RegisteredUserController::class, 'sendVerify'])->name('sendVerify');
    Route::post('verifyCode', [RegisteredUserController::class, 'verifyCode'])->name('verifyCode');

Route::middleware(['auth' , 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get("reportShow/{carId}",[CustomerController::class, 'show'])->name('show.customer.report');
    // Route::get('pdf', action: [CustomerController::class, 'pdf'])->name('download.pdf');
    Route::group(['prefix' => 'dashboard'], function () {

        //Users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/profile/{id}', [UserController::class, 'profile'])->name('user.profile');

            Route::middleware('permision:create_user')->group(function () {
                Route::get('/create', [UserController::class, 'create'])->name('users.create');
                Route::post('/create', [UserController::class, 'store'])->name('users.store');
            });
            Route::post('/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permision:delete_user');
            Route::middleware('permision:edit_customer')->group(function () {
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
                Route::post('/{user}/edit', [UserController::class, 'update'])->name('users.update');
                Route::post('/{user}/updatePhone', [UserController::class, 'updatePhone'])->name('users.update.phone');
                //asign role to user
                Route::post('/{user}/asignRole', [UserController::class, 'assignRole'])->name('users.asignRole');
            });
        });

        //Roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles.index');

            Route::get('/create', [RoleController::class, 'storePage'])->name('roles.create');
            Route::post('/create', [RoleController::class, 'store'])->name('roles.store');

            Route::middleware('permision:edit_role')->group(function () {
                Route::get("/{role}/edit", [RoleController::class, 'edit'])->name('roles.edit');
                Route::post("/{role}/edit", [RoleController::class, 'update'])->name('roles.update');
            });

            Route::post("/{role}/delete", [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permision:delete_role');
        })->middleware('permision:create_role');

        //Customers
        Route::prefix('customers')->group(function () {
            Route::get('/index', [CustomerController::class, 'index'])->name('customers.index');

            Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');

            Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.profile');
            Route::get('/{id}/bookings', [BookingController::class, 'customer'])->name('customers.bookings');

            Route::middleware('permision:edit_customer')->group(function () {
                Route::post('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
                Route::post('/{id}/update', [CustomerController::class, 'update'])->name('customers.update');
            });
        })->middleware('permision:create_customer'); 

        //Bookings
        Route::prefix("bookings")->group(function () {
            Route::get('/list', [BookingController::class, 'index'])->name('bookings.index');
            // Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');

            Route::get('/{id}/create', [BookingController::class, 'create'])->name('bookings.create');
            Route::post('/{id}/store', [BookingController::class, 'store'])->name('bookings.store');

            Route::post('/{id}/update', [BookingController::class, 'update'])->name('bookings.update');
            Route::post('/{id}/delete', [BookingController::class, 'destroy'])->name('bookings.destroy');

            Route::post('/{id}/updateStatus', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        })->middleware('permision:create_customer');

        //Cars
        Route::prefix('cars')->group(function () {
            Route::get('/list', [CarController::class, 'index'])->name('cars.index');

            Route::get('{id}/create', [CarController::class, 'create'])->name('cars.create');
            Route::post('{id}/store', [CarController::class, 'store'])->name('cars.store');

            Route::post('/{id}/update', [CarController::class, 'update'])->name('cars.update');
            Route::post('/{id}/delete', [CarController::class, 'destroy'])->name('cars.destroy');
        })->middleware('permision:create_customer');

        //Reports
        Route::prefix('reports')->group(function () {
            Route::get('/list', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/{id}', [ReportController::class, 'show'])->name('reports.show');

            Route::get('/create', [ReportController::class, 'create'])->name('reports.create');
            Route::post('/store', [ReportController::class, 'store'])->name('reports.store');

            Route::post('/{id}/update', [ReportController::class, 'update'])->name('reports.update');
            Route::post('/{id}/delete', [ReportController::class, 'destroy'])->name('reports.destroy');
        });

        //Options
        Route::prefix('options')->group(function () {
            Route::get('/', [OptionsController::class, 'index'])->name('show.options');

            Route::middleware('permision:create_option')->group(function () {
                Route::get('/create', [OptionsController::class, 'create'])->name('create.option');
                Route::post('/create', [OptionsController::class, 'store'])->name('store.option');
            });

            Route::middleware('permision:edit_option')->group(function () {
                Route::get("/{id}", [OptionsController::class, 'edit'])->name('edit.option');
                Route::post("/{id}/update", [OptionsController::class, 'update'])->name('update.option');
                Route::post("/{id}/delete", [OptionsController::class, 'destroy'])->name('delete.option');
            });
        })->middleware('permision:create_option');

        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });

//pdf
    Route::get('pdf', [CustomerController::class, 'showPdf'])->name('show.pdf');
    Route::get('/Dpdf/{carId}', [CustomerController::class, 'pdf'])->name('download.pdf');


// //notification
//     Route::get("sendSMS" , function(){
//         $notification = app(Notification::class);
//         $user = App\Models\User::find(1);
//         $notification->sendSMS($user);        
//     });
});

require __DIR__.'/auth.php';