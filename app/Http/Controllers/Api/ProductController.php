<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ProductStoreRequest;
use App\Models\Brand;
use App\Models\Image;
use App\Models\Product;
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
        $product = Product::with('images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate');
        //sort
        switch ($request->get('sort', 'id')) {
            case 'new':
                $product->orderBy('created_at', 'desc');
                break;
            case 'cheap':
                $product->orderBy('price');
                break;
            case 'expensive':
                $product->orderBy('price', 'desc');
                break;
            case 'rating':
                $product->orderBy('reviews_avg_rate', 'DESC');
                break;
            case 'popular':
                $product->orderBy('reviews_count', 'DESC');
                break;
        }
        $product->when($request->price_start, function ($query) use ($request){
                $query->where('price', '>=', $request->price_start);
            })
            ->when($request->price_end, function ($query) use ($request){
                $query->where('price', '<=', $request->price_end);
            })
            ->when($request->brand_id, function ($query) use ($request){
                $query->where('brand_id', $request->brand_id);
            });
        $product = $product->paginate(10);
        return $this->Result(200, [
            'items' => $product->items(),
            'pagination' => [
                'total' => $product->total(),
                'current_page' => $product->currentPage(),
                'has_more_pages' => $product->hasMorePages(),
                'last_page' => $product->lastPage(),
                'per_page' => $product->perPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request) //request, images required
    {
        $product = Product::create(array_merge($request->except('images'), [
            'user_id' => $request->user()->id,
        ]));
        foreach ($request->images as $image) {
            $product->images()->create([
                'path' => $this->uploadFile($image, 'products\images'),
            ]);
        }
        return $this->Result(200, $product->load('images'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = Product::with('images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->where('products.id', $product->id)->first();
        $similar = Product::with('images')->orderByRaw("ABS(price - $product->price) ")
        ->take(5)
        ->get();
        return $this->Result(200, [
            'product' => $product,
            'similar' => $similar,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->except('images'));
        if ($request->images) {
            foreach ($request->images as $image) {
                $product->images()->create([
                    'path' => $this->uploadFile($image, 'products\images'),
                ]);
            }
        }
        return $this->Result(200, $product->load('images'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->Result(200);
    }

    public function deleteImage(Image $image)
    {
        $image->delete();
        return $this->Result(200);
    }
}
