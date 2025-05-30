<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'nice_flug',
        'comment',
    ];

    public function getCountNiceByItemId($item_id) {
        if (!empty($item_id)) {
            $count = Mylist::where('item_id', $item_id)->where('nice_flug', '1')->count();

            return $count;
        }
    }

    public function getExistCheck($user_id, $item_id) {
        if (Mylist::where('user_id', $user_id)->exists() && Mylist::where('item_id', $item_id)->exists())
        {
            $mylist_exist = '1';
        } else
        {
            $mylist_exist = '0';
        }

        return $mylist_exist;

    }

    public function getCommentCountByItemId($item_id) {
        if (!empty($item_id)) {
            $count = Mylist::where('item_id', $item_id)->where('comment', '<>',  'null')->count();

            return $count;
        }
    }

    public function getCommentByItemId($item_id) {
        if (!empty($item_id)) {
            $comment = Mylist::where('item_id', $item_id)->where('comment', '<>',  'null')->first();

            return $comment;
        }
    }


    public function getMylistItem($user_id, $item_id) {

        $mylist_item = Mylist::where('user_id', $user_id)->where('item_id', $item_id)->first();

        return $mylist_item;
    }
}