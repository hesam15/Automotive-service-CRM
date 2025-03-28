<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\PermissionsRole;
use App\Models\Province;
use App\Models\Role;
use App\Models\ServiceCenter;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Pest\Laravel\call;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceCenterSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            PermisionsRoleSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class
        ]);
    }
}
