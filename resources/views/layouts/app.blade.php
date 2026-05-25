<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Proculus E-Procurement - @yield('title', 'Login')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg glass-navbar shadow-sm py-3 sticky-top">
        <div class="container">

            <a class="navbar-brand fw-bold text-danger fs-3" href="/">
                Proculus
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">

                @guest

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        Home
                    </a>

                    <a href="{{ route('login') }}" class="btn btn-outline-danger">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-pink">
                        Register Vendor
                    </a>
                @else
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button class="btn btn-danger">
                            Logout
                        </button>
                    </form>

                @endguest

            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
