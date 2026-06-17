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
            background-color: var(--color-surface, #f8f9fa); /* Mencegah background putih silau */
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
    <nav class="navbar navbar-expand-lg glass-navbar shadow-sm py-3 sticky-top" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
        <div class="container">
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

            <div class="ms-auto d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('home') }}" class="nav-link fw-bold px-2" style="color: var(--color-text-muted); font-size: 0.95rem; transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)';" onmouseout="this.style.color='var(--color-text-muted)';">Home</a>
                    
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
                        Login
                    </a>
                    
                    <a href="{{ route('register') }}" class="btn px-4 py-2 shadow-sm" style="
                        background-color: var(--color-accent); 
                        color: var(--color-white); 
                        border: 2px solid var(--color-accent);
                        border-radius: 6px; 
                        font-weight: 700;
                        font-size: 0.95rem;
                        transition: all 0.2s ease;
                        text-decoration: none;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                    " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                        Register Vendor
                    </a>
                @else
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-medium text-muted small">Halo, <strong style="color: var(--color-text-main);">{{ Auth::user()->name }}</strong></span>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm px-3 py-2 text-white" style="background-color: var(--color-primary); border-radius: 6px; font-weight: 600; border: none;">
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
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                document.body.classList.remove('page-leaving');
            }
        });
    </script>
</body>
</html>