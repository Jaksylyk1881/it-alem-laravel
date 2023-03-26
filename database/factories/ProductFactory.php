<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->value('id'),
            'brand_id' => Brand::inRandomOrder()->value('id') ?? Brand::factory()->create()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory()->create()->value('id'),
            'name' => fake()->word(),
            'price' => fake()->numerify('#####'),
            'characteristics' => fake()->realText(),
            'description' => fake()->realText(),
            'count' => fake()->numerify('#'),
        ];
    }
}
