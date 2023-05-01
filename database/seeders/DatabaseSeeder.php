<?php

use App\Models\Banner;
use App\Models\Product;
use Database\Seeders\PersonalAccessTokenSeeder;
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
        $this->call([
            PersonalAccessTokenSeeder::class,
            UserSeeder::class,
        ]);
        Product::factory(5)->create();
        Banner::factory(3)->create();
    }
}
