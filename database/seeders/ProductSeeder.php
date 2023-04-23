<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image_path = config('app.url') . '/placeholders/images/avatar.jpg';
        $products = Product::factory(3)->create();

        foreach ($products as $product) {
            $product->images()->create([
                'path' => $image_path
            ]);
            for($i = 0; $i < 2; $i++) {
                $gift_product = Product::factory()->createOne();

                $gift_product->images()->create([
                    'path' => $image_path
                ]);
                $product->gifts()->create([
                    'gift_product_id' => $gift_product->id
                ]);
            }
        }
    }
}
