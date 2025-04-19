<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '09123456789',
            'service_center_id' => '1'
        ]);

        $user1->assignRole('adminstrator');

        $user2 = User::create([
            'name' => 'حسام الدین زراعتکار',
            'email' => 'hesam@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '09059202883',
            'service_center_id' => '2'
        ]);

        $user2->assignRole('adminstrator');
    }
}
