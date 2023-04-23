<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gift_product()
    {
        return $this->belongsTo(Product::class, 'gift_product_id');
    }

    protected $casts = [
        'gift_product_id' => 'integer',
    ];
}
