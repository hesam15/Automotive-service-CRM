<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cars;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PersianConvertNumberHelper;
use Pest\ArchPresets\Custom;

class BookingController extends Controller
{
    // Read
    public function index() {
        $bookings = Booking::with('car', 'customer')->orderBy('date', 'asc')->orderByRaw("CAST(time_slot AS TIME)")->get();
        foreach ($bookings as $booking) {
            $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
        }
        return view('admin.bookings.index', compact('bookings'));
    }

    public function customer(Customer $customer) {
        $customer->load(['bookings' => function ($query) {
            $query->orderBy('date', 'asc')->orderByRaw("CAST(time_slot AS TIME)");
        }]);

        foreach ($customer->bookings as $booking) {
            $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
        }

        foreach ($customer->cars as $car) {
            $car->license_plate = explode('-', $car->license_plate);
        }

        return view('admin.customers.bookings', compact('customer'));
    }

    // Create
    public function create(Customer $customer) {
        return view('admin.bookings.create', compact('customer'));
    }

    public function store(Request $request, Customer $customer) {
        $date = (new PersianConvertNumberHelper($request->date))->convertPersianToEnglish()->convertDateToEnglish()->getValue();
        $request->merge(['date' => $date]);

        $request->validate([
            'date' => 'required|date_format:Y/m/d',
            'time_slot' => 'required',
            'car' => 'required|exists:cars,id'
        ]);
        
        if (Booking::isTimeSlotAvailable($date, $request->time_slot)) {
            return back()->with("error", "این زمان قبلا رزرو شده است.");
        }
    
        try {
            DB::transaction(function () use ($customer, $request, $date) {
                $customer->bookings()->create([
                    "date" => $date,
                    "time_slot" => $request->time_slot,
                    "car_id" => $request->car,
                ]);
            });
    
            return redirect()
                ->route('bookings.index')
                ->with("success", "رزرو با موفقیت ثبت شد.");
    
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->with("error", "خطا در ثبت رزرو");
        }
    }

    // Update
    public function update(Request $request, Booking $booking) {
        $date = (new PersianConvertNumberHelper($request->date))->convertPersianToEnglish()->getValue();

        if (Booking::isTimeSlotAvailable($date, $request->time_slot)) {
            return back()->with("error", "این زمان قبلا رزرو شده است.");
        }

        $request->validate([
            "car_id" => "required|exists:cars,id",
            "date" => "required|date_format:Y/m/d",
            "time_slot" => "required",
            "status" => "required|in:pending,completed",
        ]);

        try {
            DB::transaction(function () use ($booking, $request, $date) {
                $booking->update([
                    "car_id" => $request->car_id,
                    "date" => $date,
                    "time_slot" => $request->time_slot,
                    "status" => $request->status,
                ]);
            });

            return back()->with("success", "ویرایش با موفقیت انجام شد.");
        }
        catch (\Exception $e) {
            Log::error('Booking update failed: ' . $e->getMessage());
            return back()->with("error", "خطا در ویرایش رزرو");
        }
    }

    // Delete
    public function destroy(Booking $booking)
    {
        try {
            DB::transaction(function () use ($booking) {
                $booking->delete();
            });
    
            return back()->with('success', 'حذف با موفقیت انجام شد.');
        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return back()->with('error', 'خطا در حذف رزرو');
        }
    }
}