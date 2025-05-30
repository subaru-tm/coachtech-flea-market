@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

    @component('components.header-nav-component')

@section('content')
<div class="content">
    <div class="tab-titles">
        <a href="\" class="tab-title" data-tab="tab1">おすすめ</a>
        <form action="/{page}" method="get">
            <input type="hidden" name="page" value="mypage" />
            <button class="tab-title" data-tab="tab2">マイリスト</button>
        </form>
    </div>
    <div class="tab-contents">
        <div class="tab-content">
            @foreach( $items as $item )
            <a class="iten-card" href="{{ URL::to('/item/:' . $item['id']) }}">
                <div class="item-card__image">
                    <img src="{{ asset($item->image) }}" alt="" />
                </div>
                <div class="item-card__name-tag">
                    <span>{{ $item->name }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection
