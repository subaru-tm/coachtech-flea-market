<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\User;
use App\Models\ChatMessage;

class Dealing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'status',
        'customer_rating',
        'seller_rating',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chat_messages()
    {
        return $this->hasMany(ChatMessage::class);
    }


}
