@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="content">
    <form class="purchase-form" action="/purchase/:{{ $item['id'] }}/commit" method="post">
        @csrf

        <div class="option__area">
            <div class="item-info">
                <div class="item-img">
                    <img src="{{ asset($item->image) }}" alt="" width="177" height="177" />
                </div>
                <div class="item-name-price">
                    <h3 class="item-name">{{ $item->name }}</h3>
                    <div class="item-price">¥ {{ number_format($item->price) }}</div>
                </div>
            </div>
            <div class="payment-method__option">
                <div class="field-label">支払い方法</div>
                <select id="payment" name="payment_method">
                    <option value="" selected>選択してください
                    </option>
                    <option value="konbini">コンビニ払い</option>
                    <option value="card">クレジットカード払い</option>
                </select>

                <div class="input-feild__alert">
                    @error('payment_method')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="shipping-address__change">
                <div class="purchase__flex">
                    <div class="field-label">配送先</div>
                    <!-- id="destination__update" -->
                    <a href="/purchase/address/:{{ $item['id'] }}" class="shipping-address__change-button" >変更する</a>
                </div>
                <div class="shipping__field-display">
                    <label>〒 <input class="input_destination" name="shipping_post_code" value="{{ $shipping['shipping_post_code'] }}" readonly /></label><br>
                    <input class="input_destination" name="shipping_address" value="{{ $shipping['shipping_address'] }}" readonly />
                    @if(isset($shipping['shipping_building']))
                        <input class="input_destination" name="shipping_building" value="{{ $shipping['shipping_building'] }}" readonly />
                    @endif
                </div>
<!--                <div class="setting__flex">
                    <button type="button" id="destination__setting">変更完了</button>
                </div>  -->
            </div>

            <div class="input-feild__alert">
                @error('shipping_post_code')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-feild__alert">
                @error('shipping_address')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-feild__alert">
                @error('shipping_building')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="subtotal-commit__area">
            <table class="subtotal-table">
                <tr class="subtotal-table__record">
                    <th class="subtotal-table__record-label">商品代金</th>
                    <td id="item__price" class="subtotal-table__record-item">¥{{ number_format($item->price)}}</td>
                </tr>
                <tr class="subtotal-table__record">
                    <th class="subtotal-table__record-label">支払方法</th>
                    <td id="pay_confirm" class="subtotal-table__record-item">コンビニ払い</td>
                </tr>
            </table>

            <button class="purchase-commit__form-button" type="submit">購入する</button>
        </div>
        <div class="space__area"></div> <!-- ←←スタイル調整のため追加 -->
    </form>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="{{ asset('js/purchase.js') }}"></script>

@endsection