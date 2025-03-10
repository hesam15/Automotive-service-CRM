<?php

namespace App\Http\Controllers;

use App\Helpers\LicensePlateHleper;
use App\Http\Requests\CarCreateRequest;
use App\Models\Cars;
use App\Models\Customer;
use Illuminate\Http\Request;

class CarController extends Controller {
    public function create(Customer $customer) {
        return view('admin.car.create', compact('customer'));
    }

    public function store(CarCreateRequest $request, Customer $customer) {        
        $carsPlates = Cars::all()->pluck('licence_plate')->toArray();

        $licensePlate = LicensePlateHleper::generate($request->only(['plate_iran', 'plate_letter', 'plate_three', 'plate_two']));

        if (!in_array($licensePlate, $carsPlates) && $customer->id == $request->customer_id) {
            $customer->cars()->create([
                "customer_id" => $customer->id,
                "name" => $request->name,
                'color' => $request->color,
                'license_plate' => $licensePlate,
            ]);

            return redirect(route("bookings.create", $customer->id))->with("success", "ثبت خودرو با موفقیت انجام شد.");
        }

        return back()->with("error", "این خودرو قبلا ثبت شده است.");
    }

    public function update(Request $request, Cars $car)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'plate_two' => 'required',
            'plate_letter' => 'required',
            'plate_three' => 'required',
            'plate_iran' => 'required',
        ]);

        $licensePlate = LicensePlateHleper::generate($request->only(['plate_iran', 'plate_letter', 'plate_three', 'plate_two']));

        $car->update([
            'name' => $request->name,
            'color' => $request->color,
            'license_plate' => $licensePlate,
        ]);

        return back()->with("success", "ویرایش خودرو با موفقیت انجام شد.");
    }

    public function destroy(Cars $car)
    {
        $car->delete();

        return back()->with("success", "حذف خودرو با موفقیت انجام شد.");
    }
}