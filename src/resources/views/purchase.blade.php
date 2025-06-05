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
            <div class="field-label">支払い方法</div>
            <select form="purchase-commit" name="payment_method" id="selected-value" >
                <option value="" selected>選択してください</option>
                <option value="コンビニ払い">コンビニ払い</option>
                <option value="カード払い">カード払い</option>
            </select>
        </div>
        <div class="shipping-address__change">
            <form class="form__address-link" action="/purchase/address/:{{ $item['id'] }}" method="get" id="address-update">
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
        </div>
    </div>


    <div class="subtotal-commit__area">
        <form class="purchase-commit__form" action="/purchase/:{{ $item['id'] }}/commit" method="post" id="purchase-commit" >
        @csrf
        <table class="subtotal-table">
            <tr class="subtotal-table__record">
                <th class="subtotal-table__record-label">商品代金</th>
                <td class="subtotal-table__record-item!">¥{{ number_format($item->price)}}</td>
            </tr>
            <tr class="subtotal-table__record">
                <th class="subtotal-table__record-label">支払方法</th>
                <td class="subtotal-table__record-item!">
                    <p id="payment_method" ></p>
                    <script>
                        const select = document.getElementById("selected-value");
                        const selectedValueDisplay = document.getElementById("payment_method");

                        select.addEventListener("change", function(){
                            selectedValueDisplay.textContent = this.value;
                        });
                    </script>
                </td>
            </tr>
        </table>
        <div class="shipping-data__for-request">
            <input type="hidden"
                name="shipping_post_code"
                value="{{ $shipping['shipping_post_code'] }}"
                form="purchase-commit"
            />
            <input type="hidden"
                name="shipping_address"
                value="{{ $shipping['shipping_address'] }}"
                form="purchase-commit"
            />
            <input type="hidden"
                name="shipping_building"
                value="{{ $shipping['shipping_building'] }}"
                form="purchase-commit"
            />
        </div>
        <button class="purchase-commit__form-button" type="submit">購入する</button>
        </form>
    </div>
    <div class="space__area"></div>
</div>

@endsection