<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\Role;
use App\Models\User;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use App\Models\Booking;
use App\Models\Options;
use App\Models\Customer;
use App\Models\ServiceCenter;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('داشبورد', route('home'));
});

// Customer Form
Breadcrumbs::for('customer.form', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('فرم مشتری', route('customer.form'));
});

// Users
    // Users List
    Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('کاربران', route('users.index'));
    });

    // Create User
    Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
        $trail->parent('users.index');
        $trail->push('ایجاد کاربر', route('users.create'));
    });

    // Edit User
    Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail) {
        $user = User::where('id', request()->route('user')->id)->first();

        $trail->parent('users.index');
        $trail->push("{$user->name}", route('users.edit', $user));
    });

    // User Profile
    Breadcrumbs::for('users.profile', function (BreadcrumbTrail $trail) {
        $user = auth()->user();
        
        $trail->parent('home');
        $trail->push("{$user->name}", route('users.profile', $user->name));
    });


//Roles
    // Roles List
    Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('نقش‌ها', route('roles.index'));
    });

    // Create Role
    Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
        $trail->parent('roles.index');
        $trail->push('ایجاد نقش', route('roles.create')); 
    });

    // Edit Role
    Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail) {
        $role = Role::where('id', request()->route('role')->id)->first();

        $trail->parent('roles.index');
        $trail->push("{$role->persian_name}", route('roles.edit', $role));
    });

//Customers
    //Customer list
    Breadcrumbs::for('customers.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('لیست مشتریان', route('customers.index'));
    });
    // Customer show
    Breadcrumbs::for('customers.profile', function (BreadcrumbTrail $trail) {
        $customer = request()->route("customer");

        $trail->parent('customers.index');
        $trail->push("{$customer->fullname}", route('customers.profile', ['customer' => $customer->id]));
    });

    // Customer create
    Breadcrumbs::for('customers.create', function (BreadcrumbTrail $trail) {
        $trail->parent('customers.index');
        $trail->push('ایجاد مشتری', route('customers.create'));
    });

    //Customer Bookings
    Breadcrumbs::for('customers.bookings', function (BreadcrumbTrail $trail) {
        $customer = request()->route("customer");

        $trail->parent('customers.profile', ['id' => $customer->id]);
        $trail->push('رزرو ها', route('customers.bookings', ['customer' => $customer->id]));
    });

    //Cars create
    Breadcrumbs::for('cars.create', function (BreadcrumbTrail $trail) {
        $customer = request()->route("customer");

        $trail->parent('customers.profile', ['id' => $customer->id]);
        $trail->push('ایجاد خودرو', route('cars.create', ['customer' => $customer->id]));
    });

    //Booking
    //Bookings Index
    Breadcrumbs::for('bookings.create', function (BreadcrumbTrail $trail) {
        $customer = request()->route('customer');

        $trail->parent('customers.profile', ['customer' => $customer->id]);
        $trail->push('رزرو', route('bookings.create', ['customer' => $customer->id]));
    });

//Options
    // Options List
    Breadcrumbs::for('options.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('خدمات', route('options.index'));
    });

    // Create Option
    Breadcrumbs::for('options.create', function (BreadcrumbTrail $trail) {
        $trail->parent('options.index');
        $trail->push('ایجاد خدمت', route('options.create'));
    });

    // Edit Option
    Breadcrumbs::for('options.edit', function (BreadcrumbTrail $trail) {
        $option = Options::where('id', request()->route('id'))->first();

        $trail->parent('options.index');
        $trail->push($option->name, route('options.edit', $option->id));
    });

//Bookings
    //Bookings Index
    Breadcrumbs::for('bookings.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('تمام رزروی ها', route('bookings.index'));
    });

//Reports
    //Reports Create
    Breadcrumbs::for('report.create', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('ایجاد گزارش', route('report.create', ['booking' => request()->route('booking')]));
    });

    //Reports Index
    Breadcrumbs::for('report.index', function (BreadcrumbTrail $trail) {
        $booking = request()->route()->booking;
        $report = request()->route()->report;

        $trail->parent('home');
        $trail->push('گزارش '.$booking->customer->fullname , route('report.index', ['booking' => $booking->id, 'report' => $report->id]));
    });

//ServiceCenters
    // ServiceCenters List
    // Breadcrumbs::for('serviceCenters.index', function (BreadcrumbTrail $trail) {
    //     $trail->parent('home');
    //     $trail->push('مراکز خدمات', route('serviceCenter.index'));
    // });

    // Create ServiceCenter
    Breadcrumbs::for('serviceCenters.create', function (BreadcrumbTrail $trail) {
        $trail->push('ایجاد مرکز خدمات', route('serviceCenters.create', request()->route()->user->id));
    });

    // Edit ServiceCenter
    Breadcrumbs::for('serviceCenters.edit', function (BreadcrumbTrail $trail) {
        $serviceCenter = ServiceCenter::where('id', request()->route('serviceCenter')->id)->first();

        $trail->parent('serviceCenters.index');
        $trail->push("{$serviceCenter->name}", route('serviceCenter.edit', $serviceCenter));
    });

    // ServiceCenter Profile
    Breadcrumbs::for('serviceCenters.profile', function (BreadcrumbTrail $trail) {
        $serviceCenter = request()->route('serviceCenter');

        $trail->parent('serviceCenters.index');
        $trail->push("{$serviceCenter->name}", route('serviceCenters.profile', $serviceCenter));
    });

    // Edit ServiceCenter
    Breadcrumbs::for('serviceCenter.edit', function (BreadcrumbTrail $trail) {
        $serviceCenter = ServiceCenter::where('id', request()->route()->originalParameters()['serviceCenter'])->first();
        
        $trail->parent('home');
        $trail->push("ویرایش {$serviceCenter->name}", route('serviceCenter.edit', $serviceCenter));
    });
