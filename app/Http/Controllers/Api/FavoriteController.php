<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Favorite\FavoriteStoreRequest;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products_id = auth()->user()->favorites()->pluck('product_id');
        $products = Product::query()
            ->whereIn('id', $products_id)
            ->with([
                'images',
                'category',
            ])
            ->withCount('reviews')
            ->withCount('gifts')
            ->withAvg('reviews', 'rate')
            ->get()
            ->map(function ($product) {
                $product['has_gifts'] = $product['gifts_count'] > 0;
                return $product;
            });
        return $this->Result(200, [
            'products' => $products->where('category.type', 'product')->values(),
            'services' => $products->where('category.type', 'service')->values(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FavoriteStoreRequest $request)
    {
        $favorite = Favorite::create([
            'user_id' => auth()->id(),
            'product_id' => $request->get('product_id')
        ]);
        return $this->Result(200, $favorite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        auth()->user()->favorites()->where('product_id', $product_id)->delete();
        return $this->Result(200);
    }
}
