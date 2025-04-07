<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'adminstrator',
                'persian_name' => 'مدیر کل'
            ],
            [
                'name' => 'expert',
                'persian_name' => 'کارشناس'
            ],
            [
                'name' => 'clerk',
                'persian_name' => 'منشی'
            ],
            [
                'name' => 'customer',
                'persian_name' => 'مشتری'
            ]
        ]);
    }
}
