<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;
use App\Models\Dealing;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function create()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        return view('profile-edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user_id = Auth::id();
        $file = $request->file('img_file');

        if(!isset($file)) {

              // 必須ではないため画像がアップロードされていない場合、nullがありえる。
              // この場合、画像保存等の処理を行うとエラーとなるため、名前、住所の更新のみとする。

            $user_profile = $request->only(['name', 'post_code', 'address', 'building']);

            User::find($user_id)->update([
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]);

        } else {
              // 画像がアップロードされた場合の処理
            $originalName = $file->getClientOriginalName();
            $file->storeAs('public/', $originalName);
    
            $image_path = 'storage/' . $originalName;
    
            $user_profile = $request->only(['name', 'post_code', 'address', 'building', 'image']);
            User::find($user_id)->update([
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
                'image' => $image_path,
            ]);

        }

        return redirect('/');
    }

    public function mypage($tab)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        // 評価平均確認のため、完了した取引の評価値を取得し平均する
        $target_status = "completed";

            // 購入者(customer)としての取引を抽出
        $customer_dealings = Dealing::with('item', 'chat_messages')->where('user_id', $user_id)->where('status', $target_status)->get();

        $ratings_by_seller = $customer_dealings->pluck('seller_rating');
            // 評価平均のためにratingを取得。自身が購入者の場合、seller_rating(出品者の入力)が対象。

            // 出品者(seller)としての取引を抽出
        $seller_dealings = Dealing::with('item', 'chat_messages')->where('status', $target_status)->whereHas('item', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
        })->get();

        $ratings_by_customer = $seller_dealings->pluck('customer_rating');
            // 評価平均のためにratingを取得。自身が出品者の場合、customer_rating(購入者の入力)が対象。

        $all_ratings = $ratings_by_seller->concat($ratings_by_customer);

        $i = 0;             // 平均計算のための個数を扱う変数を初期化。
        $rating_sum = 0;    // 平均計算のための合計を扱う変数を初期化。

        foreach($all_ratings as $rating)
        {
            $rating_sum = $rating_sum + $rating;
            $i++; 
        }
            // 合算したratingを平均する。小数点以下は四捨五入。
        $rating_average = round($rating_sum / $i);

        // タブ名への新着件数表示のため、取引中情報を収集。取引完了は対象外。
        $target_status = "dealing";

            // 購入者(customer)としての取引を抽出
        $customer_dealings = Dealing::with('item', 'chat_messages')->where('user_id', $user_id)->where('status', $target_status)->get();

            // 出品者(seller)としての取引を抽出
        $seller_dealings = Dealing::with('item', 'chat_messages')->where('status', $target_status)->whereHas('item', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
        })->get();

            // 購入者・出品者それぞれの取引を結合
        $dealings = $customer_dealings->concat($seller_dealings);
        $dealings_id = $dealings->pluck('id');

        // タブごとの変数を取得
        if ($tab == "sell")
        {
            $items = Item::UserIdSearch($user_id)->get();
            $purchases = Purchase::all();
            $tab_return = "sell";

        } elseif ($tab == "buy")
        {
            $items = Item::all();
            $purchases = Purchase::UserIdSearch($user_id)->get();
            $tab_return = "buy";

        } elseif ($tab == "dealing")
        {

            // if文の前に絞り込んだ取引に対して、メッセージを抽出し、作成日(降順)でソート
            $chat_messages = ChatMessage::with('dealing')->whereIN('dealing_id', $dealings_id)->where('user_id', '<>', $user_id)->get();
            $sorted_chat_messages = $chat_messages->sortBy([
                ['read_at', true],      // 新着(null)が優先されるように昇順とする（新着がなくても後ろに表示する）
                ['created_at', false],
            ]);

            // ソート済のメッセージからリレーションよりitem_idを取得。順番も保持。
            $sorted_items_id = $sorted_chat_messages->pluck('dealing')->unique('item_id')->pluck('item_id');
            $placeholder = '';

            // 順番を保持したまま商品を抽出する
            foreach ( $sorted_items_id as $item_id => $value ) {
                $placeholder .= ($item_id == 0) ? '?' : ',?';
            }

            $items = Item::with('dealings')->whereIn('id', $sorted_items_id)->orderByRaw("FIELD(id, $placeholder)", $sorted_items_id)->get();

            $purchases = Purchase::all();
            $tab_return = "dealing";

        }

        // 新着メッセージ（自身の送信分を除く）件数を取得。「取引中の商品」タブでも件数表示のため、if文の外で取得。
        $new_messages = ChatMessage::with('dealing')->whereIN('dealing_id', $dealings_id)->where('read_at', null)->where('user_id', '<>', $user_id)->get();

        $new_messages_count_total = $new_messages->count();
        $new_messages_count_byitem = $new_messages->pluck('dealing')->countBy('item_id');

        return view('profile', compact(
                'user', 
                'items', 
                'purchases', 
                'tab_return', 
                'new_messages_count_total', 
                'new_messages_count_byitem',
                'rating_average',
        ));

    }

    public function profile()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        return view('profile-edit', compact('user'));
    }

    public function verify()
    {
        return view('auth.verify');
    }
}
