<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;


class PurchaseController extends Controller
{
    public function purchase(Request $request, $item_id) {

        $user_id = Auth::id();
        $item = Item::find($item_id);

        $isExist = Purchase::PurchaseSearch($user_id, $item_id)->exists();

        if($isExist == "1") {

            $purchase = Purchase::PurchaseSearch($user_id, $item_id)->first();

            $shipping = [
                'shipping_post_code' => $purchase['shipping_post_code'],
                'shipping_address' => $purchase['shipping_address'],
                'shipping_building' => $purchase['shipping_building'],
            ];
        } else {

            $user = User::find($user_id);

            $shipping = [
                'shipping_post_code' => $user->post_code,
                'shipping_address' => $user->address,
                'shipping_building' => $user->building,
            ];
        }
          
        return view('purchase', compact('shipping','item'));
    }

    public function store(Request $request, $item_id) {

        $user_id = Auth::id();

        switch ($request->payment_method) {
            case ("コンビニ払い"):
                $payment_method = '1';
                break;
            case ("カード払い"):
                $payment_method = '2';
                break;
        };

        $shipping_post_code = $request->shipping_post_code;
        $shipping_address = $request->shipping_address;
        $shipping_building = $request->shipping_building;

        $pachase = Purchase::updateOrCreate(
            ['user_id' => $user_id, 'item_id' => $item_id],
            [
            'payment_method' => $payment_method,
            'shipping_post_code' => $shipping_post_code,
            'shipping_address' => $shipping_address,
            'shipping_building' => $shipping_building,
            ]
        );

        return redirect('index');
    }

    public function updateShipping(Request $request, $item_id) {

        $shipping = [
            'shipping_post_code' => $request->shipping_post_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ];

        return view('address', compact('shipping', 'item_id'));

    }

    public function shippingUpdate(Request $request, $item_id) {

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
}
