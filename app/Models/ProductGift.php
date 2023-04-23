<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'main_product_id');
    }
    public function gift_product()
    {
        return $this->belongsTo(Product::class, 'gift_product_id');
    }
}
