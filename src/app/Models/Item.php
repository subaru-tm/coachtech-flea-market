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

    public function scopeKeywordSearch($query, $keyword) {
        if (!empty($keyword)) {
            $query->where('name', 'like', '%'.$keyword.'%');
        }
    }

    public function scopeExcludeMySelling($query, $user_id) {
        if (!empty($user_id)) {
            $query->where('user_id', '<>',  $user_id);
        }
    }

    public function scopeMylistSearch($query, $user_id) {
        if (!empty($user_id)) {
            //
        }
    }
}
