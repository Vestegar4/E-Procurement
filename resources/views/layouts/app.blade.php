<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Proculus E-Procurement - @yield('title', 'Welcome')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        /* 1. Sembunyikan body di awal untuk mencegah flash, lalu jalankan animasi masuk */
        body {
            opacity: 0;
            animation: smoothEnter 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            background-color: var(--color-surface, #f8f9fa);
            /* Mencegah background putih silau */
        }

        /* Animasi Masuk (Fade in & Slide up halus) */
        @keyframes smoothEnter {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 2. Kelas untuk Animasi Keluar (Dipicu otomatis oleh JavaScript saat klik link/tombol) */
        body.page-leaving {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.35s ease-out, transform 0.35s ease-out;
        }
    </style>
</head>

<body>
    {{-- NAVBAR TOP NAVIGATION HEADER --}}
    {{-- NAVBAR TOP NAVIGATION HEADER (KODE UTUH BAWAAN ZIP + MODIFIKASI TOMBOL) --}}
    <nav class="navbar navbar-expand-lg glass-navbar shadow-sm py-3 sticky-top" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
        <div class="container">

            {{-- BAGIAN KIRI: LOGO & NAMA WEB (DIKEMBALIKAN 100% SESUAI ASLI ZIP) --}}
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ url('/') }}">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                    style="width: 40px; 
                        height: 40px; 
                        aspect-ratio: 1/1; 
                        flex-shrink: 0; 
                        background-color: #fff1e1; 
                        border: 1px solid #5c361b76; 
                        box-shadow: 0 0 15px #f59f0bab; 
                        transition: transform 0.3s ease;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Proculus Logo" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                Proculus
            </a>

            {{-- BAGIAN KANAN: MENU NAVIGASI (Hapus Home, Modifikasi Kontak Admin & Register) --}}
            <div class="ms-auto d-flex align-items-center gap-3">
                @guest
                {{-- 1. TOMBOL LOGIN / MASUK PORTAL --}}
                @if(!request()->routeIs('login'))
                <a href="{{ route('login') }}" class="btn px-4 py-2" style="
                            border: 2px solid var(--color-primary); 
                            color: var(--color-primary); 
                            background-color: transparent;
                            border-radius: 6px; 
                            font-weight: 700;
                            font-size: 0.95rem;
                            transition: all 0.2s ease;
                            text-decoration: none;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                        " onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-white)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-primary)';">
                    Login <i class="fa-solid fa-right-to-bracket ms-2"></i>
                </a>
                @endif

                {{-- 2. TOMBOL KONTAK ADMIN (Muncul di Landing Page & Halaman Register) --}}
                @if(request()->routeIs('home') || request()->routeIs('register'))
                <a href="{{ route('contact.admin') }}" class="btn px-4 py-2 shadow-sm" style="
                            background-color: var(--color-primary); 
                            color: var(--color-white); 
                            border: 2px solid var(--color-primary);
                            border-radius: 6px; 
                            font-weight: 700;
                            font-size: 0.95rem;
                            transition: all 0.2s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            text-decoration: none;
                        " onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">
                    <i class="fa-solid fa-envelope-open-text me-2" style="color: var(--color-accent-bright);"></i> Kontak Admin
                </a>
                @endif

                @else
                {{-- TAMPILAN JIKA USER SUDAH LOGIN (Sama persis dengan kode bawaan zip) --}}
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-medium text-muted small">Halo, <strong style="color: var(--color-text-main);">{{ Auth::user()->name }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm px-3 py-2 text-white shadow-sm" style="background-color: var(--color-primary); border-radius: 6px; font-weight: 600; border: none; transition: 0.2s;" onmouseover="this.style.backgroundColor='#1e293b';" onmouseout="this.style.backgroundColor='var(--color-primary)';">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
                @endguest
            </div>

        </div>
    </nav>

    {{-- KONTEN UTAMA HALAMAN --}}
    @yield('content')

    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // 1. Tangkap semua klik pada link navigasi
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Biarkan berfungsi normal jika user membuka di tab baru (Ctrl+Click / Cmd+Click)
                    if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

                    e.preventDefault();
                    const targetUrl = this.href;

                    // Tambahkan kelas animasi keluar (Fade-out)
                    document.body.classList.add('page-leaving');

                    // Tunggu animasi CSS selesai (350ms), baru eksekusi perpindahan halaman
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 350);
                });
            });

            // 2. Tangkap juga saat tombol Submit Form ditekan (seperti klik Login / Daftar)
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    document.body.classList.add('page-leaving');
                });
            });
        });

        // 3. Fix untuk Safari / iOS (Back-Forward Cache Bug)
        // Mencegah halaman tetap transparan saat user menekan tombol "Back" di browser
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                document.body.classList.remove('page-leaving');
            }
        });
    </script>
</body>

</html>