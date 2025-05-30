            <span class="header__search">
                <form class="search-form">
                    @csrf
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" />
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
                <a class="header__nav-link--mypage" href="/mypage">マイページ</a>
                <a class="header__nav-link--sell" href="/sell">出品</a>
            </span>
