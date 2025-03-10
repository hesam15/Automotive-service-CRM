<?php

namespace Database\Seeders;

use App\Models\PermissionsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisionsRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15])->map(function($permissionId) {
            return [
                'permissions_id' => $permissionId,
                'role_id' => 1
            ];
        });
        
        PermissionsRole::insert($permissions->toArray());        
    }
}
