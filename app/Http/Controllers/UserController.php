<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerifyPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Role;

class UserController extends Controller
{
    public function index() {
        $users = User::where('service_center_id', auth()->user()->serviceCenter->id)->get();
        $users->load('roles');

        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function profile() {
        $user = auth()->user();
        return view('admin.users.profile', compact('user'));
    }

    // Create
    public function create() {
        $roles = Cache::remember('roles', now()->addHour(), function() {
            return Role::select('name', 'persian_name')->get();
        });

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request) {
        $token = VerifyPhone::where("user_phone", $request['phone'])->first();

        if (!$token || $token->used) {
            return redirect()->back()->with('alert', ['کد احراز هویت تایید نشده است.', 'danger'])->withInput();
        }
        
        $request->merge([
            'service_center_id' => auth()->user()->service_center_id
        ]);

        $user = User::create($request->all());

        if ($request['role']){
            $user->assignRole($request['role']);
        } else {
            return redirect()->back()->with('alert', ['نقشی انتخاب نشده است.', 'danger']);
        }

        $token->delete();

        return redirect()->route('users.index')->with("alert", ["کاربر جدید با موفقیت ایجاد شد.", "success"]);
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
        $user->syncRoles($validated['role']);

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
        if($user->hasRole('adminstrator') && count($user->serviceCenter->users) == 1) {
            $user->serviceCenter->delete();
        }

        $user->delete();

        return redirect()->route('users.index')->with("alert", ['کاربر با موفقیت حذف شد.', 'danger']);
    }

    public function createApiKey() {
        $user = auth()->user();

        if($user->tokens()) {
            $user->tokens()->delete();
        }

        $token = $user->createToken($user->serviceCenter->name)->plainTextToken;

        return response()->json([
            'api_key' => $token
        ]);    
    }
}