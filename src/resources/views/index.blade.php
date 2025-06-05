@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

    @component('components.header-nav-component')

@section('content')
<div class="content">
    <div class="tab-titles">
        <a href="/" 
            class="tab-title {{ (Request::is('/') ? 'active' : '') }}"
            @if (Request::is('/'))
                style="color: #FF5555;"
            @endif
            data-tab="tab1">
            おすすめ
        </a>
        <a href="/mylist"
            class="tab-title {{ (Request::is('mylist') ? 'active' : '') }}"
            @if (Request::is('mylist'))
                style="color: #FF5555;"
            @endif
            data-tab="tab2">
            マイリスト
        </a>
    </div>
    <div class="tab-contents">
        <div class="tab-content">
            @foreach( $items as $item )
            <a class="item-card" href="{{ URL::to('/item/:' . $item['id']) }}">
                <div class="item-card__image">
                    <img src="{{ asset($item->image) }}" alt="" />
                </div>
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
        </div>
    </div>
</div>

@endsection
