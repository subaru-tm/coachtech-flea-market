<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Dealing;
use App\Models\ChatMessage;
use App\Http\Requests\ChatMessageRequest;
use Carbon\Carbon;
use App\Mail\MailableMailtrap;
use Illuminate\Support\Facades\Mail;

class DealingController extends Controller
{
    public function chat($dealing_id)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $dealing = Dealing::with('item')->find($dealing_id);
        $item_id = $dealing->item->id;

     // まずはその他の取引に表示する商品を抽出
        $target_status = "dealing";

        // 購入者(customer)として取引中の商品を抽出
        $customer_item_id = Dealing::where('user_id', $user_id)->where('status', $target_status)->pluck("item_id")->toArray();
        $customer_dealing_items = Item::with('dealings')->whereIn('id', $customer_item_id)->get();

        // 出品者(seller)として取引中の商品を抽出
        $items_item_id = Dealing::where('status', $target_status)->pluck("item_id");
        $seller_dealing_items = Item::with('dealings')->whereIn('id', $items_item_id)->where('user_id', $user_id)->get();

        // 購入者・出品者それぞれの取引中商品を結合してviewに渡す変数に格納する
        $dealing_items = $customer_dealing_items->concat($seller_dealing_items);

        $dealing_items_id = $dealing_items->pluck('id');

        $other_items_id = $dealing_items_id->reject(function ($value) use ($item_id) {
            return $value === $item_id;
        });
                
        $other_items = Item::with('dealings')->whereIn('id', $other_items_id)->get();

     // 次に選択中の取引に関するデータ（user,chat_messages)を抽出
        // userを抽出。ログイン中ユーザーが出品者か購入者を判定し、相対をcounter_userとする

        if ( $user_id == $dealing->user_id )
        {
            // ログイン中ユーザーが購入者の場合。$counter_userに出品者を設定。
            $counter_user_id = $dealing->item->user_id;
            $counter_user = User::find($counter_user_id);
        } elseif ( $user_id == $dealing->item->user_id )
        {
            // ログイン中ユーザーが出品者の場合。$counter_userに購入者を設定。
            $counter_user_id = $dealing->user_id;
            $counter_user = User::find($counter_user_id);
        }

     // 最後にchat_messagesテーブルから対象の取引のメッセージ履歴（削除を除く）を取得。

        // メッセージを抽出するにあたって、未読のメッセージを既読に更新する
        $reading_counter_user_messages = ChatMessage::where('dealing_id', $dealing_id)->where('user_id', $counter_user_id)->where('read_at', null)->get();

        foreach($reading_counter_user_messages as $reading_message )
        {
            $chat_message = ChatMessage::find($reading_message->id);
            $chat_message->read_at = Carbon::now();
            $chat_message->save();
        }

        $target_delete_flug = "0";
        $chat_messages = ChatMessage::where('dealing_id', $dealing_id)->where('delete_flug', $target_delete_flug)->get();


        return view('dealing-chat', compact('other_items', 'user', 'counter_user', 'dealing', 'chat_messages'));
    }

    public function store(ChatMessageRequest $request, $dealing_id)
    {
        $user_id = Auth::id();
        $image_path = null;

        if( !empty( $request->file('img_file') )) {
            $file = $request->file('img_file');
            $originalName = $file->getClientOriginalName();
            $file->storeAs('public/', $originalName);
            $image_path = 'storage/' . $originalName;
        }

        $new_message = ChatMessage::create([
            'dealing_id' => $dealing_id,
            'user_id' => $user_id,
            'message' => $request->message,
            'image' => $image_path,
        ]);

        return redirect(route('dealing.chat', ['dealing_id' => $dealing_id]));
    }

    public function Sent(Request $request)
    {
        $message = new Message;
        $message->message_id = $request->message_id;
        $message->message = $requeest->message;

        broadcast(new MessageSent($message))->toOthers();
    }

    public function update(Request $request, $dealing_id)
    {
        $edit_message = ChatMessage::find($request->chat_message_id)->update([
            'message' => $request->message,
        ]);

        return redirect(route('dealing.chat', ['dealing_id' => $dealing_id]));
    }

    public function delete(Request $request, $dealing_id)
    {
        // 「削除」ボタンん押下時のアクション。論理削除としてフラグ更新のみ。
        // 「編集」のアクションと類似するが、ボタンが異なるため別々とする。
        $edit_message = ChatMessage::find($request->chat_message_id)->update([
            'delete_flug' => '1',
        ]);

        return redirect(route('dealing.chat', ['dealing_id' => $dealing_id]));
    }

    public function complete(Request $request, $dealing_id)
    {
        // 取引完了モーダルでの評価入力・送信により当アクションを実行

        $user_id = $request->user_id;
        $dealing = Dealing::with('item')->find($dealing_id);

        if( $dealing->user_id == $user_id ) {
            // 送信が購入者であった場合、ステータスと購入者評価を更新
            $completed_status = "completed";
            $costomer_rating = $request->rating;

            $complete_dealing = Dealing::find($dealing_id)->update([
                'status' => $completed_status,
                'customer_rating' => $costomer_rating,
            ]);

            // DB更新後、出品者へメールを送る
            $seller_user_id = $dealing->item->user_id;
            $seller_user = User::find($seller_user_id);
            $name = $seller_user->name;

            Mail::to($seller_user->email)->send(new MailableMailtrap($name, $dealing_id));

        } else {
            // 送信が出品者であった場合、出品者評価のみ更新
            $seller_rating = $request->rating;
            
            $complete_dealing = Dealing::find($dealing_id)->update([
                'seller_rating' => $seller_rating,
            ]);
        }

        return redirect(route('index'));
    }
}
