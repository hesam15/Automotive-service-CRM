<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Pest\Laravel\call;
use Database\Seeders\RoleHasPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ServiceCenterSeeder::class,
            PermissionSeeder::class,
            RoleHasPermissionsSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class
        ]);
    }
}
