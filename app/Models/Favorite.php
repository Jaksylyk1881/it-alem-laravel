<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];

    protected $casts = [
        'product_id' => 'integer',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
