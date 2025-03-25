<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    public function users() {
        return $this->hasMany(User::class);
    }

    public function customers() {
        return $this->hasMany(Customer::class);
    }
}
