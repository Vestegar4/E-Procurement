@extends('layouts.app')

@section('title', 'Welcome to Proculus')

@section('content')
    {{-- HERO SECTION UTAMA --}}
    <section class="landing-hero" style="position: relative; overflow: hidden;">
        <div style="position: absolute; top: -100px; left: -50px; width: 300px; height: 300px; background: rgba(217, 119, 6, 0.05); border-radius: 50%; z-index: 0;"></div>
        <div style="position: absolute; bottom: -150px; right: -50px; width: 400px; height: 400px; background: rgba(15, 23, 42, 0.03); border-radius: 50%; z-index: 0;"></div>

        <div class="container" style="position: relative; z-index: 1;">
            
            <span class="hero-badge shadow-sm">
                <i class="fa-solid fa-bolt me-2"></i> Modern E-Procurement Platform
            </span>

            <h1 class="hero-title mt-3">
                Digital Procurement Solution<br>for Modern Enterprise
            </h1>

            <p class="hero-description text-muted">
                Kelola proses tender, vendor management, bid monitoring, invoice, dan procurement perusahaan dalam satu platform terintegrasi dengan aksesibilitas tinggi.
            </p>

            <div class="d-flex justify-content-center gap-3 mt-5">
                <a href="{{ route('login') }}" class="btn-primary-action shadow-sm" style="min-width: 160px;">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn-amber shadow-sm" style="min-width: 160px;">
                    <i class="fa-solid fa-building me-2"></i> Register Vendor
                </a>
            </div>

        </div>
    </section>

    {{-- SECTION HOW IT WORKS (4 LANGKAH) --}}
    <section class="py-5" style="background-color: var(--color-white); border-top: 2px solid var(--color-border);">
        <div class="container py-4">
            
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: var(--color-text-main); letter-spacing: -0.02em;">Cara Kerja Proculus</h2>
                <p class="text-muted">Proses transparan dan efisien dari hulu ke hilir</p>
            </div>

            <div class="row g-4 text-center">
                
                {{-- Step 1 --}}
                <div class="col-md-3">
                    <div class="landing-feature-card h-100">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.5rem; font-weight: 800;">
                            1
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Vendor Register</h5>
                        <p class="text-muted small mb-0">Pendaftaran mandiri oleh rekanan vendor secara online.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="col-md-3">
                    <div class="landing-feature-card h-100">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.5rem; font-weight: 800;">
                            2
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Admin Verification</h5>
                        <p class="text-muted small mb-0">Verifikasi berkas dan legalitas oleh admin instansi.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="col-md-3">
                    <div class="landing-feature-card h-100">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.5rem; font-weight: 800;">
                            3
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Join Tender</h5>
                        <p class="text-muted small mb-0">Akses tender terbuka dan kirim proposal penawaran.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="col-md-3">
                    <div class="landing-feature-card h-100">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.5rem; font-weight: 800;">
                            4
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Bid & Result</h5>
                        <p class="text-muted small mb-0">Evaluasi sistematis dan pengumuman pemenang lelang.</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-4 text-center" style="background: var(--color-surface);">
        <div class="container">
            <h5 class="fw-bold" style="color: var(--color-text-main);">Proculus E-Procurement</h5>
            <p class="mb-0 text-muted small">&copy; 2026 Proculus Enterprise. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
@endsection