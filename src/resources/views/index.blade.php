@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

    @component('components.header-nav-component')

@section('content')
<div class="content">
    <div class="tab-titles">
        <a class="tab-title {{ (Request::is('/') ? 'active' : '') }}"
            href="{{ route('index') }}" 
            @if (Request::is('/'))
                style="color: #FF5555;"
            @endif
            data-tab="tab1">
            おすすめ
        </a>
        <a class="tab-title {{ (Request::is('mylist') ? 'active' : '') }}"
            @if( isset($keyword) )
                href="/mylist:{{ $keyword }}"
            @else
                href="/mylist"
            @endif

            @if (Route::is('mylist'))
                style="color: #FF5555;"
            @elseif (Route::is('mylist.keyword'))
                style="color: #FF5555;"
            @endif
            data-tab="tab2">
            マイリスト
        </a>
    </div>
    <div class="tab-contents">
        <div class="tab-content">
            @if( !isset($items) )
                <!-- 何も表示しない。未認証でマイリストを押した場合、items情報がない。 -->
            @else
                @foreach( $items as $item )
                <a class="item-card" href="{{ URL::to('/item/:' . $item['id']) }}">
                    <div class="item-card__image">
                        <img src="{{ asset($item->image) }}" alt="" />
                    </div>

                    <!-- 購入済商品を表示(purchasesテーブルに存在する購入完了のitem_id) -->
                    @if ( in_array($item->id, $purchases->pluck('item_id')->toArray()) )
                        <div class="item-sold__display">Sold</div>
                    @endif

                    <div class="item-card__name-tag">
                        <span>{{ $item->name }}</span>
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection
