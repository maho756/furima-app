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
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/like.js') }}"></script>
    
</body>

</html>