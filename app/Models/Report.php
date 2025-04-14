<?php

namespace App\Models;

use App\Models\Car;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'car_id',
        'booking_id',
        'reports',
        'description'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
