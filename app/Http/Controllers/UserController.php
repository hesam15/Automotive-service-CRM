<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\VerifyPhone;
use Illuminate\Http\Request;
use App\Models\VerifyPhoneTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\Auth\VerifyPhoneController;
use App\Http\Requests\User\UserStoreRequest as UserUserStoreRequest;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        $users->load('role');

        $roles = Role::all();
        
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function profile(User $user) {
        if(Auth::id() != $user->id) {
            return redirect()->back()->with('error', 'شما اجازه دسترسی به این صفحه را ندارید.');
        }

        return view('admin.users.profile', compact('user'));
    }

    // Create
    public function create() {
        $roles = Cache::remember('roles', now()->addHour(), function() {
            return Role::select('id', 'persian_name')->get();
        });

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserUserStoreRequest $request) {
        $token = VerifyPhone::where("user_phone", $request['phone'])->first();

        if (!$token || $token->used) {
            return redirect()->back()->with('error', 'کد احراز هویت تایید نشده است.')->withInput();
        }

        $user = User::create($request);

        if ($request['role']){
            $user->assignRole($request['role']);
        }
        else {
            return redirect()->back()->with('error', 'نقشی انتخاب نشده است.');
        }

        $token->delete();

        return redirect()->route('users.index')->with("success", "کاربر جدید با موفقیت ایجاد شد.");
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

        return redirect()->route('users.index')->with("success", "کاربر با موفقیت ویرایش شد.");
    }

    public function updatePhone(Request $request, User $user) {
        $data = $request->validate([
            'phone' => ['required', 'max:11', 'unique:users,phone,' . $user->id],
        ]);

        $token = VerifyPhone::where("user_phone", $data['phone'])->first();

        if ($token->is_used) {
            $user->update($data);
            return redirect()->back()->with('success', 'شماره تلفن با موفقیت تغییر کرد.');
        }

        return redirect()->back()->with('error', 'کد احراز هویت تایید نشده است.');
    }

    // Delete
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}