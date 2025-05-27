@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-title">
        <h1>会員登録</h1>
    </div>
    <div class="content-inner">
        <form class="form" action="/register" method="post">
            @csrf
            <div class="form__group">
                <div><label for="name">ユーザー名</label></div>
                <input type="text" name="name" id="name" />
                <div class="form__group-alert">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" />
                <div class="form__group-alert">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password" />
                <div class="form__group-alert">
                    @error('password')
                        @if( $message <> "パスワードと一致しません")
                            {{ $message }}
                        @endif
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" />
                <div class="form__group-alert">
                    @error('password')
                        @if( $message == "パスワードと一致しません")
                            {{ $message }}
                        @endif
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <button type="submit">登録する</button>
            </div>
            <div class="form__group-link">
                <a href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection