<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permissions;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Read
    public function index()
    {
        $roles = Role::all();
        $permissions = Permissions::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    // Create
    public function storePage()
    {
        $permissions = Permissions::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $role = Role::create($request->only(['name', 'persian_name']));

        if ($request->permissions) {
            $role->givePermissionsToRole($role, $request->permissions);
        }

        return redirect()->route('roles.index')->with('success', "نقش جدید با موفقیت ثبت شد");
    }

    // Update
    public function edit(Role $role)
    {
        $permissions = Permissions::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->only(['name', 'persian_name']));
        $role->refreshPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', "نقش با موفقیت ویرایش شد");
    }

    // Delete
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }

    // Validation
    private function validateForm(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'persian_name' => 'required|unique:roles,persian_name'
        ]);
    }
}