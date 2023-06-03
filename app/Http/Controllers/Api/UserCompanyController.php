<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class UserCompanyController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->select('orders.*')
            ->join('order_products', 'order_products.order_id', 'orders.id')
            ->join('products', 'products.id', 'order_products.product_id')
            ->where('products.user_id', auth()->id())
            ->get();
        return $this->Result(200, $orders);
    }

    public function show(Request $request, Order $order)
    {
        $order_products = $order->load(['products.product.images', 'address', 'user'])->append(['products_price']) ;
        $order_products->status_message = match ($order_products->status) {
            10 => 'Товар у продавца',
            15 => 'В прцессе',
            20 => 'Принят',
            0 => 'Отклонено'
        };

        return $this->Result(200, $order_products);
    }

    public function update(Request $request,Order $order)
    {
        switch ($request->get('action')) {
            case 'accept':
                $order->update(['status' => 15]);
                break;
            case 'decline':
                $order->update(['status' => 0]);
                break;
            case 'complete':
                $order->update(['status' => 20]);
                break;
        }

        return $this->Result(200, $order);
    }

    public function product(Request $request)
    {
        $products = Product::query()
            ->with(['images', 'category', 'gifts'])
            ->where('id', $request->company_id)
            ->first();
        return $this->Result(200, $products);
    }

}
