<?php

namespace App\Models;

use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_center_id',
        'car_id',
        'date',
        'time_slot',
        'status',
        'obsolete',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function car() {
        return $this->belongsTo(Car::class);
    }

    public function serviceCenter() {
        return $this->belongsTo(ServiceCenter::class);
    }

    public static function isTimeSlotAvailable($date, $timeSlot)
    {
        return self::where('date', $date)
            ->where('time_slot', $timeSlot)
            ->where('status', 'active')
            ->exists();
    }

    public static function todayBookings(){
        $today = Verta::now()->format('Y/m/d');
        return $today;
    }
}
