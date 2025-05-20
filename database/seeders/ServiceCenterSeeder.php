<?php

namespace Database\Seeders;

use App\Models\ServiceCenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceCenter::create([
            'name' => "Karshenas Kian",
            'phone' => "09123456789",
            'user_id' => '1',
            'city_id' => '444',
            'address' => 'فکوری 5، شهرستانی 7',
            'fridays_off' => true,
            'working_hours' => "8:30-21:30"
        ]);

        ServiceCenter::create([
            'name' => "کارشناسی حسام",
            'phone' => "09059202884",
            'user_id' => '2',
            'city_id' => '444',
            'address' => 'فکوری 5، شهرستانی 7',
            'fridays_off' => true,
            'working_hours' => "8:30-21:30"
        ]);
    }
}
