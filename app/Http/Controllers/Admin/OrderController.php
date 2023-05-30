<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::query()->orderByDesc('id')->paginate();
        return view('admin.pages.orders', compact([
            'orders',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->withSuccess('Успешно');
    }


    /**
     * Display a listing of the resource.
     */
    public function products(Order $order)
    {
        $order_products = $order->products()->paginate();
        return view('admin.pages.order_products', compact([
            'order_products',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct(OrderProduct $order_product)
    {
        $order_product->delete();
        return back()->withSuccess('Успешно');
    }



}
