<?php

namespace App\Services\Notification;

use App\SmsServiceInterface;
use App\Models\VerifyPhoneTokens;

class SmsVerificationService {
    protected $smsService;
    protected const VERIFICATION_PATTERN = "m7v01r9x9xc6f68";
    protected const EXPIRE_MINUTES = 5;

    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendVerificationCode(string $phone, string $code): bool
    {
        return $this->smsService->sendPattern(
            $phone,
            self::VERIFICATION_PATTERN,
            ['verify_code' => $code]
        );
    }

    public function verifyCode(string $phone, string $code): bool{
        $token = VerifyPhoneTokens::where('user_phone', $phone)->first();
        if ($token && $token->code === $code) {
            $token->used = true;
            $token->save();
            return true;
        }
        return false;
    }

    public function generateCode(): string
    {
        return str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function isExpired(\DateTime $createdAt): bool
    {
        $expirationTime = $createdAt->modify('+' . self::EXPIRE_MINUTES . ' minutes');
        return $expirationTime < new \DateTime();
    }
}