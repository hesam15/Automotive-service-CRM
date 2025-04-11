<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            ['name' => 'create_serviceCenters'],['name' => 'edit_serviceCenters'],['name' => 'delete_serviceCenters'],
            ['name' => 'create_users'], ['name' => 'edit_users'], ['name' => 'delete_users'], ['name' => 'view_users'], ['name' => 'create_api_key'],
            ['name' => 'view_customers'],['name' => 'create_customers'], ['name' => 'edit_customers'], ['name' => 'delete_customers'],
            ['name' => 'create_options'], ['name' => 'edit_options'], ['name' => 'delete_options'],
            ['name' => 'create_reports'], ['name' => 'edit_reports'], ['name' => 'delete_reports'],
            ['name' => 'view_bookings'],['name' => 'create_bookings'], ['name' => 'edit_bookings'], ['name' => 'delete_bookings'],
        ]);
    }
}
