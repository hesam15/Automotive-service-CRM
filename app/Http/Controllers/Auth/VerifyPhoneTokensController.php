<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\GenerateToken;
use Illuminate\Validation\Rules;
use App\Models\VerifyPhoneTokens;
use function Laravel\Prompts\error;
use App\Http\Controllers\Controller;
use App\Services\Notification\SmsService;
use App\Services\Notification\SmsVerifyCode;
use App\Services\Notification\SmsVerificationService;

class VerifyPhoneTokensController extends Controller
{
    private $token;
    private $smsVerificationService;

    public function __construct(SmsVerificationService $smsVerificationService){
        $this->smsVerificationService = $smsVerificationService;
    }

    public function create(Request $request){
        $request->validate([
            'phone' => ['required', 'max:11' ,'unique:'.User::class],
        ]);

        $data = $request->only('phone');

        $this->token = VerifyPhoneTokens::where("user_phone", $data['phone'])->first();

        if($this->token){
            $this->token->delete();
        }

        $code = GenerateToken::generateCode();

        $this->token = VerifyPhoneTokens::create([
            'code' => $code,
            'user_phone' => $data['phone'],
        ]);

        return $this->sendVerify($this->token);
    }

    //Verify Phone
    public function sendVerify($token) {
        if ($this->smsVerificationService->sendVerificationCode($token->user_phone, $token->code)) {
            session([
                'verification_code' => $token->code,
                'verification_phone' => $token->user_phone
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'کد تایید با موفقیت ارسال شد',
                'data' => [
                    'timeout' => 300 // زمان اعتبار کد به ثانیه
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