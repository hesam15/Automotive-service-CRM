<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifyPhone extends Model {
    use HasFactory;
    protected $table = 'verify_phone_tokens';

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