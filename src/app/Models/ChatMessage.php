<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dealing;
use App\Models\User;


class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealing_id',
        'user_id',
        'message',
        'image',
        'read_at',
        'delete_flug',
    ];

    public function dealing() {
        return $this->belongsTo(Dealing::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


}
