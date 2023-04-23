<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ProductStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductGift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function searchFormValues()
    {
        $brands = Brand::get(['id', 'name']);
        $priceMinMax = Product::selectRaw('min(price) as min, max(price) as max')->first();
        return $this->Result(200,[
            'brands' => $brands,
            'price_start' => $priceMinMax->min,
            'price_end' => $priceMinMax->max,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('images')
            ->withCount('reviews')
            ->withCount('gifts')
            ->withAvg('reviews', 'rate');
        //sort
        switch ($request->get('sort', 'id')) {
            case 'new':
                $products->orderBy('created_at', 'desc');
                break;
            case 'cheap':
                $products->orderBy('price');
                break;
            case 'expensive':
                $products->orderBy('price', 'desc');
                break;
            case 'rating':
                $products->orderBy('reviews_avg_rate', 'DESC');
                break;
            case 'popular':
                $products->orderBy('reviews_count', 'DESC');
                break;
        }
        $products->when($request->price_start, function ($query) use ($request){
                $query->where('price', '>=', $request->price_start);
            })
            ->when($request->price_end, function ($query) use ($request){
                $query->where('price', '<=', $request->price_end);
            })
            ->when($request->brand_id, function ($query) use ($request){
                $query->where('brand_id', $request->brand_id);
            });
        $products = $products->paginate(10);
        $product_items = array_map(function(Product $product) {
            $product['has_gifts'] = $product['gifts_count'] > 0;
            unset($product['gifts_count']);
        }, $products->items());
        return $this->Result(200, [
            'items' => $products->items(),
            'pagination' => [
                'total' => $product_items,
                'current_page' => $products->currentPage(),
                'has_more_pages' => $products->hasMorePages(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create(array_merge($request->except(['images', 'gifts']), [
            'user_id' => $request->user()->id,
        ]));
        $this->storeImages($product, $request->images);
        $this->storeGifts($product, $request->gifts);
        return $this->Result(200, ['product_id' => $product->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = Product::with('images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->where('products.id', $product->id)
            ->first();
        $similar = Product::with('images')
            ->whereNot('products.id', $product->id)
            ->orderByRaw("ABS(price - $product->price) ")
            ->take(5)
            ->get();
        $gifts = ProductGift::query()
            ->where('main_product_id', $product->id)
            ->with('gift_product.images')
            ->get()
            ->pluck('gift_product');
        return $this->Result(200, compact([
            'product',
            'gifts',
            'similar',
        ]));
    }

    public function placeholders(Request $request)
    {
        $brands = Brand::all();
        $categories = Category::query()
            ->where('type', $request->type)
            ->get();
        $gifts = Product::query()
            ->with('images')
            ->where('user_id', auth()->id())
            ->get();

        return $this->Result(200, compact([
            'categories',
            'brands',
            'gifts',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->except(['images', 'gifts']));
        $this->storeImages($product, $request->images);
        $this->storeGifts($product, $request->gifts);
        return $this->Result(200);
    }

    public function storeImages(Product $product, $images)
    {
        foreach ($images as $image) {
            $product->images()->create([
                'path' => $this->uploadFile($image, 'products\images'),
            ]);
        }
    }
    public function storeGifts(Product $product, $gifts)
    {
        foreach ($gifts as $gift_product_id) {
            $product->gifts()->create([
                'gift_product_id' => $gift_product_id,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->Result(200);
    }

    public function deleteImage($product, Image $image)
    {
        $image->delete();
        return $this->Result(200);
    }
    public function deleteGift($product, $gift)
    {
        ProductGift::query()
            ->where('main_product_id', $product)
            ->where('gift_product_id', $gift)
            ->delete();

        return $this->Result(200);
    }
}
