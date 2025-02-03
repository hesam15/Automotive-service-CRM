<?php

namespace App\Services;

use App\Models\Booking;
use Morilog\Jalali\Jalalian;
use App\Helpers\PersianHelper;
use Illuminate\Http\Request;

class DatePicker {
    public function getAvailableTimes(Request $request)
    {
        $date = PersianHelper::convertPersianToEnglish($request->date);
        $date = Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
        $times = [8, 20];
    
        // دریافت همه ساعت‌ها به صورت associative array
        $allTimes = $this->allTimes($times);
        
        // دریافت و تبدیل ساعت‌های رزرو شده به associative array
        $bookedTimes = Booking::where('date', $date)
            ->pluck('time_slot')
            ->toArray();
    
        // حذف ساعت‌های رزرو شده
        $availableTimes = array_diff_key($allTimes, $bookedTimes);
    
        return response()->json([
            'all' => $allTimes,
            'available' => $availableTimes,
            'booked' => $bookedTimes
        ]);
    }    
    
       
    public function allTimes($times)
    {
        $allTimes = [];
        
        for ($hour = $times[0]; $hour <= $times[1]; $hour++) {
            $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $allTimes[] = "$formattedHour:00";
            if ($hour < $times[1]) {
                $allTimes[] = "$formattedHour:30";
            }
        }
        return $allTimes;
    }
}