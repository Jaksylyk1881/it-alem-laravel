<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'is_favorite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function gifts()
    {
        return $this->hasMany(ProductGift::class, 'main_product_id');
    }

    public function getIsFavoriteAttribute()
    {
        if(!auth()->hasUser()) {
            return false;
        }
        return auth()->user()->favorites()->where('product_id', $this->id)->exists();
    }

    protected $casts = [
        'category_id' => 'integer',
        'price' => 'integer',
        'count' => 'integer',
        'brand_id' => 'integer',
    ];
}
