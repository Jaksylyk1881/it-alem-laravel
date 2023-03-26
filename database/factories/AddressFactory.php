<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->freeEmail(),
            'postcode' => fake()->postcode(),
            'city_id' => City::inRandomOrder()->value('id') ?? City::factory()->create()->value('id'),
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->value('id'),
            'street' => fake()->streetAddress(),
            'home' => fake()->numerify('#, #, #'),
        ];
    }
}
