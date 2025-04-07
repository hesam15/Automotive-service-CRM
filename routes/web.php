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

Route::get('serviceCenters/create/{user}', [ServiceCenterController::class, "create"])->name("serviceCenter.create");
Route::post('serviceCenters/create/{user}', [ServiceCenterController::class, "store"])->name("serviceCenter.store");

Route::get('/', function() {
    return redirect()->route("home");
});

Route::middleware(['auth' , 'verified', CheckServiceCenter::class])->group(function () {    
    Route::get("reportShow/{carId}",[CustomerController::class, 'show'])->name('show.customer.report');
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        //Service Center
        Route::prefix('serviceCenters')->controller(ServiceCenterController::class)->name("serviceCenter.")->group(function () {
            Route::get('/edit/{serviceCenter}', 'edit')->name("edit");
            Route::put('/update/{serviceCenter}', 'update')->name("update");

            Route::post('/destroy/{serviceCenter}', 'destroy')->name("destroy");
        });


        //Users
        Route::prefix('users')->controller(UserController::class)->name("users.")->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/profile/{user}', 'profile')->name('profile');

            Route::middleware('can:create_user')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/create', 'store')->name('store');
            });

            Route::post('/{user}/delete', 'destroy')->name('destroy')->middleware('can:delete_user');

            Route::middleware('can:edit_customer')->group(function () {
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::post('/{user}/edit', 'update')->name('update');
                Route::post('/{user}/updatePhone', 'updatePhone')->name('update.phone');
                //asign role to user
                Route::post('/{user}/asignRole', 'assignRole')->name('asignRole');
            });

            Route::get('/create/apiKey', 'createApiKey')->name("create.api-key");
        });

        //Customers
        Route::prefix('customers')->controller(CustomerController::class)->name('customers.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::view('/create', 'admin.customers.create')->name('create');
            Route::post('/store', 'store')->name('store');

            Route::get('/{customer}', 'show')->name('profile');
            Route::get('/{customer}/bookings', 'bookings')->name('bookings');

            Route::middleware('can:edit_customer')->group(function () {
                Route::post('/{customer}', 'destroy')->name('destroy');
                Route::post('/{customer}/update', 'update')->name('update');
            });
        })->middleware('can:create_customer'); 

        //Bookings
        Route::prefix("bookings")->controller(BookingController::class)->group(function () {
            Route::get('/list', 'index')->name('bookings.index');
            // Route::get('/{id}', [BookingController::class, 'show'])->name('bookings.show');

            Route::get('/{customer}/create', 'create')->name('bookings.create');
            Route::post('/{customer}/store', 'store')->name('bookings.store');

            Route::post('/{customer}/update', 'update')->name('bookings.update');
            Route::post('/{customer}/delete', 'destroy')->name('bookings.destroy');

            Route::post('/{customer}/updateStatus', 'updateStatus')->name('bookings.updateStatus');
        })->middleware('can:create_customer');

        //Cars
        Route::prefix('cars')->controller(CarController::class)->group(function () {
            Route::get('/list', 'index')->name('cars.index');

            Route::get('{customer}/create', 'create')->name('cars.create');
            Route::post('{customer}/store', 'store')->name('cars.store');

            Route::post('/{id}/update', 'update')->name('cars.update');
            Route::post('/{id}/delete', 'destroy')->name('cars.destroy');
        })->middleware('can:create_customer');

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

            Route::middleware('can:create_option')->group(function () {
                Route::view('/create', 'admin.options.create')->name('create');
                Route::post('/create', 'store')->name('store');
            });

            Route::middleware('can:edit_option')->group(function () {
                Route::get("/{option}", 'edit')->name('edit');
                Route::post("/{option}/update", 'update')->name('update');
                Route::post("/{option}/delete", 'destroy')->name('destroy');
            });
        })->middleware('can:create_option');

        Route::get('/datepicker-settings', [DatePicker::class, 'getSettings']);
        Route::get('/available-times', [DatePicker::class, 'getAvailableTimes']);
    });

//pdf
    Route::get('pdf', [CustomerController::class, 'showPdf'])->name('show.pdf');
    Route::get('/Dpdf/{carId}', [CustomerController::class, 'pdf'])->name('download.pdf');

    Route::get('/test/{serviceCenter}', [ServiceCenterController::class, 'show']);


// //notification
//     Route::get("sendSMS" , function(){
//         $notification = app(Notification::class);
//         $user = App\Models\User::find(1);
//         $notification->sendSMS($user);        
//     });
});

require __DIR__.'/auth.php';