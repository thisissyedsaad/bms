<?php

namespace Database\Seeders;

use App\Models\CategoryPrice;
use App\Models\Country;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserHasRoleSeeder::class,
            ClientSeeder::class,
            CountrySeeder::class,
        ]);
    }
}
