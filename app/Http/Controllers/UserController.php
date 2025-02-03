<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Read
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function profile($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.users.profile', compact('user'));
    }

    // Create
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $token = Token::where("user_phone", $request->phone)->first();

        if (!$token || $token->used) {
            return redirect()->back()->with('error', 'کد احراز هویت تایید نشده است.')->withInput();
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|unique:users',
            'role' => 'required'
        ]);

        $user = User::create($request->only('name', 'email', 'password', 'phone'));
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with("success", "کاربر جدید با موفقیت ایجاد شد.");
    }

    // Update
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'role' => 'required'
        ]);

        $user->refreshRoles($request->role);
        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with("success", "کاربر با موفقیت ویرایش شد.");
    }

    public function updatePhone(Request $request, User $user)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone,' . $user->id,
        ]);

        $token = Token::where("user_phone", $request->phone)->first();

        if ($token && $token->used) {
            $user->update($request->only('phone'));
            $token->delete();

            return redirect()->route('users.index')->with("success", "شماره تلفن با موفقیت ویرایش شد.");
        }

        return redirect()->back()->with('error', 'کد احراز هویت تایید نشده است.')->withInput();
    }

    // Delete
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}