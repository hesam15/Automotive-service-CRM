<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BookingDateChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {             
        $nowDateTime = Carbon::now();

        $bookings = Booking::where("date", "<", $nowDateTime)->get();
    
        foreach ($bookings as $booking){ 
            $bookingDateTime = Carbon::parse($booking->date . " " . $booking->time_slot);
    
            if ($bookingDateTime->lt($nowDateTime) && $booking->status == "pending") {
                try {
                    $booking->status = "expired";
                    $result = $booking->save();
                } catch (\Exception $e) {
                    Log::error('خطا در به‌روزرسانی: ' . $e->getMessage());
                }
            } elseif (floor($bookingDateTime->diffInDays($nowDateTime)) >= 3 && $booking->status == "expired") {                    
                $deleteResult = $booking->delete();
                Log::info('نتیجه حذف رزرو: ' . ($deleteResult ? 'موفق' : 'ناموفق'));
            }
        }        
        
    }
}
