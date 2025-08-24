<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH FLEA MARKET</title>
    <script src="https://kit.fontawesome.com/42694f25bf.js" crossorigin="anonymous"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('storage/logo.svg') }}" alt="" />
            </a>
            @if (Route::is('dealing.chat'))
                <!-- 取引チャット画面では、検索欄、ログアウト、マイページ、出品の各メニューは表示しない -->
            @else
            <span class="header__search">
                <form class="search-form"
                    @if (Request::is('mylist'))
                        action="/mylist:{keyword}"
                    @else
                        action="/search:{keyword}"
                    @endif

                    method="get"
                >
                    @csrf
                    <input type="text" name="keyword" 
                        @if (isset($keyword))
                            value="{{ $keyword }}"
                        @else
                            value="{{ old('keyword') }}"
                            placeholder="なにをお探しですか？"
                        @endif
                    />

                </form>
            </span>
            <span class="header__nav">
                @if (Auth::check())
                    <form action="/logout" method="get">
                        @csrf
                        <button class="header__nav-button">ログアウト</button>
                    </form>
                @else
                    <a class="header__nav-link--login" href="/login">ログイン</a>
                @endif
                <a class="header__nav-link--mypage" href="/mypagesell">マイページ</a>
                <a class="header__nav-link--sell" href="/exhibition">出品</a>
            </span>
            @endif
        </div>
    </header>
    <main>
        @yield('content')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            toastr.options = {
                "closeButton" : true,
                "progressBar" : true,
                "positionClass" : "toast-bottom-right",
            }

            @if(Session::has('flashSuccess'))
                toastr.success("{{ session('flashSuccess') }}");
            @endif
        </script>
    </main>    
</body>
</html>