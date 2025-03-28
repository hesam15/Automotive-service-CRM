<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PersianConvertNumberHelper;
use App\Http\Requests\Booking\BookingUpdateRequest;
use App\Http\Requests\Booking\BookingStoreRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function store(BookingStoreRequest $request, Customer $customer) {
        if ($customer->id != $request->customer_id) {
            return back()->with("error", "خطا در ثبت رزرو.");
        }

        if (Booking::isTimeSlotAvailable($request->date, $request->time_slot)) {
            return back()->with("error", "این زمان قبلا رزرو شده است.");
        }

        try {
            DB::transaction(function () use ($customer, $request) {
                $customer->bookings()->create([
                    "date" => $request->date,
                    "time_slot" => $request->time_slot,
                    "car_id" => $request->car_id,
                    "customer_id" => $request->customer_id,
                    "status" => "pending"
                ]);
            });
    
            return redirect()
                ->route('bookings.index')
                ->with("alert", ["رزرو با موفقیت ثبت شد.", "success"]);
    
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->with("alert", ["خطا در ثبت رزرو", "danger"]);
        }
    }

    // Update
    public function update(BookingUpdateRequest $request, Booking $booking) {
        if (Booking::isTimeSlotAvailable($request->date, $request->time_slot)) {
            return back()->with("alert", ["این زمان قبلا رزرو شده است.", "danger"]);
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                $booking->update([
                    "car_id" => $request->car_id,
                    "date" => $request->date,
                    "time_slot" => $request->time_slot,
                    "status" => $request->status,
                ]);
            });

            return back()->with("alert", ["ویرایش با موفقیت انجام شد.", "success"]);
        }
        catch (\Exception $e) {
            Log::error('Booking update failed: ' . $e->getMessage());
            return back()->with("alert", ["خطا در ویرایش رزرو", "danger"]);
        }
    }

    // Delete
    public function destroy(Booking $booking)
    {
        try {
            DB::transaction(function () use ($booking) {
                $booking->delete();
            });
    
            return back()->with('alert', ['حذف با موفقیت انجام شد.', 'success']);
        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return back()->with('alert', ['خطا در حذف رزرو', 'danger']);
        }
    }
}