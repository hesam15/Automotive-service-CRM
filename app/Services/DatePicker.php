<?php

namespace App\Services;

use App\Helpers\PersianConvertNumberHelper;
use App\Models\Booking;
use Morilog\Jalali\Jalalian;
use Illuminate\Http\Request;

class DatePicker {
    public function getAvailableTimes(Request $request)
    {
        $date = (new PersianConvertNumberHelper($request->date))->convertPersianToEnglish()->convertDateToEnglish()->getValue();
        $times = [8, 21];
    
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