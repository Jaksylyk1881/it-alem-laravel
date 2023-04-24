<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //client
        User::factory()->create([
            'phone' => 'admin',
            'password' => bcrypt('admin'),
            'is_admin' => 1,
        ]);
        $user = User::factory()->create()->first();
        $user->addresses()->save(Address::factory()->createOne());

        //company
        $user = User::factory()->create([
            'type' => 'company',
            'address_id' => Address::factory()->create()->value('id'),
            'description' => fake()->realText(),
        ])->first();
        for($i = 0; $i < 3; $i++) {
            $user->images()->create([
                'path' => config('app.url') . '/' . str_replace('public/', '', fake()->image('public/uploads/users/companies')),
            ]);
        }
    }
}
