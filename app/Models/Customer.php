<?php

namespace App\Models;

use App\Models\Cars;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone'];

    public function cars(){
        return $this->hasMany(Cars::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function serviceCenters() {
        return $this->belongsToMany(ServiceCenter::class);
    }

    public function hasServiceCenter(ServiceCenter $serviceCenter) {
        return $this->serviceSenters->contains($serviceCenter);
    }
}
