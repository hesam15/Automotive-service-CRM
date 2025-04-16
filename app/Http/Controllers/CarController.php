<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Helpers\LicensePlateHleper;
use App\Http\Requests\CarStoreRequest;

class CarController extends Controller {
    public function create(Customer $customer) {
        return view('admin.car.create', compact('customer'));
    }

    public function store(CarStoreRequest $request, Customer $customer) {        
        $carPlates = Car::all();

        $licensePlate = LicensePlateHleper::generate($request->only(['plate_iran', 'plate_letter', 'plate_three', 'plate_two']));

        $serviceCenter = auth()->user()->serviceCenter;

        if (!$carPlates->contains('license_plate' ,$licensePlate) && !$serviceCenter->cars->contains('license_plate' ,$licensePlate)) {
            $car = Car::create([
                "customer_id" => $customer->id,
                "name" => $request->name,
                'color' => $request->color,
                'license_plate' => $licensePlate,
            ]);

            $car->serviceCenters()->attach($serviceCenter);

            return redirect(route("bookings.create", $customer->id))->with("alert", ["ثبت خودرو با موفقیت انجام شد.", "success"]);
        } elseif ($carPlates->contains('license_plate' ,$licensePlate) && !$serviceCenter->cars->contains('license_plate' ,$licensePlate)) {
            $car = Car::where('license_plate', $licensePlate)->first();

            $car->serviceCenters()->syncWithoutDetaching(auth()->user()->serviceCenter);

            return redirect(route("bookings.create", $customer->id))->with("alert", ["ثبت خودرو با موفقیت انجام شد.", "success"]);
        }

        $car = Car::where('license_plate', $licensePlate)->first();

        return redirect()->route('customers.profile', $car->customer)->with("alert", ["این خودرو قبلا ثبت شده است.", 'danger'])->with('car', $car->id);
    }

    public function update(Request $request, Car $car)
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

        return back()->with("alert", ["ویرایش خودرو با موفقیت انجام شد.", "success"]);
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return back()->with("alert", ["حذف خودرو با موفقیت انجام شد.", "success"]);
    }
}