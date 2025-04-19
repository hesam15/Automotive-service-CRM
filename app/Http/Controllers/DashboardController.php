<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\Booking;
use App\Models\Customer;
use Spatie\Permission\Models\Role;
use Illuminate\Container\Attributes\Auth;
use App\Helpers\PersianConvertNumberHelper;
use App\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $serviceCenter = auth()->user()->serviceCenter;
        $reportsCount = 0;

        foreach($serviceCenter->customers as $customer) {
            foreach($customer->cars as $car) {
                $reportsCount += $car->reports->count();
            }
        }

        $today = Booking::todayBookings();
        // Get counts for stats cards
        $customersCount = $serviceCenter->customers->count();
        $todayBookings = Booking::whereDate('date', $today)->count();

        // Get recent bookings
        $recentBookings = Booking::where('service_center_id', $serviceCenter->id)->with('customer')
            ->orderBy('date', 'desc')
            ->orderBy('time_slot', 'desc')
            ->take(5)
            ->get()
            ->map(function($booking) {
                $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
                return $booking;
            });

        return view('admin.dashboard', compact(
            'customersCount',
            'todayBookings',
            'reportsCount',
            'recentBookings'
        ));
    }
}