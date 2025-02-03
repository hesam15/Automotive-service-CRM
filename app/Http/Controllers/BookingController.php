<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cars;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Helpers\PersianHelper;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Read
    public function index()
    {
        $bookings = Booking::with('car', 'customer')->orderBy('date', 'asc')->orderByRaw("CAST(time_slot AS TIME)")->get();
        foreach ($bookings as $booking) {
            $booking->date = Jalalian::fromCarbon(Carbon::parse($booking->date))->format('Y/m/d');
        }
        return view('admin.bookings.index', compact('bookings'));
    }

    public function customer($id)
    {
        $customer = Customer::where('id', $id)->firstOrFail();
        $bookings = Booking::with('car')
            ->where('customer_id', $customer->id)
            ->orderBy('date', 'asc')
            ->orderByRaw("CAST(time_slot AS TIME)")
            ->get();

        foreach ($bookings as $booking) {
            $booking->date = Jalalian::fromCarbon(Carbon::parse($booking->date))->format('Y/m/d');
        }

        foreach ($customer->cars as $car) {
            $car->license_plate = explode('-', $car->license_plate);
        }

        return view('admin.customers.bookings', compact('bookings', 'customer'));
    }

    // Create
    public function create($id)
    {
        $customer = Customer::where("id", $id)->firstOrFail();

        foreach ($customer->bookings as $booking) {
            $booking->date = Jalalian::fromCarbon(Carbon::parse($booking->date))->format('Y/m/d');
        }

        return view('admin.bookings.create', compact('customer'));
    }

    public function store(Request $request, $id)
    {
        $customer = Customer::where("id", $id)->firstOrFail();

        $date = $this->convertDate($request->date);

        if (Booking::isTimeSlotAvailable($date, $request->time_slot)) {
            return back()->with("error", "این زمان قبلا رزرو شده است.");
        }

        $request->validate([
            "car" => "required",
            "date" => "required",
            "time_slot" => "required",
        ]);

        DB::transaction(function () use ($customer, $request, $date) {
            $customer->bookings()->create([
                "date" => $date,
                "time_slot" => $request->time_slot,
                "customer_id" => $customer->id,
                "car_id" => $request->car,
            ]);
        });

        return back()->with("success", "رزرو با موفقیت انجام شد.");
    }

    // Update
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $date = $this->convertDate($request->date);

        if (Booking::isTimeSlotAvailable($date, $request->time_slot)) {
            return back()->with("error", "این زمان قبلا رزرو شده است.");
        }

        $request->validate([
            "car_id" => "required",
            "date" => "required",
            "time_slot" => "required",
            "status" => "required",
        ]);

        $booking->update([
            'car_id' => $request->car_id,
            "date" => $date,
            "time_slot" => $request->time_slot,
            'status' => $request->status,
        ]);

        return back()->with("success", "ویرایش با موفقیت انجام شد.");
    }

    // Delete
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with("success", "حذف با موفقیت انجام شد.");
    }

    // Helper
    private function convertDate($date)
    {
        $englishDate = PersianHelper::convertPersianToEnglish($date);
        return Jalalian::fromFormat('Y/m/d', $englishDate)->toCarbon()->format('Y-m-d');
    }
}