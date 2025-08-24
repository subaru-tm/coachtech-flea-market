@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-heading">
        <img src="{{ $user->image }}" alt="" width="150" height="150" />
        <div class="content-heading__info">
            <div class="content-heading__info-name">{{ $user->name }}</div>
            @if ($rating_average == 0)
                <!-- まだ評価がない場合は何も表示しない -->
            @else
                <div class="user-rating__average">
                    <input class="rating__input" id="star1" name="rating" type="radio" value="1"
                        @if ($rating_average >= 1)
                            checked
                        @endif
                        disabled
                    >
                    <label class="rating__label" for="star1"><i class="fa-solid fa-star"
                        @if ($rating_average >= 1)
                            style="color: #FFF048;"
                        @endif
                    ></i></label>

                    <input class="rating__input" id="star2" name="rating" type="radio" value="2"
                        @if ($rating_average >= 2)
                            checked
                        @endif
                        disabled
                    >
                    <label class="rating__label" for="star2"><i class="fa-solid fa-star"
                        @if ($rating_average >= 2)
                            style="color: #FFF048;"
                        @endif
                    ></i></label>

                    <input class="rating__input" id="star3" name="rating" type="radio" value="3"
                        @if ($rating_average >= 3)
                            checked
                        @endif
                        disabled
                    >
                    <label class="rating__label" for="star3"><i class="fa-solid fa-star"
                        @if ($rating_average >= 3)
                            style="color: #FFF048;"
                        @endif
                    ></i></label>

                    <input class="rating__input" id="star4" name="rating" type="radio" value="4"
                        @if ($rating_average >= 4)
                            checked
                        @endif
                        disabled
                    >
                    <label class="rating__label" for="star4"><i class="fa-solid fa-star"
                        @if ($rating_average >= 4)
                            style="color: #FFF048;"
                        @endif
                    ></i></label>

                    <input class="rating__input" id="star5" name="rating" type="radio" value="5"
                        @if ($rating_average == 5)
                            checked
                        @endif
                        disabled
                    >
                    <label class="rating__label" for="star5"><i class="fa-solid fa-star"
                        @if ($rating_average == 5)
                            style="color: #FFF048;"
                        @endif
                    ></i></label>
                </div>
            @endif
        </div>
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
        <a href="/mypage{{ $tab = 'dealing' }}"
            class="tab-title {{ (Request::is('mypagedealing') ? 'active' : '') }}"
            @if (Request::is('mypagedealing'))
                style="color: #FF5555;"
            @endif
            data-tab="tab3">
            取引中の商品
            <span class="new_messages_count">{{ $new_messages_count_total }}</span>
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
        @elseif ($tab_return == 'dealing')
            @foreach( $items as $item )
                @foreach( $item->dealings as $dealing )
                    <a class="item-card" href="{{ URL::to('/dealing/' . $dealing->id) }}">
                        <div class="item-card__image">
                            <img src="{{ asset($item->image) }}" alt=""  width="290" height="290" />
                        </div>
                        <!-- controllerで集計した商品ごとの新着件数を該当商品分だけ取得 -->
                        <?php
                            if( isset($new_messages_count_byitem[$item->id]) ) {
                            $new_messages_count = $new_messages_count_byitem[$item->id];
                            }else {
                                $new_messages_count = null;
                            }
                        ?>
                        <span class="new-messages__count-byitem"
                            @if ($new_messages_count <> null)
                                style="background-color: #FF0000;"
                            @endif >
                            {{ $new_messages_count }}
                        </span>

                        <!-- 取引中の場合はsold表示は想定外とする（売れていないことを前提） -->
                        <div class="item-card__name-tag">
                            <span>{{ $item->name }}</span>
                        </div>
                    </a>
                @endforeach
            @endforeach
        @endif
        </div>
    </div>
    @endif
    @endif
    @endif
</div>

@endsection