<?php

namespace App\Models;

use App\Models\Car;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone'];

    public function cars(){
        return $this->hasMany(Car::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function serviceCenters() {
        return $this->belongsToMany(ServiceCenter::class);
    }

    public function hasServiceCenter(ServiceCenter $serviceCenter) {
        return $this->serviceCenters->contains($serviceCenter);
    }
}
