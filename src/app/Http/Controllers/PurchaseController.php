<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function purchase(Request $request, $item_id) {

        $user_id = Auth::id();
        $item = Item::find($item_id);

        $isExist = Purchase::PurchaseSearch($user_id, $item_id)->exists();

        if($isExist == "1") {
            // 既にpurchasesテーブルに同じ商品、ユーザーでレコードが存在する場合、
            // 配送先住所はそのレコードから取得。
            $purchase = Purchase::PurchaseSearch($user_id, $item_id)->first();

            $shipping = [
                'shipping_post_code' => $purchase['shipping_post_code'],
                'shipping_address' => $purchase['shipping_address'],
                'shipping_building' => $purchase['shipping_building'],
            ];
        } else {
            // 新規レコードとなる場合、profile情報があるusersテーブルから住所等を
            // デフォルトで取得。
            $user = User::find($user_id);

            $shipping = [
                'shipping_post_code' => $user->post_code,
                'shipping_address' => $user->address,
                'shipping_building' => $user->building,
            ];
        }

        if(isset($request['payment_method'])) {
            // payment_methodが選択された際のリダイレクト用。payment_methodも一緒にviewに渡す。
            $payment_method = $request['payment_method'];

            return view('purchase', compact('shipping','item','payment_method'));
        }

        return view('purchase', compact('shipping','item'));
    }

    public function store(PurchaseRequest $request, $item_id) {

        $item = Item::find($item_id);

        $user_id = Auth::id();

        $shipping_post_code = $request->shipping_post_code;
        $shipping_address = $request->shipping_address;
        $shipping_building = $request->shipping_building;

        $pachase = Purchase::updateOrCreate(
            ['user_id' => $user_id, 'item_id' => $item_id],
            [
            'payment_method' => $request->payment_method,
            'shipping_post_code' => $request->shipping_post_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building ?? null,
            ]
        );

        return redirect(route('stripe'));
    }

    public function editShipping(Request $request, $item_id) {

        $shipping = [
            'shipping_post_code' => $request->shipping_post_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ];

        return view('address', compact('shipping', 'item_id'));

    }

    public function shippingUpdate(AddressRequest $request, $item_id) {

        $user_id = Auth::id();

        $purchase = Purchase::updateOrCreate(
            ['user_id' => $user_id, 'item_id' => $item_id],
            [
                'shipping_post_code' => $request->shipping_post_code,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,    
            ]
            );
        return redirect()->route('purchase', compact('item_id'));
    }

    public function stripe() {
        // 応用要件 購入完了後、stripeの決済画面んを開くために使用
        return view('stripe.stripe-index');
    }
}
