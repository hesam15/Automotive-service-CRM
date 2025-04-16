<?php

namespace App\Models;

use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'customer_id',
        'report_id',
        'image',
        'color',
        'license_plate',
    ];

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function serviceCenters() {
        return $this->belongsToMany(ServiceCenter::class);
    }
}
