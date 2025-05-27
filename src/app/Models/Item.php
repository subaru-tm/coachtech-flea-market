<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'condition',
        'description',
        'price',
        'image',
    ];

    public function user() {
        return $this->belongTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id',);
    }

}
