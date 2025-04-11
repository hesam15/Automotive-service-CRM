<?php

use App\Services\DatePicker;
use App\Models\ServiceCenter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RoleController;
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

//verify phone
Route::post('/send-verification-code', [VerifyPhoneController::class, 'send']);

Route::post('/verify-code', [VerifyPhoneController::class, 'verify']);


// Existing routes remain unchanged
Route::get('register', [RegisteredUserController::class, 'create'])
->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
->name('registerUser');

Route::get('/', function() {
    return redirect()->route("home");
});

Route::prefix('dashboard/serviceCenters/create')->name("serviceCenters.")->middleware(['auth', 'role:adminstrator'])->controller(ServiceCenterController::class)->group(function () {
    Route::get('/{user}', "create")->name("create");
    Route::post('/{user}', 'store')->name("store");
});

Route::middleware(['auth', 'verified', CheckServiceCenter::class, 'role:adminstrator|expert|clerk'])->group(function () {    
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //Service Center
        Route::prefix('serviceCenters')->controller(ServiceCenterController::class)->name("serviceCenter.")->group(function () {
            Route::get('/edit/{serviceCenter}', 'edit')->can('edit_serviceCenters')->name("edit");
            Route::put('/update/{serviceCenter}', 'update')->can('edit_serviceCenters')->name("update");

            Route::post('/destroy/{serviceCenter}', 'destroy')->can('delete_serviceCenters')->name("destroy");
        });

        //Users
        Route::prefix('users')->controller(UserController::class)->name("users.")->group(function () {
            Route::get('/', 'index')->name('index')->can('view_users');
            Route::get('/profile', 'profile')->name('profile');

            Route::can('create_users')->prefix("/create")->group(function () {
                Route::get('/', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::post('/{user}/delete', 'destroy')->name('destroy')->can('delete_users');

            Route::can('edit_users')->group(function () {
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::post('/{user}/edit', 'update')->name('update');
                Route::post('/{user}/updatePhone', 'updatePhone')->name('update.phone');
                //asign role to user
                Route::post('/{user}/asignRole', 'assignRole')->name('asignRole');
            });

            Route::get('/create/apiKey', 'createApiKey')->name("create.api-key")->can('create_api_key');
        });

        //Customers
        Route::prefix('/service-ceter/{serviceCenter}/customers')->controller(CustomerController::class)->name('customers.')->group(function () {
            Route::middleware('can:index,customer')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{customer}', 'show')->name('profile');
                Route::get('/{customer}/bookings', 'bookings')->name('bookings')->can('view_bookings');
            });

            Route::prefix("/create")->can('create_customers')->group(function () {
                Route::view('/', 'admin.customers.create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('can:update,customer')->group(function () {
                Route::post('/{customer}', 'destroy')->name('destroy');
                Route::post('/{customer}/update', 'update')->name('update');
            });
        })->can('index', 'customer'); 

        //Bookings
        Route::prefix("bookings")->name('bookings.')->controller(BookingController::class)->group(function () {
            Route::get('/list', 'index')->name('index');
            // Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');

            Route::get('/{customer}/create', 'create')->name('create');
            Route::post('/{customer}/store', 'store')->name('store');

            Route::can('edit_bookings')->group(function () {
                Route::post('/{customer}/update', 'update')->name('update');
                Route::post('/{customer}/delete', 'destroy')->name('destroy');
    
                Route::post('/{customer}/updateStatus', 'updateStatus')->name('updateStatus');
            });
        });

        //Cars
        Route::prefix('cars')->controller(CarController::class)->group(function () {
            Route::get('/list', 'index')->name('cars.index');

            Route::get('{customer}/create', 'create')->name('cars.create');
            Route::post('{customer}/store', 'store')->name('cars.store');

            Route::post('/{id}/update', 'update')->name('cars.update');
            Route::post('/{id}/delete', 'destroy')->name('cars.destroy');
        })->can('create_customer');

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

            Route::can('create_option')->group(function () {
                Route::view('/create', 'admin.options.create')->name('create');
                Route::post('/create', 'store')->name('store');
            });

            Route::can('edit_option')->group(function () {
                Route::get("/{option}", 'edit')->name('edit');
                Route::post("/{option}/update", 'update')->name('update');
                Route::post("/{option}/delete", 'destroy')->name('destroy');
            });
        })->can('create_option');

        Route::get('/datepicker-settings', [DatePicker::class, 'getSettings']);
        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });

    Route::get("reportShow/{carId}",[CustomerController::class, 'show'])->name('show.customer.report');
});

require __DIR__.'/auth.php';