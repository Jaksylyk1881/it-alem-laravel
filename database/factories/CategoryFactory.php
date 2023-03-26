<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'image' => config('app.url') . '/' . str_replace('public/', '', fake()->image('public/uploads/categories')),
            'type' => fake()->randomElement(Category::TYPES),
            'parent_id' => Category::inRandomOrder()->value('id') ?? null,
        ];
    }
}
