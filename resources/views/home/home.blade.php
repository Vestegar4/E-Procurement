@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- HERO SECTION --}}
    <section class="landing-hero">
        <div class="container text-center">
            
            <span class="hero-badge">
                Modern E-Procurement Platform
            </span>

            <h1 class="hero-title">
                Digital Procurement Solution<br>for Modern Enterprise
            </h1>

            <p class="hero-description">
                Kelola proses tender, vendor management, bid monitoring, invoice, dan procurement perusahaan dalam satu platform terintegrasi.
            </p>

            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('login') }}" class="btn-primary-action">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn-outline-action">
                    Register Vendor
                </a>
            </div>

            {{-- Opsional: Jika Anda memiliki gambar mockup dashboard seperti di desain TaskGo, uncomment baris di bawah ini --}}
            {{-- <img src="{{ asset('images/dashboard-mockup.png') }}" alt="Dashboard Mockup" class="hero-mockup-img"> --}}

        </div>
    </section>

    {{-- FEATURES SECTION (Premium Benefits) --}}
    <section class="features-section">
        <div class="container">
            
            <div class="text-center">
                <h2 class="section-title">Unlock Premium Benefits</h2>
                <p class="section-subtitle">Semua kebutuhan procurement perusahaan dalam satu dashboard modern.</p>
            </div>

            <div class="feature-grid mt-5">
                <div class="landing-feature-card">
                    <h3>Tender Management</h3>
                    <p>Kelola tender procurement secara digital dan terstruktur dari awal hingga akhir.</p>
                </div>
                
                <div class="landing-feature-card">
                    <h3>Vendor Verification</h3>
                    <p>Sistem approval vendor yang efisien dengan validasi dokumen perusahaan otomatis.</p>
                </div>
                
                <div class="landing-feature-card">
                    <h3>Bid Monitoring</h3>
                    <p>Monitoring seluruh proses bidding, penawaran harga, dan evaluasi performa vendor.</p>
                </div>
                
                <div class="landing-feature-card">
                    <h3>Invoice Tracking</h3>
                    <p>Monitoring tagihan, invoice, dan purchase order secara realtime dengan riwayat jelas.</p>
                </div>
            </div>

        </div>
    </section>

    {{-- STATS SECTION (Why Teams Choose Us) --}}
    <section class="stats-section">
        <div class="container text-center">
            
            <h2 class="section-title" style="margin-bottom: 50px;">Why Enterprises Choose Us</h2>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">120+</div>
                    <div class="stat-label">Active Vendors<br>Tergabung di sistem</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">56</div>
                    <div class="stat-label">Open Tenders<br>Sedang berlangsung</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Procurement<br>Efficiency Impact</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Monitoring System<br>Selalu siap diakses</div>
                </div>
            </div>

        </div>
    </section>

    {{-- FLOW SECTION (Easy Steps) --}}
    <section style="padding: 80px 24px; background: #FFFFFF;">
        <div class="container">
            
            <div class="text-center mb-5">
                <h2 class="section-title">Get Started in 4 Easy Steps</h2>
                <p class="section-subtitle">Alur proses pengadaan barang dan jasa yang transparan.</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="landing-feature-card h-100" style="padding: 24px;">
                        <div style="width: 50px; height: 50px; background: #1A1A1A; color: #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 20px;">1</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 5px;">Vendor Register</h3>
                        <p style="font-size: 0.9rem;">Pendaftaran akun vendor baru.</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="landing-feature-card h-100" style="padding: 24px;">
                        <div style="width: 50px; height: 50px; background: #1A1A1A; color: #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 20px;">2</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 5px;">Admin Verify</h3>
                        <p style="font-size: 0.9rem;">Verifikasi kelayakan berkas.</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="landing-feature-card h-100" style="padding: 24px;">
                        <div style="width: 50px; height: 50px; background: #1A1A1A; color: #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 20px;">3</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 5px;">Join Tender</h3>
                        <p style="font-size: 0.9rem;">Ajukan penawaran dokumen.</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="landing-feature-card h-100" style="padding: 24px;">
                        <div style="width: 50px; height: 50px; background: #1A1A1A; color: #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; margin: 0 auto 20px;">4</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 5px;">Bid & Result</h3>
                        <p style="font-size: 0.9rem;">Pengumuman pemenang lelang.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="landing-footer py-5" style="background: #FAF9F6; border-top: 1px solid rgba(0,0,0,0.05);">
        <div class="container text-center">
            
            <h5 style="font-weight: 800; color: #1A1A1A; letter-spacing: -0.01em;">
                Proculus E-Procurement
            </h5>
            
            <p style="color: #71717A; max-width: 400px; margin: 10px auto 0;">
                Modern digital procurement platform for enterprise procurement management.
            </p>

        </div>
    </footer>

@endsection