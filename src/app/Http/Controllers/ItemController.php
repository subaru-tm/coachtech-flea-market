<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Mylist;
use App\Models\User;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    // 商品一覧画面で使用するアクション

    public function index() {
        if (Auth::check()) {
            $user_id = Auth::id();
            $items = Item::ExcludeMySelling($user_id)->get();
        } else {
            $items = Item::all();
        }

        return view('index', compact('items'));
    }

    public function search(Request $request) {
        $keyword = $request->input('keyword');
        $items = Item::KeywordSearch($keyword)->get();
        return view('index', compact('items', 'keyword'));
    }

    public function mylist() {
        $user_id = Auth::id();
        $items = Item::MylistSearch($user_id)->get();

        return view('index', compact('items'));
    }


    // 以降、商品詳細画面で使用するアクション

    public function detail($item_id) {
        $item = Item::with('categories')->find($item_id);
        $categories = Category::all();
        $nice_count = Mylist::getCountNiceByItemId($item_id);

        //他ユーザーのコメントを取得し、コメントがある場合は該当ユーザー情報も取得
        $comment_count = Mylist::getCommentCountByItemId($item_id);
        $other_comment = Mylist::getCommentByItemId($item_id);
        $comment_user = '';  //コメントが取得できなかった場合（ ゼロ or null )に備えて変数初期化。
        if (isset($other_comment)) {
            $comment_user_id = $other_comment->user_id;
            $comment_user = User::find($comment_user_id);
        }
        //ログイン済であれば該当ユーザーのmylist_item（該当商品のいいね、コメント）を取得し、viewに渡す。
        if (Auth::check()) {
            $user_id = Auth::id();
            $mylist_item = Mylist::getMylistItem($user_id, $item_id);

            return view('item-detail', compact('item', 'categories', 'nice_count', 'mylist_item', 'comment_count', 'other_comment', 'comment_user'));

        } else {
            //ログインしていなくても商品詳細画面を参照できるように、mylist_item以外の情報をviewに渡す。
            return view('item-detail', compact('item', 'categories', 'nice_count', 'comment_count', 'other_comment', 'comment_user'));

        }
    }

    public function nice($item_id) {
        $user_id = Auth::id();

        if (Mylist::getExistCheck($user_id, $item_id) == '0') {

            $mylist =  Mylist::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
                'nice_flug' => '1',
            ]);

        } elseif (Mylist::getExistCheck($user_id, $item_id) == '1') {

            $mylist_item = Mylist::getMylistItem($user_id, $item_id);

            if ($mylist_item->nice_flug == '1')
            {
                $mylist = Mylist::getMylistItem($user_id, $item_id)->update(['nice_flug' => '0']);

            } elseif ($mylist_item->nice_flug == '0') {

                $mylist = Mylist::getMylistItem($user_id, $item_id)->update(['nice_flug' => '1']);

            }

            return redirect(route('item.detail',['item_id' => $item_id ]));

        }
    }

    public function comment(CommentRequest $request, $item_id) {
        $user_id = Auth::id();
        $comment = $request->comment;

        if (Mylist::getExistCheck($user_id, $item_id) == '0') {
            
            $mylist = Mylist::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
                'comment' => $comment,
            ]);

        } elseif (Mylist::getExistCheck($user_id, $item_id) == '1') {

            $mylist = Mylist::getMylistItem($user_id, $item_id)->update(['comment' => $comment]);

        }

        return redirect(route('item.detail',['item_id' => $item_id ]))->with(compact('mylist'));
    }
}
