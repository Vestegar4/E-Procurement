<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proculus Enterprise Admin - @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">
    {{-- VITE SUDAH MEMUAT BOOTSTRAP JS & CSS SECARA OTOMATIS --}}
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
                    <a href="{{ route('admin.purchase-order') }}"
                        class="nav-link {{ request()->routeIs('admin.purchase-order') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-receipt w-20px text-center me-2"></i> Purchase Order
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports') }}"
                        class="nav-link {{ request()->routeIs('admin.reports') ? 'active-menu' : '' }}">
                        <i class="fa-solid fa-file-invoice w-20px text-center me-2"></i> Laporan & Analitik
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

                    {{-- TOMBOL LONCENG NOTIFIKASI TARUH DI SINI --}}
                    @php
                        // Ambil 5 notif terakhir dari DB
                        $notifications = \App\Models\Notification::latest()->take(5)->get();
                    @endphp

                    <div class="dropdown">
                        <button
                            class="btn border-0 position-relative rounded-circle d-flex justify-content-center align-items-center"
                            type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 44px; height: 44px; background: var(--color-surface); border: 1px solid var(--color-border) !important;">
                            <i class="fa-solid fa-bell text-muted fs-5"></i>
                            @if ($notifications->count() > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"
                                    style="margin-top: 5px; margin-left: -5px;"></span>
                            @endif
                        </button>

                        {{-- WADAH DROPDOWN MENU YANG DIPERBAIKI --}}
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0"
                            aria-labelledby="notifDropdown"
                            style="width: 320px; border-radius: 12px; overflow: hidden;">

                            {{-- 1. HEADER DROPDOWN (Tetap / Tidak ter-scroll) --}}
                            <div class="bg-light border-bottom px-3 py-3" style="z-index: 10;">
                                <h6 class="mb-0 fw-bold" style="color: var(--color-text-main);">Pemberitahuan Sistem
                                </h6>
                            </div>

                            {{-- 2. ISI NOTIFIKASI (Area yang bisa di-scroll) --}}
                            <ul class="list-unstyled mb-0"
                                style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                                @forelse($notifications as $notif)
                                    <li>
                                        <a class="dropdown-item py-3 border-bottom text-wrap" href="javascript:void(0)"
                                            style="background: transparent;">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="rounded-circle d-flex justify-content-center align-items-center mt-1 shadow-sm"
                                                    style="width: 35px; height: 35px; background-color: var(--color-primary); color: var(--color-white); flex-shrink: 0;">
                                                    <i class="fa-solid fa-info" style="font-size: 0.8rem;"></i>
                                                </div>
                                                <div>
                                                    <strong
                                                        class="d-block text-dark small mb-1">{{ $notif->title ?? 'Pembaruan' }}</strong>
                                                    <span class="text-muted d-block"
                                                        style="font-size: 0.85rem; white-space: normal;">{{ $notif->message ?? 'Ada aktivitas baru.' }}</span>
                                                    <small class="d-block fw-bold mt-1"
                                                        style="color: var(--color-accent); font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center py-4">
                                        <i class="fa-regular fa-bell-slash fs-3 mb-2"
                                            style="color: var(--color-border);"></i>
                                        <p class="text-muted small mb-0">Belum ada aktivitas baru.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- DROPDOWN PROFIL USER --}}
                    <div class="dropdown">
                        <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold text-uppercase shadow-sm"
                            id="profileDropdown" data-bs-toggle="dropdown" data-bs-display="static"
                            aria-expanded="false"
                            style="width: 44px; height: 44px; background: var(--color-primary); color: var(--color-accent); cursor: pointer; border: 2px solid var(--color-accent); font-size: 1.1rem;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2"
                            aria-labelledby="profileDropdown" style="border-radius: 12px; min-width: 220px;">
                            <li class="px-3 py-2">
                                <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <small class="text-muted">{{ Auth::user()->email ?? 'admin@procurement.com' }}</small>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="{{ route('admin.settings') }}"
                                    class="dropdown-item fw-bold text-dark py-2 {{ request()->routeIs('admin.settings') ? 'text-warning' : 'text-dark' }}"
                                    style="border-radius: 8px;">
                                    <i class="fa-solid fa-gear me-2 text-secondary"></i> Pengaturan Sistem
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item fw-bold text-danger py-2"
                                        style="border-radius: 8px;">
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
                @yield('content')
            </div>
        </div>
    </div>

    {{-- KUMPULAN LOGIKA JAVASCRIPT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("sidebarToggle");
            const sidebarElement = document.getElementById("sidebarNav");
            const overlayElement = document.getElementById("sidebarOverlay");

            // 1. Fungsi Buka/Tutup Sidebar
            if (toggleBtn) {
                toggleBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    if (window.innerWidth <= 991.98) {
                        sidebarElement.classList.add("mobile-open");
                        overlayElement.classList.add("show");
                        document.body.style.overflow = "hidden";
                    } else {
                        sidebarElement.classList.toggle("toggled");
                    }
                });
            }

            // 2. Fungsi Tutup Sidebar saat Bagian Blur (Overlay) Diklik & Kembalikan Fokus
            if (overlayElement) {
                overlayElement.addEventListener("click", function(e) {
                    e.preventDefault();

                    // Tutup sidebar dan hilangkan efek blur
                    sidebarElement.classList.remove("mobile-open");
                    overlayElement.classList.remove("show");
                    document.body.style.overflow = "auto";

                    // Kembalikan fokus kursor/layar ke halaman (Perbaikan yang Anda minta)
                    const mainContent = document.querySelector(".main-content") || document.querySelector(
                        ".wrapper");
                    if (mainContent) {
                        mainContent.setAttribute("tabindex", "-1");
                        mainContent.focus();
                        mainContent.style.outline = "none";
                    }
                });
            }

            // 3. Reset tampilan otomatis jika orientasi layar diubah
            window.addEventListener("resize", function() {
                if (window.innerWidth > 991.98) {
                    sidebarElement.classList.remove("mobile-open");
                    overlayElement.classList.remove("show");
                    document.body.style.overflow = "auto";
                }
            });
        });
        // ==========================================
        // 2. LOGIKA TRIGGER SYSTEM EVENT INDEPENDEN (JANGAN DIHAPUS)
        // ==========================================
        window.triggerSystemEvent = function(title, message, iconType) {
            const isToastEnabled = localStorage.getItem('enable_admin_toasts') !== 'false';
            const isSystemNotifEnabled = localStorage.getItem('enable_system_notif') !== 'false';

            // Eksekusi Pop-up Toast jika aktif di pengaturan
            if (isToastEnabled === true) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: iconType,
                        title: title,
                        text: message,
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true
                    });
                }
            } else {
                console.warn('[Proculus System] Popup Toast dinonaktifkan dari pengaturan.');
            }

            // Eksekusi Penambahan Angka Lencana jika aktif di pengaturan
            if (isSystemNotifEnabled === true) {
                const badge = document.getElementById('notification-badge-count') || document.querySelector(
                    '#notifDropdown .badge');
                if (badge) {
                    let currentCount = parseInt(badge.innerText) || 0;
                    currentCount++;
                    badge.innerText = currentCount > 99 ? '99+' : currentCount;
                }
            } else {
                console.warn('[Proculus System] Log notifikasi dinonaktifkan dari pengaturan.');
            }
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.interaction-toast')
    @stack('scripts')
</body>

</html>
