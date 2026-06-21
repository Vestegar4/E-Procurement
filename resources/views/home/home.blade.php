@extends('layouts.app')

@section('title', 'Welcome to Proculus')

@section('content')
{{-- HERO SECTION UTAMA --}}
<section class="landing-hero" style="position: relative; overflow: hidden; padding: 100px 0; background-color: var(--color-surface);">
    <div style="position: absolute; top: -100px; left: -50px; width: 300px; height: 300px; background: rgba(217, 119, 6, 0.05); border-radius: 50%; z-index: 0;"></div>
    <div style="position: absolute; bottom: -150px; right: -50px; width: 400px; height: 400px; background: rgba(15, 23, 42, 0.03); border-radius: 50%; z-index: 0;"></div>

    <div class="container text-center" style="position: relative; z-index: 1;">

        <span class="badge rounded-pill shadow-sm mb-3 px-4 py-2" style="background-color: var(--color-white); color: var(--color-accent); border: 1px solid var(--color-border); font-size: 0.9rem; font-weight: 700;">
            <i class="fa-solid fa-bolt me-2"></i> Modern E-Procurement Platform
        </span>

        <h1 class="display-4 fw-bold mt-3 mb-4" style="color: var(--color-text-main); letter-spacing: -0.02em; line-height: 1.2;">
            Digital Procurement Solution<br>for Modern Enterprise
        </h1>

        <p class="lead text-muted mx-auto mb-5" style="max-width: 750px; font-weight: 500; line-height: 1.7;">
            Kelola proses tender, vendor management, bid monitoring, invoice, dan procurement perusahaan dalam satu platform terintegrasi dengan aksesibilitas tinggi.
        </p>

        {{-- PERBAIKAN TOMBOL: SEKARANG TERLIHAT NYATA DAN SANGAT JELAS SEBAGAI BUTTON --}}
        <!-- <div class="d-flex justify-content-center gap-3 flex-wrap">

            {{-- Tombol 1: Login Portal (Gaya Outline Navy yang Tegas & Bersih) --}}
            <a href="{{ route('login') }}" class="btn px-4 py-3 shadow-sm" style="
                    border: 2px solid var(--color-primary); 
                    color: var(--color-primary); 
                    background-color: var(--color-white);
                    border-radius: 8px; 
                    font-weight: 700;
                    font-size: 1.05rem;
                    min-width: 180px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    text-decoration: none;
                    transition: all 0.25s ease;
                " onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='var(--color-white)';" onmouseout="this.style.backgroundColor='var(--color-white)'; this.style.color='var(--color-primary)';">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Login
            </a> -->

            {{-- Tombol 2: Daftar Sebagai Vendor (Gaya Solid Amber - Tombol Utama Paling Mencolok) --}}
            <a href="https://play.google.com/store/apps/details?id=proculus.app.dartd" target="_blank" class="btn px-4 py-3 shadow" style="
                    background-color: var(--color-accent); 
                    color: var(--color-white); 
                    border: 2px solid var(--color-accent);
                    border-radius: 8px; 
                    font-weight: 700;
                    font-size: 1.05rem;
                    min-width: 240px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    text-decoration: none;
                    transition: all 0.25s ease;
                " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                Android App Download <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>

        </div>

    </div>
</section>

{{-- FEATURES SECTION --}}
<section class="py-5" style="background-color: var(--color-white);">
    <div class="container py-4">

        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--color-text-main);">Alur Pengadaan Proculus</h2>
            <p class="text-muted fw-medium">Proses lelang yang transparan, aman, dan mudah dipantau.</p>
        </div>

        <div class="row g-4 text-center">
            {{-- Step 1 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: var(--radius-card, 16px); background: var(--color-surface); border: 1px solid var(--color-border);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.3rem; font-weight: 800;">1</div>
                    <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">Registrasi</h5>
                    <p class="text-muted small mb-0">Daftarkan profil perusahaan Anda secara resmi di platform kami.</p>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: var(--radius-card, 16px); background: var(--color-surface); border: 1px solid var(--color-border);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.3rem; font-weight: 800;">2</div>
                    <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">Verifikasi Admin</h5>
                    <p class="text-muted small mb-0">Berkas legalitas perusahaan ditinjau langsung oleh tim pengadaan.</p>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: var(--radius-card, 16px); background: var(--color-surface); border: 1px solid var(--color-border);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.3rem; font-weight: 800;">3</div>
                    <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">Join Tender</h5>
                    <p class="text-muted small mb-0">Akses tender terbuka dan kirim proposal penawaran harga terbaik.</p>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: var(--radius-card, 16px); background: var(--color-surface); border: 1px solid var(--color-border);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 60px; height: 60px; background: var(--color-primary); color: var(--color-accent-bright); font-size: 1.3rem; font-weight: 800;">4</div>
                    <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">Bid & Result</h5>
                    <p class="text-muted small mb-0">Evaluasi sistematis yang transparan dan pengumuman pemenang lelang.</p>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- FOOTER --}}
<footer class="py-4 text-center border-top" style="background: var(--color-surface); border-color: var(--color-border) !important; position: relative;">
    <div class="container">
        <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">Proculus E-Procurement</h5>
        <p class="mb-0 text-muted small">&copy; 2026 Proculus Enterprise. Hak Cipta Dilindungi.</p>
        
        {{-- AKSES ADMIN TERSEMBUNYI --}}
        <div class="mt-2">
            <a href="{{ route('login') }}" 
               class="text-decoration-none" 
               style="font-size: 0.65rem; color: var(--color-text-main); opacity: 0.1; transition: opacity 0.3s ease; letter-spacing: 1px; text-transform: uppercase;"
               onmouseover="this.style.opacity='0.6'" 
               onmouseout="this.style.opacity='0.1'">
               Admin Access
            </a>
        </div>
    </div>
</footer>
@endsection