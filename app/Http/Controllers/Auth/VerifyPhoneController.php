<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\VerifyPhone;
use Illuminate\Http\Request;
use App\Services\GenerateToken;
use Illuminate\Validation\Rules;
use App\Models\VerifyPhoneTokens;
use function Laravel\Prompts\error;
use App\Http\Controllers\Controller;
use App\Services\Notification\SmsService;
use App\Services\Notification\SmsVerifyCode;
use App\Services\Notification\SmsVerificationService;

class VerifyPhoneController extends Controller
{
    private $token;
    private $smsVerificationService;

    public function __construct(SmsVerificationService $smsVerificationService){
        $this->smsVerificationService = $smsVerificationService;
    }

    public function send(Request $request){
        $request->validate([
            'phone' => ['required', 'max:11' ,'unique:'.User::class],
        ]);

        $data = $request->only('phone');

        $this->token = VerifyPhone::where("user_phone", $data['phone'])->first();

        if($this->token){
            $this->token->delete();
        }

        $code = GenerateToken::generateCode();

        $this->token = VerifyPhone::create([
            'code' => $code,
            'user_phone' => $data['phone'],
        ]);

        if ($this->smsVerificationService->sendVerificationCode($this->token->user_phone, $this->token->code)) {
            session([
                'verification_code' => $this->token->code,
                'verification_phone' => $this->token->user_phone
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'کد تایید با موفقیت ارسال شد',
                'data' => [
                    'timeout' => 500 // زمان اعتبار کد به ثانیه
                ]
            ], 200);
        }

        $this->token->delete();
        
        return response()->json([
            'success' => false,
            'message' => 'خطا در ارسال کد تایید'
        ], 422);    
    }

    public function verify(Request $request) {
        $request->validate([
            'code' => ['required', 'max:6'],
        ]);

        $data = $request->only('code');
        $phone = session('verification_phone');

        if ($this->smsVerificationService->verifyCode($phone, $data['code'])) {
            return response()->json([
                'message' => 'شماره تلفن شما با موفقیت تایید شد.',
                'success' => true
            ]);
        }

        return response()->json([
            'message' => 'کد تایید اشتباه است.',
            'success' => false
        ], 422);
    }
}