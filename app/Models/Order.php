<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

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
}
