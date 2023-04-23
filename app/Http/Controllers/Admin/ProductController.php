<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
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
        $categories = Category::all();
        $products = Product::query()->orderByDesc('id')->paginate();

        return view('admin.pages.products.index', compact([
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
        $product = Product::create($request->except('images'));
        $this->storeImages($product, $request->images);
        return back()->withSuccess('Успешно');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->except('images'));
        $this->storeImages($product, $request->images);
        return back()->withSuccess('Успешно');
    }

    private function storeImages(Product $product, $images)
    {
        foreach ($images as $image) {
            Image::create([
                'product_id' => $product->id,
                'path' => $this->uploadFile($image, 'product')
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
}
