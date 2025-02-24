<?php

namespace App\Models;

use App\Models\Cars;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $fillable = [
        'car_id',
        'booking_id',
        'reports',
        'description'
    ];

    public function car()
    {
        return $this->belongsTo(Cars::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
