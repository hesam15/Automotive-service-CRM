<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifyPhoneTokens extends Model {
    use HasFactory;
    const EXPIRATION_TIME = 5;
    protected $fillable = [
        'code',
        'user_phone',
        'is_used'
    ];

    /**
     * User tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}