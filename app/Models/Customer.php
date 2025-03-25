<?php

namespace App\Models;

use App\Models\Cars;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['fullname', 'phone', 'service_center_id'];

    public function cars(){
        return $this->hasMany(Cars::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function serviceSenter() {
        return $this->belongsTo(ServiceCenter::class);
    }
}
