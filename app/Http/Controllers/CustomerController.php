<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Cars;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Helpers\PersianHelper;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();

        if ($request->has('search')) {
            $search = $request->search;
            $customers = $this->search($search);
        }

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view("admin.customers.create");
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $customer = Customer::create([
            "fullname" => $request->fullname,
            "phone" => $request->phone,
        ]);

        return redirect(route("customers.index"))->with("success", "مشتری با موفقیت اضافه شد.");
    }

    public function show($id)
    {
        $customer = Customer::with('cars', 'bookings')->findOrFail($id);

        $registrationTime = Jalalian::fromCarbon(Carbon::parse($customer->created_at))->format('Y/m/d');

        foreach ($customer->bookings as $booking) {
            $booking->date = Jalalian::fromCarbon(Carbon::parse($booking->date))->format('Y/m/d');
        }

        foreach ($customer->cars as $car) {
            $car->license_plate = explode('-', $car->license_plate);
        }

        return view("admin.customers.profile", compact('customer', 'registrationTime'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view("admin.customers.edit", compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        $customer = Customer::findOrFail($id);
        $customer->update([
            "fullname" => $request->fullname,
            "phone" => $request->phone,
        ]);

        return redirect(route("customers.index"))->with("success", "ویرایش با موفقیت انجام شد.");
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect(route('customers.index'))->with("success", "حذف مشتری با موفقیت انجام شد.");
    }

    private function validateRequest($request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:customers'],
        ]);
    }

    public function search($search)
    {
        return Customer::where('fullname', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->get();
    }
}