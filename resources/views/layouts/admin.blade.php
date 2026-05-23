<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Procurement Admin - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>

<body>

    <div class="wrapper">

        {{-- SIDEBAR --}}
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Proculus</h3>
            </div>

            <ul class="nav flex-column sidebar-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-pie w-20px text-center me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                        class="nav-link {{ request()->routeIs('admin.users') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-users w-20px text-center me-2"></i> Manajemen User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.vendors') }}"
                        class="nav-link {{ request()->routeIs('admin.vendors') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-building w-20px text-center me-2"></i> Vendor Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.procurement') }}"
                        class="nav-link {{ request()->routeIs('admin.procurement') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-cart-shopping w-20px text-center me-2"></i> Procurement
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products') }}"
                        class="nav-link {{ request()->routeIs('admin.products') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-box-open w-20px text-center me-2"></i> Produk & Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.po') }}"
                        class="nav-link {{ request()->routeIs('admin.po') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-20px text-center me-2"></i> Purchase Order
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports') }}"
                        class="nav-link {{ request()->routeIs('admin.reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-lines w-20px text-center me-2"></i> Laporan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="{{ route('admin.settings') }}"
                        class="nav-link {{ request()->routeIs('admin.settings') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-gear w-20px text-center me-2"></i> Pengaturan
                    </a>
                </li>
            </ul>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="main-content">

            {{-- TOP NAVBAR --}}
            <div class="top-navbar">
                <div>
                    <h4 class="page-title">@yield('title', 'Dashboard Overview')</h4>
                </div>
                <div class="top-right">

                    {{-- Search --}}
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control border-0 bg-light" placeholder="Cari...">
                        <button class="btn btn-light bg-light border-0">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>

                    {{-- Notification --}}
                    <div class="dropdown">
                        <button class="notification-btn position-relative" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 280px;">
                            <li>
                                <h6 class="dropdown-header">Notifikasi</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span class="dropdown-item-text text-muted small text-center d-block py-3">
                                    Tidak ada notifikasi baru
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- Profile --}}
                    <div class="dropdown">
                        <div class="admin-profile" data-bs-toggle="dropdown" aria-expanded="false"
                            style="cursor: pointer;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <span class="dropdown-item-text fw-bold">{{ Auth::user()->name }}</span>
                            </li>
                            <li>
                                <span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- CONTENT AREA --}}
            <div class="content-area">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

    </div>

    @stack('scripts')
</body>

</html>
