@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-inner">
        <div class="content-heading">住所の変更</div>
        <form class="input-form" action="/purchase/address/:{{ $item_id }}/update" method="post">
            @csrf
            <div class="input-form__group">
                <div class="input-form__label">郵便番号</div>
                <input class="input-form__text" type="text" name="shipping_post_code" value="{{ $shipping['shipping_post_code'] }}" />
            </div>
            <div class="input-form__group">
                <div class="input-form__label">住所</div>
                <input class="input-form__text" type="text" name="shipping_address" value="{{ $shipping['shipping_address'] }}" />
            </div>
            <div class="input-form__group">
                <div class="input-form__label">建物名</div>
                <input class="input-form__text" type="text" name="shipping_building" value="{{ $shipping['shipping_building'] }}" />
            </div>
            <div class="input-form__button">
                <button type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>

@endsection