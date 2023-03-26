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
        return $this->Result(
            200,
            Order::with('address')->where('user_id', $request->user()->id)
                ->get()
        );
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

        $order = Order::create([
            'user_id' => $request->user()->id,
            'address_id' => Address::find($request->address_id)->id,
            'delivery_type' => $request->delivery_type,
            'payment_type' => $request->payment_type,
            'description' => $request->description,
        ]);

        $baskets = Basket::where('user_id', $request->user()->id);
        foreach ($baskets->get() as $basket) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $basket->product_id,
            ]);

        }

        $baskets->delete();

        return $this->Result(200, $order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $this->Result(200, $order->load('products')); //add products->images
    }

}
