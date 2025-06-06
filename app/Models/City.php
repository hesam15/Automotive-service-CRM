<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'province_id'];

    public function serviceCenters() {
        return $this->belongsTo(ServiceCenter::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }
}
