<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chat_users() : hasOne
    {
        return $this->hasOne(ChatUsers::class);
    }

    public function messages() : hasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function getLastMessageAttribute()
    {
        if(!array_key_exists('messages', $this->toArray())) {
            $this->load(['messages' => fn($q) => $q->take(1)]);
        }
        return $this->messages()->latest()->first();
    }
}
