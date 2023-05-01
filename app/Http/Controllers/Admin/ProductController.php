<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductGift;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $brands = Brand::all();
        $products = Product::query()->orderByDesc('id')->paginate();
        $categories = Category::query()->selectRaw('id, CONCAT(name, "[", type, "]") as name')->get();

        return view('admin.pages.products', compact([
            'users',
            'brands',
            'categories',
            'products',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->except(['images','gifts']));
        $this->storeImages($product, $request->images ?? []);
        $this->storeGifts($product, $request->gifts ?? []);
        return back()->withSuccess('Успешно');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->except(['images','gifts']));
        $this->storeImages($product, $request->images ?? []);
        $this->storeGifts($product, $request->gifts ?? []);
        return back()->withSuccess('Успешно');
    }

    private function storeImages(Product $product, $images)
    {
        foreach ($images as $image) {
            Image::create([
                'product_id' => $product->id,
                'path' => $this->uploadFile($image, 'products/images')
            ]);
        }
    }
    private function storeGifts(Product $product, $gifs)
    {
        foreach ($gifs as $gif) {
            ProductGift::create([
                'main_product_id' => $product->id,
                'gift_product_id' => $gif
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->withSuccess('Успешно');
    }

    public function destroyImage(Image $image)
    {
        $image->delete();
        return back()->withSuccess('Успешно');
    }
    public function destroyGift(ProductGift $gift)
    {
        $gift->delete();
        return back()->withSuccess('Успешно');
    }
}
