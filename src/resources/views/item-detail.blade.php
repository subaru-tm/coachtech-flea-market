@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item-detail.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-image-area">
        <img src="{{ asset($item->image) }}" alt="" width="600" height="600" />
    </div>

    <div class="item-description-area">
        <h1  class="item__name" >{{ $item->name }}</h1>
        <div class="item__brand">{{ $item->brand }}</div>
        <div class="item__price">
            <span class="item__price-unit">¥</span>
            <span class="item__price-price">{{ number_format($item->price) }}</span>
            <span class="item__price-unit">(税込)</span>
        </div>
        <div class="nice-comment__area">
            <div class="nice-group">
                <form class="nice-form" action="/item/:{{ $item->id }}/nice" method="post">
                    @csrf
                    <button type="submit">
                        @if (isset($mylist_item->nice_flug) && $mylist_item->nice_flug == '1')
                            <img src="{{ asset('storage/nice-button-pushed.png') }}" alt="" width="40" height="40"/>
                        @else
                            <img src="{{ asset('storage/nice-button.png') }}" alt="" width="40" height="40"/>
                        @endif
                    </button>
                </form>
                <div class="nice-counter">{{ $nice_count }}</div>
            </div>
            <div class="comment-group">
                <img src="{{ asset('storage/comment-mark.png') }}" alt="" width="40" height="40" />
                <div class="comment-counter">{{ $comment_count }}</div>
            </div>
        </div>
        <div class="item__purchase-procedure">
            <a   class="item__purchase-procedure--link" href="/purchase/:{{ $item['id'] }}">購入手続きへ</a>
        </div>

        <h2  class="item__description-section">商品説明</h2>
        <div class="item__color">カラー：</div>
        <div class="item__new-used"></div>
        <div class="item__description">{{ $item->description }}</div>
        <div class="item__delivery"></div>

        <h2  class="item__description-section">商品の情報</h2>
        <div class="item__category">
            <h3 class="item__field-header">カテゴリー</h3>
            @foreach ($categories as $category)
                @if ( in_array( $category->id, $item->categories->pluck('id')->toArray() ))
                    <div class="item__category-tag">
                        {{ $category->name }}
                    </div>
                @else
                    @continue
                @endif
            @endforeach
        </div>
        <div class="item__condition">
            <h3  class="item__field-header">商品の状態</h3>
            <div class="item__condition-content">
               @switch ($item->condition)
                    @case("1")
                        良好
                        @break
                    @case("2")
                        目立った傷や汚れなし
                        @break
                    @case("3")
                        やや傷や汚れあり
                        @break
                    @case("4")
                        状態が悪い
                        @break
                @endswitch
            </div>
        </div>

        <h2  class="item__description-section--comment">コメント({{ $comment_count }})</h2>
        @if ($comment_count <> '0')
            <div class="item__comment-reference">
                <div class="reference-user__info">
                    <img src="{{ asset($comment_user->image) }}" alt="" width="70" height="70" />
                    <div class="reference-user__info-name">{{ $comment_user->name }}</div>
                </div>
                <div class="reference-comment__content">{{ $other_comment->comment }}</div>
            </div>
        @endif
        <div class="item__comment-mine">
            <h3  class="item__comment-form--header">商品へのコメント</h3>
            <form class="comment-form" action="/item/:{{ $item->id }}/comment" method="post">
                @csrf
                <textarea name="comment" value="{{ old('comment') }}" >@if (isset($mylist_item)){{ $mylist_item->comment }}@endif</textarea>
                <div class="comment-form__alert">
                    @error('comment')
                        {{ $message }}
                    @enderror
                </div>
                <div class="comment-form__button">
                    <button type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection