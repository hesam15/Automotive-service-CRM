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

    public function store(Request $request) {


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

    // Update
    public function edit(User $user) {
        $roles = Cache::remember('roles', now()->addHour(), function() {
            return Role::select('id', 'persian_name');
        });

        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user) {
        $validated = $request->validated();

        $user->update($validated);
        $user->refreshRoles($validated['role']);

        return redirect()->route('users.index')->with("alert", ["کاربر با موفقیت ویرایش شد.", "success"]);
    }

    public function updatePhone(Request $request, User $user) {
        $data = $request->validate([
            'phone' => ['required', 'max:11', 'unique:users,phone,' . $user->id],
        ]);

        $token = VerifyPhone::where("user_phone", $data['phone'])->first();

        if ($token->is_used) {
            $user->update($data);
            return redirect()->back()->with('alert', ['شماره تلفن با موفقیت تغییر کرد.', "success"]);
        }

        return redirect()->back()->with('alert', ['کد احراز هویت تایید نشده است.', "danger"]);
    }

    // Delete
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}