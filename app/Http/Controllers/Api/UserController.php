<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\VerifyPhone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller {

    public function show(User $user) {
        return new UserResource($user);
    }

    public function index() {
        return UserResource::collection(User::all());
    }

    public function store(UserStoreRequest $request) {
        $user = User::create($request->all());

        if ($request['role']){
            $user->assignRole(1);
        }
        else {
            return redirect()->back()->with('alert', ['نقشی انتخاب نشده است.', 'danger']);
        }

        $token = $user->createToken($request->token)->plainTextToken;

        return json_encode($token);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('api.users.index');
    }
}