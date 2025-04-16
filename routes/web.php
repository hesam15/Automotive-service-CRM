<?php

use App\Services\DatePicker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OptionsController;
use App\Http\Middleware\CheckServiceCenter;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceCenterController;
use App\Http\Controllers\Auth\VerifyPhoneController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function() {
    return redirect()->route('home');
});

//verify phone
Route::controller(VerifyPhoneController::class)->group(function () {
    Route::post('/send-verification-code', 'send');
    Route::post('/verify-code', 'verify');
});

Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('register', 'create')->name('register');
    Route::post('register', 'store')->name('registerUser');
});

Route::prefix('dashboard/serviceCenters/create')->name("serviceCenters.")->controller(ServiceCenterController::class)->group(function () {
    Route::get('/{user}', "create")->name("create");
    Route::post('/{user}', 'store')->name("store");
})->middleware(['auth', 'role:adminstrator', 'can:create_serviceCetners']);

Route::middleware(['auth', 'verified', CheckServiceCenter::class, 'role:adminstrator|expert|clerk'])->group(function () {    
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //Service Center
        Route::prefix('serviceCenters')->controller(ServiceCenterController::class)->name("serviceCenter.")->group(function () {
            Route::get('/edit/{serviceCenter}', 'edit')->name("edit");
            Route::put('/update/{serviceCenter}', 'update')->name("update");

            Route::post('/delete/{serviceCenter}', 'delete')->name("destroy");
        })->can('update', 'serviceCenter');

        //Users
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

        //Customers
        Route::prefix('/customers')->controller(CustomerController::class)->name('customers.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::prefix("/create")->group(function () {
                Route::view('/', 'admin.customers.create')->name('create');
                Route::post('/', 'store')->name('store');
            })->can('create_customers');

            Route::prefix('/{customer}')->group(function () {
                Route::get('/', 'show')->name('profile');
                Route::get('/bookings', 'bookings')->name('bookings')->can('view_bookings');
            })->can('show', 'customer');

            Route::group([], function () {
                Route::post('/{customer}', 'destroy')->name('destroy');
                Route::post('/{customer}/update', 'update')->name('update');
            })->can('update', 'customer');
        })->can('view_customers'); 

        //Bookings
        Route::prefix("bookings")->name('bookings.')->controller(BookingController::class)->group(function () {
            Route::get('/list', 'index')->name('index')->can('view_bookings');

            Route::prefix('/{customer}')->group(function() {
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
            })->can('create', 'booking');

            Route::prefix('/{customer}')->group(function () {
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('destroy');
    
                Route::post('/update-status', 'updateStatus')->name('updateStatus');
            })->can('update', 'booking');
        });

        //Cars
        Route::prefix('cars')->name('cars.')->controller(CarController::class)->group(function () {
            Route::get('/list', 'index')->name('index')->can('view_cars');

            Route::prefix('/{customer}')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
            })->can('create', 'car');

            Route::prefix('/{car}')->group(function () {
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'destroy')->name('destroy');
            })->can('update', 'car');
        });

        //Reports
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

        //Options
        Route::prefix('options')->controller(OptionsController::class)->name('options.')->group(function () {
            Route::get('/', 'index')->name('index')->can('view_options');

            Route::group([], function () {
                Route::view('/create', 'admin.options.create')->name('create');
                Route::post('/create', 'store')->name('store');
            })->can('create_options');

            Route::prefix('/{option}')->group(function () {
                Route::get("/", 'edit')->name('edit');
                Route::post("/update", 'update')->name('update');
                Route::post("/delete", 'destroy')->name('destroy');
            })->can('update', 'option');
        });

        Route::get('/datepicker-settings', [DatePicker::class, 'getSettings']);
        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });
});

require __DIR__.'/auth.php';