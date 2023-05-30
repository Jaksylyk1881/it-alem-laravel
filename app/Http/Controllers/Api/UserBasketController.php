<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class UserBasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $basket_products = $request->user()
            ->baskets()
            ->with([
                'product',
                'product.images',
                'product.user:name,id',
                'gift_product.images',
            ])
            ->get();
        return $this->Result(200, [
            'total_price' => $basket_products->map(fn ($q) => $q->count * $q->product->price)->sum(),
            'total_count' => $basket_products->sum('count'),
            'products' => $basket_products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::find($request->product_id);
        $basket = Basket::where('user_id')->first();
        if($basket != null && $basket->first()->product()->user()->id != $product->user()->id) {
            return $this->Result(
                400,
                null,
                'Ошибка. Выбрано объявление из разных поставщиков'
            );
        }
        $basket = Basket::create([
            'product_id' => $product->id,
            'gift_product_id' => $request->gift_product_id,
            'user_id' => $request->user()->id,
        ]);
        return $this->Result(200, $basket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Basket $basket)
    {
        if ($request->action) {
            switch ($request->action) {
                case 'increment':
                    if(Product::where('id', $basket->product_id)->value('count') > $basket->count) {
                        $basket->update(['count' => $basket->count + 1]);
                        return $this->Result(200);
                    }
                    return $this->Result(400, null, 'Превышено максимальное количество');
                case 'decrement':
                    if($basket->count > 1) {
                        $basket->update(['count' => $basket->count - 1]);
                        return $this->Result(200);
                    }
                    return $this->Result(400, null, 'Превышено минимальное количество');
            }
        }
        return $this->Result(400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Basket $basket)
    {
        $basket->delete();
        return $this->Result(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function clear(Request $request)
    {
        Basket::where('user_id', $request->user()->id)->delete();
        return $this->Result(200);
    }
}
