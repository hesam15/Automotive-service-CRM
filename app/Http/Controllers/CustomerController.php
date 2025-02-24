<?php

namespace App\Http\Controllers;

use App\Helpers\PersianConvertNumberHelper;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Cars;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Validator;
use Pest\ArchPresets\Custom;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    public function store(Request $request) {
        $this->validateRequest($request);

        try {
            $customer = Customer::create([
                "fullname" => $request->fullname,
                "phone" => $request->phone,
            ]);
            
            return redirect()->route('customers.index')->with("success", "مشتری با موفقیت اضافه شد.");
        }
        catch (\Exception $e) {
            return redirect()->route("customers.index")->with("error", "اضافه کردن مشتری با ارور مواجه شد.");
        }
    }

    public function show(Customer $customer) {
        $registrationTime = (new PersianConvertNumberHelper($customer->created_at))->convertDateToPersinan()->getValue();

        foreach ($customer->bookings as $booking) {
            $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
        }

        foreach ($customer->cars as $car) {
            $car->license_plate = explode('-', $car->license_plate);
        }

        return view("admin.customers.profile", compact('customer', 'registrationTime'));
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

            return redirect(route("customers.index"))->with("success", "ویرایش با موفقیت انجام شد.");
        }
        catch (\Exception $e) {
            return redirect()->route("customers.index")->with("error", "ویرایش مشتری با ارور مواجه شد.");
        }
    }

    public function destroy(Customer $customer) {
        $customer->delete();

        return redirect(route('customers.index'))->with("success", "حذف مشتری با موفقیت انجام شد.");
    }

    private function validateRequest($request) {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:customers'],
        ]);
    }
}