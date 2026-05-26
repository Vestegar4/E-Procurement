<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Proculus E-Procurement - @yield('title', 'Welcome')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg glass-navbar shadow-sm py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" style="color: #1A1A1A; letter-spacing: -0.02em;" href="/">
                Proculus
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('home') }}" class="btn btn-light border">Home</a>
                    <a href="{{ route('login') }}" class="btn-outline-action" style="padding: 8px 24px;">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary-action" style="padding: 8px 24px;">Register Vendor</a>
                @else
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-dark">Logout</button>
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