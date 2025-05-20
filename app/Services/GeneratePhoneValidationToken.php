<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\VerifyPhoneTokens;

class GeneratePhoneValidationToken {
        /**
     * Generate a six digits code
     *
     * @param int $codeLength
     * @return string
     */
    public static function generateCode($codeLength = 6) {
        $max = pow(10, $codeLength);
        $min = $max / 10 - 1;
        $code = mt_rand($min, $max);
        return $code;
    }

    /**
     * True if the token is not used nor expired
     *
     * @return bool
     */
    public static function isValid($token) {
        return ! self::isUsed($token) && ! self::isExpired($token);
    }

    /**
     * Is the current token used
     *
     * @return bool
     */
    public static function isUsed($token) {
        return $token->used;
    }
    /**
     * Is the current token expired
     *
     * @return bool
     */
    public static function isExpired($token) {
        return $token->created_at->diffInMinutes(Carbon::now()) > VerifyPhoneTokens::EXPIRATION_TIME;
    }
}