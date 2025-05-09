<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'service_center_id',
        'name',
        'values',
    ];

    protected $casts = [
        'values' => 'array'
    ];

    public function serviceCeter() {
        return $this->belongsTo(ServiceCenter::class);
    }
}
