<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Models\Permissions;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller {
    // Read
    public function index() {
        $roles = Role::with('permissions')->get();
        $permissions = Cache::remember('permissions', now()->addHour(), function() {
            return Permissions::select('id', 'persian_name');
        });
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    // Create
    public function create() {
        $permissions = Cache::remember('permissions', now()->addHour(), function() {
            return Permissions::select('id', 'persian_name');
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleStoreRequest $request) {
        $validated = $request->validated();

        $role = Role::create([
            "name" => $validated['name'],
            "persian_name" => $validated['persian_name'],
        ]);

        if ($validated['permissions']) {
            $role->givePermissionsToRole($role, $validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', "نقش جدید با موفقیت ثبت شد");
    }

    // Update
    public function edit(Role $role) {
        $permissions = Cache::remember('permissions', now()->addHour(), function() {
            return Permissions::select('id', 'persian_name');
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleUpdateRequest $request, Role $role) {
        $validated = $request->validated();
        $role->update([
            "name" => $validated['name'],
            "persian_name" => $validated['persian_name'],
        ]);
        if ($validated['permissions']) {
            $role->refreshPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', "نقش با موفقیت ویرایش شد");
    }

    // Delete
    public function destroy(Role $role) {
        $role->delete();
        return redirect()->route('roles.index');
    }
}