<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH FLEA MARKET</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('storage/logo.svg') }}" alt="" />
            </a>
            @if (Auth::check())
                <span class="header__search">
                    <form class="search-form">
                        @csrf
                        <input type="text" name="keyword" placeholder="なにをお探しですか？" />
                    </form>
                </span>
                <span class="header__nav">
                    <form action="/logout" method="get">
                        @csrf
                        <button class="header__nav-button">ログアウト</button>
                    </form>
                    <a class="header__nav-link--mypage" href="/mypage">マイページ</a>
                    <a class="header__nav-link--sell" href="/sell">出品</a>
                </span>
            @endif
        </div>
    </header>
    <main>
        @yield('content')
    </main>    
</body>
</html>