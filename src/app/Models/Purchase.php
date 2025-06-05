<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'shipping_post_code',
        'shipping_address',
        'shipping_building',
    ];

    public function scopePurchaseSearch($query, $user_id, $item_id) {

        $query->where('user_id', $user_id)->where('item_id', $item_id);

        return $query;

    }

    public function scopeCommitedPurchase($query) {

        $query->where('payment_method', '<>' , 'null');

        return $query;
    }

    public function scopeUserIdSearch($query, $user_id) {
        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }
    }
}
