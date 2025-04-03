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

        $today = Booking::todayBookings();
        // Get counts for stats cards
        $customersCount = Customer::count();
        $todayBookings = Booking::whereDate('date', $today)->count();
        $reportsCount = Report::count();

        // Get recent bookings
        $recentBookings = Booking::with('customer')
            ->orderBy('date', 'desc')
            ->orderBy('time_slot', 'desc')
            ->take(5)
            ->get()
            ->map(function($booking) {
                $booking->date = (new PersianConvertNumberHelper($booking->date))->convertDateToPersinan()->getValue();
                return $booking;
            });

        return view('dashboard', compact(
            'customersCount',
            'todayBookings',
            'reportsCount',
            'recentBookings'
        ));
    }
}