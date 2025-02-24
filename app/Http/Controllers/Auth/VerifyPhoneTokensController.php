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

        $this->sendVerify($this->token);
    }

    //Verify Phone
    public function sendVerify($token) {
        if ($this->smsVerificationService->sendVerificationCode($token->user_phone, $token->code)) {
            session()->put("code_id", $token->id);
            session()->put("user_phone", $token->phone);

            return response()->json([
                'success' => true,
                'message' => 'کد تایید ارسال شد'
            ]);
        }

        $this->token->delete();
        
        return response()->json([
            'success' => false,
            'message' => 'خطا در ارسال کد تایید'
        ], 422);
    }
}