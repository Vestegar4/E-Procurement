<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proculus Enterprise Admin - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        
        {{-- OVERLAY GELAP UNTUK HP --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- SIDEBAR UTAMA --}}
        <div class="sidebar" id="sidebarNav">
            <div class="sidebar-header">
                <h3>PROCULUS</h3>
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
                        <i class="fa-solid fa-building w-20px text-center me-2"></i> Data Vendor
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.procurement') }}"
                        class="nav-link {{ request()->routeIs('admin.procurement') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-gavel w-20px text-center me-2"></i> Paket Tender
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products') }}"
                        class="nav-link {{ request()->routeIs('admin.products') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-box w-20px text-center me-2"></i> Katalog Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.purchase-order') }}"
                        class="nav-link {{ request()->routeIs('admin.purchase-order') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-receipt w-20px text-center me-2"></i> Purchase Order
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports') }}"
                        class="nav-link {{ request()->routeIs('admin.reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice w-20px text-center me-2"></i> Pusat Laporan
                    </a>
                </li>
            </ul>
        </div>

        {{-- AREA UTAMA KONTEN --}}
        <div class="main-content">
            {{-- TOP NAVBAR --}}
            <div class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    {{-- Tombol Toggle Garis Tiga --}}
                    <button type="button" class="btn-toggle-sidebar" id="sidebarToggle" aria-label="Menu Toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="page-title mb-0 d-none d-sm-block">@yield('title')</h4>
                </div>

                <div class="top-right d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold text-uppercase shadow-sm"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 44px; height: 44px; background: var(--color-primary); color: var(--color-accent); cursor: pointer; border: 2px solid var(--color-accent); font-size: 1.1rem;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="border-radius: 12px; min-width: 220px;">
                            <li class="px-3 py-2">
                                <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <small class="text-muted">{{ Auth::user()->email ?? 'admin@procurement.com' }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            {{-- MENEMPATKAN MENU PENGATURAN DI SINI --}}
                            <li>
                                <a href="{{ route('admin.settings') }}" class="dropdown-item fw-bold py-2 {{ request()->routeIs('admin.settings') ? 'text-warning' : 'text-dark' }}" style="border-radius: 8px;">
                                    <i class="fa-solid fa-gear me-2 text-secondary"></i> Pengaturan Sistem
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item fw-bold text-danger py-2" style="border-radius: 8px;">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar Aplikasi
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- PANEL TAMPILAN AREA --}}
            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background: #E6F4EA; color: #137333; border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background: #FCE8E6; color: #C5221F; border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

    </div>

    {{-- LOGIKA TOGGLE JAVASCRIPT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.getElementById("sidebarToggle");
            const sidebarElement = document.getElementById("sidebarNav");
            const overlayElement = document.getElementById("sidebarOverlay");

            if (toggleButton && sidebarElement && overlayElement) {
                toggleButton.addEventListener("click", function (event) {
                    event.preventDefault();
                    if (window.innerWidth > 992) {
                        sidebarElement.classList.toggle("toggled");
                    } else {
                        sidebarElement.classList.toggle("mobile-open");
                        overlayElement.classList.toggle("show");
                    }
                });
            }

            if (overlayElement) {
                overlayElement.addEventListener("click", function () {
                    sidebarElement.classList.remove("mobile-open");
                    overlayElement.classList.remove("show");
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>