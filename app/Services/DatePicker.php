<?php

namespace App\Services;

use App\Helpers\PersianConvertNumberHelper;
use App\Models\Booking;
use App\Models\ServiceCenter;
use App\Models\Setting;
use Carbon\Carbon;
use Hekmatinasser\Jalali\Jalali;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class DatePicker
{
    private $settings;
    private $workingHours;

    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Load settings from database
     */
    private function loadSettings()
    {
        $serviceCenter = ServiceCenter::where("id", auth()->user()->service_center_id)->first();

        $this->settings = [
            'fridays_closed' => $serviceCenter->fridays_off ?? false,
            'working_hours' => explode("-", $serviceCenter->working_hours) ?? null,
        ];
        // Parse working hours from settings or use default
        if ($this->settings['working_hours']) {
            $this->workingHours = [
                'start' => $this->settings['working_hours'][0],
                'end' => $this->settings['working_hours'][1],
                'interval' => 30,
            ];
        } else {
            $this->workingHours = [
                'start' => 9,
                'end' => 21,
                'interval' => 30,
            ];
        }
    }

    /**
     * Get available times for a specific date
     */
    public function getAvailableTimes(Request $request) {
        try {
            // تبدیل تاریخ از شمسی به میلادی
            $persianDate = (new PersianConvertNumberHelper($request->date))
                ->convertDateToEnglish()
                ->value;

            // بررسی روز جمعه
            $date = Carbon::parse($persianDate);
            if ($this->settings['fridays_closed'] && $date->dayOfWeek === Carbon::FRIDAY) {
                return response()->json([
                    'all' => [],
                    'available' => [],
                    'booked' => [],
                    'message' => 'این روز تعطیل است'
                ]);
            }

            // دریافت همه ساعت‌های کاری
            $allTimes = $this->generateTimeSlots();
            
            // دریافت ساعت‌های رزرو شده
            $bookedTimes = Booking::where('date', $persianDate)
                ->whereIn('status', ['pending', 'completed'])
                ->pluck('time_slot')
                ->toArray();

            // محاسبه ساعت‌های در دسترس
            $availableTimes = array_values(array_diff($allTimes, $bookedTimes));

            return response()->json([
                'all' => $allTimes,
                'available' => $availableTimes,
                'booked' => $bookedTimes,
                'settings' => [
                    'fridays_closed' => (bool) $this->settings['fridays_closed'],
                    'working_hours' => $this->workingHours,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطا در دریافت ساعات مراجعه',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate time slots based on working hours settings
     */
    private function generateTimeSlots()
    {
        $slots = [];

        // تبدیل ساعت شروع و پایان به دقیقه
        $startTime = $this->timeToMinutes($this->workingHours['start']);
        $endTime = $this->timeToMinutes($this->workingHours['end']);
        $interval = $this->workingHours['interval'];

        for ($minutes = $startTime; $minutes <= $endTime; $minutes += $interval) {
            $timeSlot = $this->minutesToTime($minutes);
            
            // بررسی زمان استراحت
            if (!$this->isInBreakTime($timeSlot)) {
                $slots[] = $timeSlot;
            }
        }
        return $slots;
    }

    /**
     * Convert time string to minutes
     */
    private function timeToMinutes(string $time): int
    {
        list($hours, $minutes) = explode(':', $time);
        return ($hours * 60) + $minutes;
    }

    /**
     * Convert minutes to time string
     */
    private function minutesToTime(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Check if given time is in break time
     */
    private function isInBreakTime(string $time): bool
    {
        if (!isset($this->workingHours['breaks'])) {
            return false;
        }

        $timeMinutes = $this->timeToMinutes($time);

        foreach ($this->workingHours['breaks'] as $break) {
            $breakStart = $this->timeToMinutes($break['start']);
            $breakEnd = $this->timeToMinutes($break['end']);

            if ($timeMinutes >= $breakStart && $timeMinutes < $breakEnd) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get datepicker settings
     */
    public function getSettings()
    {
        return response()->json([
            'fridays_closed' => (bool) $this->settings['fridays_closed'],
            'working_hours' => $this->workingHours,
        ]);
    }
}