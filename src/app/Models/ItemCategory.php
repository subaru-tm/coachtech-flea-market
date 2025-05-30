<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_category';

    protected $fillable = [
        'item_id',
        'category_id',
    ];

    public function scopeCategorySearch($query, $item_id) {
        if (!empty($item_id)) {
            $query->where('item_id', $item_id);
        }
    }
}
