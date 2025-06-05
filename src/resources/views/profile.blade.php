@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-heading">
        <img src="{{ $user->image }}" alt="" width="150" height="150" />
        <div class="content-heading__name">{{ $user->name }}</div>
        <a class="content-heading__edit-link" href="/mypage/profile" >プロフィールを編集</a>
    </div>
    <div class="tab-titles">
        <a href="/mypage{{ $tab = 'sell' }}"
            class="tab-title {{ (Request::is('mypagesell') ? 'active' : '') }}"
            @if (Request::is('mypagesell'))
                style="color: #FF5555;"
            @endif
            data-tab="tab1">
            出品した商品
        </a>
        <a href="/mypage{{ $tab = 'buy' }}"
            class="tab-title {{ (Request::is('mypagebuy') ? 'active' : '') }}"
            @if (Request::is('mypagebuy'))
                style="color: #FF5555;"
            @endif
            data-tab="tab2">
            購入した商品
        </a>
    </div>

    <!-- 以下、item-cardの出力は、タブボタンが押されている前提で表示。そうでない場合は非表示 -->
    @if(isset($tab_return))
    @if(isset($items))
    @if(isset($purchases))
    <div class="tab-contents">
        @if ($tab_return == 'sell')
          <!-- 出品の場合はitemsテーブルで絞り込んだため、foreachもitemsのみで十分 -->
            @foreach( $items as $item )
            <a class="item-card" href="{{ URL::to('/item/:' . $item['id']) }}">
                <div class="item-card__image">
                    <img src="{{ asset($item->image) }}" alt="" width="290" height="290" />
                </div>
                    <!-- ただし、出品の場合はsoldを表示するためにpurchasesを確認 -->
                @if ( in_array($item->id, $purchases->pluck('item_id')->toArray()) )
                    <div class="item-sold__image">
                        <img src="{{ asset('storage/sold-img.png') }}" alt="" />
                    </div>
                @endif
                <div class="item-card__name-tag">
                    <span>{{ $item->name }}</span>
                </div>
            </a>
            @endforeach
        @elseif ($tab_return == 'buy')
          <!-- itemsテーブルの項目を取得するためforeachもitemsだが、購入の場合はpurchasesテーブルで絞り込んだため、 purchasesのitem_idに存在する場合のみitem-cardを表示する-->
            @foreach( $items as $item )
                @if ( in_array($item->id, $purchases->pluck('item_id')->toArray()) )
                    <a class="item-card" href="{{ URL::to('/item/:' . $item['id']) }}">
                        <div class="item-card__image">
                            <img src="{{ asset($item->image) }}" alt=""  width="290" height="290" />
                        </div>
                        <!-- 購入の場合はsoldは非表示（全てsoldのため） -->
                        <div class="item-card__name-tag">
                            <span>{{ $item->name }}</span>
                        </div>
                    </a>
                @endif
            @endforeach
        @endif
        </div>
    </div>
    @endif
    @endif
    @endif
</div>

@endsection