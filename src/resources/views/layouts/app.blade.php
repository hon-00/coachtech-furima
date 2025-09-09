<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH FURIMA</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <h1 class="header-logo">
                <a class="header-logo__link" href="">
                    <img class="header-logo__img" src="{{ asset('images/logo.svg') }}" alt="Logo" />
                </a>
            </h1>
        </div>
        <div class="search-area">
            <form class="search-form" action="" method="get">
                <div class="search-form__item">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                </div>
            </form>
        </div>
        <nav>
            <ul class="header-nav">
                <li class="header-nav__item">
                    <a class="header-nav__link" href="">ログイン</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__link" href="">マイページ</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__link" href="">出品</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>