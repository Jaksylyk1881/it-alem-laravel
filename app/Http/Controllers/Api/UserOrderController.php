<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Order\UserOrderStoreRequest;
use App\Models\Address;
use App\Models\Basket;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = auth()->user()->orders()
            ->with([
                'address',
                'products.product.images',
                'products.gift_products.images',
            ])
            ->get()
            ->map(function ($order) {
                $order->type = $order->products->first()->product->category->type;
                $order->status_str = Order::STATUS[$order->status];
                foreach ($order->products as $product) {
                    $product->sum = $product->product->count * $product->product->price;
                }
                return $order;
            });
        return $this->Result(200, $orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserOrderStoreRequest $request)
    {
        //some validation
        if($request->user()->baskets()->get()->isEmpty()) {
            return $this->Result(400, 'Basket is empty');
        }
        //get basket items
        $baskets_query = Basket::where('user_id', $request->user()->id);
        $baskets = $baskets_query->with(['product.category'])->get();
        foreach ($baskets->where('product.category.type', 'service') as $basket) {

            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => Address::find($request->address_id)->id ?? null,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
                'description' => $request->description,
            ]);

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $basket->product_id,
                'gift_product_id' => $basket->gift_product_id,
                'count' => $basket->count,
            ]);

            Basket::query()
                ->where('id', $basket->id)
                ->delete();
        }
        if($baskets->where('product.category.type', 'product')->isNotEmpty()) {

            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => Address::find($request->address_id)->id ?? null,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
                'description' => $request->description,
            ]);
            foreach ($baskets->where('product.category.type', 'product') as $basket) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $basket->product_id,
                    'gift_product_id' => $basket->gift_product_id,
                    'count' => $basket->count,
                ]);
            }
            Basket::query()
                ->whereIn('id', $baskets->where('product.category.type', 'product')->pluck('id'))
                ->delete();
        }

        return $this->Result(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $this->Result(
            200,
            $order->load([
                'address',
                'products.product.images',
                'products.gift_products.images',
            ])
        );
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return $this->Result(200);
    }

}
