<?php

namespace Database\Seeders;

use App\Models\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permissions::insert([
            ['name' => 'create_customer','persian_name' => 'ایجاد مشتری'], ['name' => 'edit_customer','persian_name' => 'ویرایش مشتری'],['name' => 'delete_customer', 'persian_name' => 'حذف مشتری'],
            ['name' => 'create_user','persian_name' => 'ایجاد کاربر'],['name' => 'edit_user','persian_name' => 'ویرایش کاربر'],['name' => 'delete_user','persian_name' => 'حذف کاربر'],
            ['name' => 'create_option','persian_name' => 'ایجاد خدمت'],['name' => 'edit_option','persian_name' => 'ویرایش خدمت'],['name' => 'delete_option','persian_name' => 'حذف خدمت'],
            ['name' => 'create_role','persian_name' => 'ایجاد نقش'],['name' => 'edit_role','persian_name' => 'ویرایش نقش'],['name' => 'delete_role','persian_name' => 'حذف نقش'],
            ['name' => 'create_booking','persian_name' => 'ایجاد رزرو'],['name' => 'edit_booking','persian_name' => 'ویرایش رزرو'],['name' => 'delete_booking','persian_name' => 'حذف رزرو'],
        ]);
    }
}
