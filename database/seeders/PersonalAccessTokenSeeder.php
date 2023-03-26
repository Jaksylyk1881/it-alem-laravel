<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalAccessTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => User::factory()->create()->value('id'),
            'name' => 'auth',
            'token' => hash('sha256', '8PAnyHJ3eyUJl0rH6UMY4PxP6ZoILKL6UBnHGdaQ'),
            'abilities' => '["auth"]',
            'expires_at' => '2032-07-20 10:22:58',
        ]);
//        DB::table('personal_access_tokens')->insert([
//            'tokenable_type' => 'App\Models\User',
//            'tokenable_id' => 777,
//            'name' => 'auth',
//            'token' => hash('sha256', '8PAnyHJ3eyUJl0rH6UMY4PxP6ZoILKL6UBnHGdaQ'),
//            'abilities' => '["auth"]',
//            'expires_at' => '2032-07-20 10:22:58',
//        ]);
    }
}
