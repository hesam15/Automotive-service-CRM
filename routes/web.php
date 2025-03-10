<?php

use App\Models\User;
use App\Models\Booking;
use App\Services\DatePicker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyPhoneController;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Notifications\Notification as NotificationsNotification;

//verify phone
Route::middleware('guest')->group(function () {
    Route::post('/sendVerify', [VerifyPhoneController::class, 'create'])
        ->name('send.verification');
    Route::post('/verifyCode', [VerifyPhoneController::class, 'verify'])->name('verify.phone');
    
    // Existing routes remain unchanged
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('registerUser');
});


Route::middleware(['auth' , 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get("reportShow/{carId}",[CustomerController::class, 'show'])->name('show.customer.report');
    // Route::get('pdf', action: [CustomerController::class, 'pdf'])->name('download.pdf');
    Route::group(['prefix' => 'dashboard'], function () {

        //Users
        Route::prefix('users')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('/profile/{user}', 'profile')->name('user.profile');

            Route::middleware('permision:create_user')->group(function () {
                Route::get('/create', 'create')->name('users.create');
                Route::post('/create', 'store')->name('users.store');
            });
            Route::post('/{user}/delete', 'destroy')->name('users.destroy')->middleware('permision:delete_user');
            Route::middleware('permision:edit_customer')->group(function () {
                Route::get('/{user}/edit', 'edit')->name('users.edit');
                Route::post('/{user}/edit', 'update')->name('users.update');
                Route::post('/{user}/updatePhone', 'updatePhone')->name('users.update.phone');
                //asign role to user
                Route::post('/{user}/asignRole', 'assignRole')->name('users.asignRole');
            });


        });

        //Roles
        Route::prefix('roles')->controller(RoleController::class)->group(function () {
            Route::get('/', 'index')->name('roles.index');

            Route::get('/create', 'create')->name('roles.create');
            Route::post('/create', 'store')->name('roles.store');

            Route::middleware('permision:edit_role')->group(function () {
                Route::get("/{role}/edit", 'edit')->name('roles.edit');
                Route::post("/{role}/edit", 'update')->name('roles.update');
            });

            Route::post("/{role}/delete", 'destroy')->name('roles.destroy')->middleware('permision:delete_role');
        })->middleware('permision:create_role');

        //Customers
        Route::prefix('customers')->controller(CustomerController::class)->name('customers.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::view('/create', 'admin.customers.create')->name('create');
            Route::post('/store', 'store')->name('store');

            Route::get('/{customer}', 'show')->name('profile');
            Route::get('/{customer}/bookings', 'bookings')->name('bookings');

            Route::middleware('permision:edit_customer')->group(function () {
                Route::post('/{customer}', 'destroy')->name('destroy');
                Route::post('/{customer}/update', 'update')->name('update');
            });
        })->middleware('permision:create_customer'); 

        //Bookings
        Route::prefix("bookings")->controller(BookingController::class)->group(function () {
            Route::get('/list', 'index')->name('bookings.index');
            // Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');

            Route::get('/{customer}/create', 'create')->name('bookings.create');
            Route::post('/{customer}/store', 'store')->name('bookings.store');

            Route::post('/{customer}/update', 'update')->name('bookings.update');
            Route::post('/{customer}/delete', 'destroy')->name('bookings.destroy');

            Route::post('/{customer}/updateStatus', 'updateStatus')->name('bookings.updateStatus');
        })->middleware('permision:create_customer');

        //Cars
        Route::prefix('cars')->controller(CarController::class)->group(function () {
            Route::get('/list', 'index')->name('cars.index');

            Route::get('{customer}/create', 'create')->name('cars.create');
            Route::post('{customer}/store', 'store')->name('cars.store');

            Route::post('/{id}/update', 'update')->name('cars.update');
            Route::post('/{id}/delete', 'destroy')->name('cars.destroy');
        })->middleware('permision:create_customer');

        //Reports
        Route::prefix('report')->controller(ReportController::class)->name('report.')->group(function () {
            Route::get('bookings/{booking}/reports/{report}', 'index')->name('index');

            Route::get('/{booking}/create', 'create')->name('create');
            Route::post('/{booking}/store', 'store')->name('store');
            
            Route::post('/{car}/{id}/update', 'update')->name('update');
            Route::post('/{car}/{id}/delete', 'destroy')->name('destroy');
            
            Route::get('reports/{report}/print', 'print')->name('print');
        });

        //Options
        Route::prefix('options')->controller(OptionsController::class)->name('options.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::middleware('permision:create_option')->group(function () {
                Route::view('/create', 'admin.options.create')->name('create');
                Route::post('/create', 'store')->name('store');
            });

            Route::middleware('permision:edit_option')->group(function () {
                Route::get("/{option}", 'edit')->name('edit');
                Route::post("/{option}/update", 'update')->name('update');
                Route::post("/{option}/delete", 'destroy')->name('destroy');
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