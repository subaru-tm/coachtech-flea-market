@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="content">
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
            <!-- 支払い方法が選択されたら直ぐにページをリダイレクトしてpayment_method変数として値を取得。
                 なお、バリデーションのため、リダイレクト後は選択中の値をsellectタグでも表示(if文にて定義)。 -->
            <form class="form__payment-method" action="/purchase/:{{ $item['id'] }}" method="get">
                @csrf
                <div class="field-label">支払い方法</div>
                <select name="payment_method" onchange="this.form.submit()" value="{{ old('payment_method') }}">
                    <option value="" 
                        @if(!isset($payment_method))
                            selected
                        @endif
                        >選択してください
                    </option>
                    <option value="コンビニ払い"
                        @if(isset($payment_method) && $payment_method="コンビニ払い")
                            selected
                        @endif
                        >コンビニ払い
                    </option>
                    <option value="カード払い"
                        @if(isset($payment_method) && $payment_method="カード払い")
                            selected
                        @endif
                        >カード払い
                    </option>
                </select>
                <div class="input-feild__alert">
                    @error('payment_method')
                        {{ $message }}
                    @enderror
                </div>
            </form>
        </div>
        <div class="shipping-address__change">
            <form class="form__address-link" action="/purchase/address/:{{ $item['id'] }}" method="get">
            @csrf
                <div class="shipping-address__change-header">
                    <div class="field-label">配送先</div>
                    <button class="shipping-address__change-link" type="submit" >変更する</button>
                </div>
                 <div class="shipping__field-display">
                    <input type="hidden" name="shipping_post_code" value="{{ $shipping['shipping_post_code'] }}" form="address-update" />
                    {{ $shipping['shipping_post_code'] }}
                 </div>
                 <div class="shipping__field-display">
                    <input type="hidden" name="shipping_address" value="{{ $shipping['shipping_address'] }}" form="address-update" />
                    <input type="hidden" name="shipping_building" value="{{ $shipping['shipping_building'] }}" form="address-update" />
                    {{ $shipping['shipping_address'] }} {{ $shipping['shipping_building'] }}
                 </div>

            </form>
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
    </div>


    <div class="subtotal-commit__area">
        <form class="purchase-commit__form" action="/purchase/:{{ $item['id'] }}/commit" method="post">
        @csrf
        <table class="subtotal-table">
            <tr class="subtotal-table__record">
                <th class="subtotal-table__record-label">商品代金</th>
                <td class="subtotal-table__record-item!">¥{{ number_format($item->price)}}</td>
            </tr>
            <tr class="subtotal-table__record">
                <th class="subtotal-table__record-label">支払方法</th>
                <td class="subtotal-table__record-item!">
                    @if(isset($payment_method))
                        {{ $payment_method }}
                        <input type="hidden" name="payment_method" value="{{ $payment_method }}" />
                    @endif
                </td>
            </tr>
        </table>
        <div class="shipping-data__for-request">
            <input type="hidden"
                name="shipping_post_code"
                value="{{ $shipping['shipping_post_code'] }}"
            />
            <input type="hidden"
                name="shipping_address"
                value="{{ $shipping['shipping_address'] }}"
            />
            <input type="hidden"
                name="shipping_building"
                value="{{ $shipping['shipping_building'] }}"
            />
        </div>
        <button class="purchase-commit__form-button" type="submit">購入する</button>
        </form>
    </div>
    <div class="space__area"></div> <!-- ←←スタイル調整のため追加 -->
</div>

@endsection