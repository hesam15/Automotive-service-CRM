<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'thumbnail_path',
        'city_id',
        'address',
        'user_id',
        'fridays_off',
        'working_hours'
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function customers() {
        return $this->belongsToMany(Customer::class);
    }

    public function cars() {
        return $this->belongsToMany(Car::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function province() {
        return $this->hasOne(Province::class);
    }

    public function city() {
        return $this->hasOne(City::class);
    }
}
