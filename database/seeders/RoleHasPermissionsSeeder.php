<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\RoleHasPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminstratorPermissions = collect([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27, 28])->map(function($permissionId) {
            return [
                'permission_id' => $permissionId,
                'role_id' => 1
            ];
        });

        $expertPermissions = collect([7,8,9,10,11])->map(function($permissionId) {
            return [
                'permission_id' => $permissionId,
                'role_id' => 2
            ];
        });

        $clerkPermissions = collect([1,2,3,4,5,6,7,8,9,13,14,15])->map(function($permissionId) {
            return [
                'permission_id' => $permissionId,
                'role_id' => 3
            ];
        });

        $roles = [$adminstratorPermissions, $expertPermissions, $clerkPermissions];

        foreach($roles as $role) {
            RoleHasPermissions::insert($role->toArray());
        }
    }
}
