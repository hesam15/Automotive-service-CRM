<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\VerifyPhone;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

use Illuminate\Auth\Events\Registered;
use App\Http\Requests\User\UserStoreRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $token = VerifyPhone::where("user_phone", $validated['phone'])->first();

        if($token->is_used){
            $user = User::create($validated);
    
            $user->assignRole('adminstrator');
    
            event(new Registered($user));

            $token->delete();
    
            Auth::login($user);
    
            return redirect(route('serviceCenters.create', compact('user'),absolute: false));
        }
        
        return redirect()->back()->with("alert", ['کد احراز هویت تایید نشده است.', 'danger'])->withInput();

    }
}