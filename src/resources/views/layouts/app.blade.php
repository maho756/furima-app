<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>furima-app</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>



<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a href="/" class="header__logo">
                    <img src="{{ asset('images/logo.svg') }}" alt="coachtech">
                </a>

                @if (Auth::check())

                <form action="/" method="GET" class="header__search-form">
                    <input type="text" name="query" placeholder="なにをお探しですか？" class="header__search-input" value="{{ request('query') }}">
                    <input type="hidden" name="tab" value="{{ request('tab', $tab ?? 'recommend') }}">


                </form>
                <nav class="header__nav">
                    <ul class="header__nav-list">
                        <li class="header__nav-item">
                            <form method="POST"  action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="header__nav-link">ログアウト</button>
                            </form>
                        </li>
                        <li class="header__nav-item">
                            <a href="/mypage" class="header__nav-link">マイページ</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/sell" class="header__nav-button">出品</a>
                        </li>
                    </ul>
                </nav>
                @else
                <form action="/" method="GET" class="header__search-form">
                    <input type="text" name="query" placeholder="なにをお探しですか？" class="header__search-input" value="{{ request('query') }}">
                    <input type="hidden" name="tab" value="{{ request('tab', $tab ?? 'recommend') }}">


                </form>

                <nav class="header__nav">
                    <ul class="header__nav-list">
                        <li class="header__nav-item">
                            <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/mypage" class="header__nav-link">マイページ</a>
                        </li>
                        <li class="header__nav-item">
                            <a href="/sell" class="header__nav-button">出品</a>
                        </li>
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/like.js') }}"></script>
    
    @yield('js')
</body>

</html>