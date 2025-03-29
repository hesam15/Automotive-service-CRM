<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'city_id',
        'address',
        'manager',
        'fridays_off',
        'working_hours'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function province() {
        return $this->hasOne(Province::class);
    }

    public function city() {
        return $this->hasOne(City::class);
    }
}
