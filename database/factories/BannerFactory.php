<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => config('app.url') . '/' . str_replace('public/', '', fake()->image('public/uploads/banners')),
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory()->create()->value('id'),
        ];
    }
}
