<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $guarded = [];

    const TYPES = ['client', 'company'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'lat' => 'double',
        'lng' => 'double',
    ];

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function shop_address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'company_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shop_orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getChatsWithUsersAttribute()
    {
        return Chat::query()
            ->join('chat_users', function ($q) {
                $q->where(function ($qq) {
                    $qq->orWhere('chat_users.user_id', '=', DB::raw($this->id));
                    $qq->orWhere('chat_users.owner_id', '=', DB::raw($this->id));
                });
                $q->on('chat_users.chat_id', 'chats.id');
            });
    }
}
