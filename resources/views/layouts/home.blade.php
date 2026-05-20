<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Procurement Admin</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

<div class="main-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        <div>

            <h2 class="logo">
                E-Procurement
            </h2>

            <nav class="menu">

                <a href="{{ url('/') }}"
                   class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ url('/procurement') }}"
                   class="menu-item {{ request()->is('procurement') ? 'active' : '' }}">
                    Procurement
                </a>

                <a href="{{ url('/purchase-orders') }}"
                   class="menu-item {{ request()->is('purchase-orders') ? 'active' : '' }}">
                    Purchase Order
                </a>

                <a href="{{ url('/contracts') }}"
                   class="menu-item {{ request()->is('contracts') ? 'active' : '' }}">
                    Contracts
                </a>

                <a href="{{ url('/reports') }}"
                   class="menu-item {{ request()->is('reports') ? 'active' : '' }}">
                    Reports
                </a>

                <a href="{{ url('/settings') }}"
                   class="menu-item {{ request()->is('settings') ? 'active' : '' }}">
                    Settings
                </a>

            </nav>

        </div>

        <div class="sidebar-footer">
            <h5>Admin Panel</h5>
            <p>
                Modern E-Procurement Dashboard
            </p>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="content">

        {{-- TOPBAR --}}
        <div class="topbar">

            <div>

                <h3 class="page-title">
                    E-Procurement Admin
                </h3>

                <p class="page-subtitle">
                    Welcome back, Admin
                </p>

            </div>

            <div class="topbar-right">

                <input
                    type="text"
                    placeholder="Search..."
                    class="search-input"
                >

                <div class="profile"></div>

            </div>

        </div>

        {{-- PAGE CONTENT --}}
        @yield('content')

    </main>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>