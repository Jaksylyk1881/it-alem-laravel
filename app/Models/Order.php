<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    const STATUS = [
        '0' => 'decline',
        '10' => 'in progress',
        '15' => 'accepted',
        '20' => 'complete',
    ];

    protected $guarded = [];

    protected $appends = ['type', 'status_str'];

    const DELIVERY_TYPE = ['pickup', 'delivery'];
    const PAYMENT_TYPE = ['cash', 'card'];

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProductsPriceAttribute()
    {
        $order_products_prices = $this->products()->with('product:id,price')->get()->map(function ($order_products) {
            $order_products->price =  $order_products->count * $order_products->product->price;
            return $order_products;
        })->sum('price');
        return $order_products_prices;
    }

    public function getTypeAttribute()
    {
        return $this->products()->first()->product->category->type;
    }
    public function getStatusStrAttribute()
    {
        return self::STATUS[$this->status];
    }
}
