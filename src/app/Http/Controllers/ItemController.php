<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\Mylist;
use App\Models\User;
use App\Models\Purchase;
use App\Models\ItemCategory;
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

        $purchases = Purchase::CommitedPurchase()->get();

        return view('index', compact('items', 'purchases'));
    }

    public function mylist() {
        $user_id = Auth::id();
        $mylist_items_id = Mylist::MylistItemSearch($user_id)->get();
        $items = Item::whereIn('id', $mylist_items_id)->get();

        $purchases = Purchase::CommitedPurchase()->get();

        return view('index', compact('items', 'purchases'));
    }

    public function search(Request $request) {
        $keyword = $request->input('keyword');
        $items = Item::KeywordSearch($keyword)->get();
        return view('index', compact('items', 'keyword'));
    }

    // 商品詳細画面で使用するアクション（view表示、いいねの追加／取り消し、コメント参照・送信）

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
        }

        return redirect(route('item.detail',['item_id' => $item_id ]));

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

    //商品出品のアクション（view表示とitemsテーブルへのinsert）
    public function create() {

        $categories = Category::all();

        return view('exhibition', compact('categories'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $price = str_replace('¥', '', $request->price);

        $file = $request->file('img-file');
        $originalName = $file->getClientOriginalName();
        $file->storeAs('public/', $originalName);

        $image_path = 'storage/' . $originalName;


        $item = Item::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'brand' => $request->brand,
            'condition' => $request->condition,
            'description' => $request->description,
            'price' => $price,
            'image' => $image_path,
        ]);

        $new_item_id = $item->id;

        $categories = $request->category;

        foreach( $categories as $category ) {
            $item_category = ItemCategory::create([
                'item_id' => $new_item_id,
                'category_id' => $category,
            ]);
        }
        // 出品結果が直ぐに確認できるように、マイページへリダイレクト。
        // （補足：商品一覧へのリダイレクトも実装したが、ログイン中だと自身の出品が見れないため）
        return redirect('mypagesell');
    }
}
