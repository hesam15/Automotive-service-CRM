<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\LicensePlateHleper;
use App\Helpers\PersianConvertNumberHelper;
use App\Http\Requests\CustomerStoreRequest;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer) {
        $registrationTime = (new PersianConvertNumberHelper($customer->created_at))->convertDateToPersinan()->getValue();

        foreach ($customer->bookings as $booking) {
            $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
        }

        foreach ($customer->cars as $car) {
            $car->license_plate = LicensePlateHleper::show($car->license_plate);
        }

        return view("admin.customers.profile", compact('customer', 'registrationTime'));
    }

    public function bookings(Customer $customer) {
        $bookings = Booking::where('customer_id', $customer->id)->get();

        foreach ($bookings as $booking) {
            $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->value;
        }

        return view('admin.customers.bookings', compact('customer', 'bookings'));
    }

    public function store(CustomerStoreRequest $request) {
        try {
            $customer = User::create([
                "name" => $request->fullname,
                "phone" => $request->phone,
                "email" => "example@gmail.com",
                "password" => 12345678,
                "service_center_id" => auth()->user()->service_center_id
            ]);

            $customer->assignRole('customer');
            
            return redirect()->route('customers.index')->with("alert", ["مشتری با موفقیت اضافه شد.", 'success']);
        }
        catch (\Exception $e) {
            return dd($e->getMessage());
            return redirect()->route("customers.index")->with("alert", ["اضافه کردن مشتری با ارور مواجه شد.", 'danger']);
        }
    }

    public function edit(Customer $customer) {
        return view("admin.customers.edit", compact('customer'));
    }

    public function update(Request $request, Customer $customer) {
        $this->validateRequest($request);

        try {
            $customer->update([
                "fullname" => $request->fullname,
                "phone" => $request->phone,
            ]);

            return redirect(route("customers.index"))->with("alert", ["ویرایش با موفقیت انجام شد.", 'success']);
        }
        catch (\Exception $e) {
            return redirect()->route("customers.index")->with("alert", ["ویرایش مشتری با ارور مواجه شد.", 'danger']);
        }
    }

    public function destroy(Customer $customer) {
        $customer->delete();

        return redirect(route('customers.index'))->with("alert", ["حذف مشتری با موفقیت انجام شد.","danger"]);
    }
}