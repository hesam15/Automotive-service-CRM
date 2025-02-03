<?php

namespace App\Http\Controllers;

use App\Models\CarBody;
use App\Models\Cars;
use App\Models\Customer;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function create($id)
    {
        $customer = Customer::where('id', $id)->firstOrFail();
        return view('admin.car.create', compact('customer'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'plate_two' => 'required',
            'plate_letter' => 'required',
            'plate_three' => 'required',
            'plate_iran' => 'required',
        ]);

        $customer = Customer::findOrFail($request->id);
        $customerCars = Cars::where("customer_id", $request->customer_id)->pluck("license_plate")->toArray();

        $license_plate = $this->generateLicensePlate($request);

        if (!in_array($license_plate, $customerCars) && $customer->id == $request->customer_id) {
            $customer->cars()->create([
                "customer_id" => $customer->id,
                "name" => $request->name,
                'color' => $request->color,
                'license_plate' => $license_plate,
            ]);

            return redirect(route("bookings.create", $customer->id))->with("success", "ثبت خودرو با موفقیت انجام شد.");
        }

        return back()->with("error", "این خودرو قبلا ثبت شده است.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'plate_two' => 'required',
            'plate_letter' => 'required',
            'plate_three' => 'required',
            'plate_iran' => 'required',
        ]);

        $car = Cars::findOrFail($id);

        $license_plate = $this->generateLicensePlate($request);

        $car->update([
            'name' => $request->name,
            'color' => $request->color,
            'license_plate' => $license_plate,
        ]);

        return back()->with("success", "ویرایش خودرو با موفقیت انجام شد.");
    }

    public function destroy($id)
    {
        $car = Cars::findOrFail($id);
        $car->delete();

        return back()->with("success", "حذف خودرو با موفقیت انجام شد.");
    }

    private function generateLicensePlate(Request $request)
    {
        return $request->plate_two . '-' . $request->plate_letter . '-' . $request->plate_three . '-' . $request->plate_iran;
    }
}