<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proculus Enterprise Vendor - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        
        {{-- OVERLAY GELAP UNTUK HP --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- SIDEBAR UTAMA VENDOR --}}
        <div class="sidebar" id="sidebarNav">
            <div class="sidebar-header">
                <h3>E-PROC VENDOR</h3>
            </div>

            <ul class="nav flex-column sidebar-menu">
                <li class="nav-item">
                    <a href="{{ route('vendor.dashboard') }}"
                        class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-chart-pie w-20px text-center me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.tenders') }}"
                        class="nav-link {{ request()->routeIs('vendor.tenders') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-gavel w-20px text-center me-2"></i> Pengadaan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.bids') }}"
                        class="nav-link {{ request()->routeIs('vendor.bids') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-receipt w-20px text-center me-2"></i> Penawaran Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.documents') }}"
                        class="nav-link {{ request()->routeIs('vendor.documents') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-shield w-20px text-center me-2"></i> Dokumen Vendor
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.reports') }}"
                        class="nav-link {{ request()->routeIs('vendor.reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice w-20px text-center me-2"></i> Laporan Vendor
                    </a>
                </li>
            </ul>
        </div>

        {{-- AREA UTAMA KONTEN VENDOR --}}
        <div class="main-content">
            {{-- TOP NAVBAR VENDOR --}}
            <div class="top-navbar">
                <div class="d-flex align-items-center gap-3">
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
                            {{ substr(Auth::user()->name ?? 'V', 0, 1) }}
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="border-radius: 12px; min-width: 220px;">
                            <li class="px-3 py-2">
                                <p class="mb-0 fw-bold text-dark">{{ Auth::user()->vendor->company_name ?? Auth::user()->name }}</p>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            {{-- MENEMPATKAN MENU PENGATURAN VENDOR DI SINI --}}
                            <li>
                                <a href="{{ route('vendor.settings') }}" class="dropdown-item fw-bold py-2 {{ request()->routeIs('vendor.settings') ? 'text-warning' : 'text-dark' }}" style="border-radius: 8px;">
                                    <i class="fa-solid fa-user-gear me-2 text-secondary"></i> Pengaturan Akun
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item fw-bold text-danger py-2" style="border-radius: 8px;">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- PANEL TAMPILAN AREA KONTEN VENDOR --}}
            <div class="content-area">
                @yield('content')
            </div>
        </div>

    </div>

    {{-- LOGIKA TOGGLE JAVASCRIPT REKURSIVE --}}
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