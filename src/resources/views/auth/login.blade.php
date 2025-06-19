@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content-inner">
        <div class="content-title">
            <h1>ログイン</h1>
        </div>
        <form class="form" action="/login" method="post">
            @csrf
            <div class="form__group">
                <div><label for="email">メールアドレス</label></div>
                <input type="text" name="email" id="email" />
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
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <button type="submit">ログインする</button>
            </div>
            <div class="form__group-link">
                <a href="/register">会員登録はこちら</a>
            </div>
       </form>
    </div>
</div>

@endsection