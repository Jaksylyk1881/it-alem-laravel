<?php

use App\Models\Banner;
use Database\Seeders\PersonalAccessTokenSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Banner::factory(3)->create();
        $this->call(UserSeeder::class);
        $this->call(PersonalAccessTokenSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
