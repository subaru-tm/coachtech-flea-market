@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-inner">
        <h2 class="content-heading">プロフィール設定</h2>
        <div class="content-input">
            <form class="input-form" action="/mypage/profile/update" method="post">
                @method('PATCH')
                @csrf
                <div class="input-form__group">
                    <label class="input-form__group-label"></label>
                    <input class="input-form__group-file--button" type="file" name="img_file"/>
                </div>
                <div class="input-form__group">
                    <label class="input-form__group-label">ユーザー名</label>
                    <input class="input-form__group-text" type="text" name="name" value="{{ $user['name'] }}" />
                </div>
                <div class="input-form__group">
                    <label class="input-form__group-label">郵便番号</label>
                    <input class="input-form__group-text" type="text" name="post_code" value="{{ $user['post_code'] }}" />
                </div>
                <div class="input-form__group">
                    <label class="input-form__group-label">住所</label>
                    <input class="input-form__group-text" type="text" name="address" value="{{ $user['address'] }}" />
                </div>
                <div class="input-form__group">
                    <label class="input-form__group-label">建物名</label>
                    <input class="input-form__group-text" type="text" name="building" value="{{ $user['building'] }}" />
                </div>
                <div class="input-form__button">
                    <button class="input-form__button-submit" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection