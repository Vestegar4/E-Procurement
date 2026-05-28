@extends('layouts.app')
@section('title', 'Vendor Registration')

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 2rem; padding-bottom: 2rem;">
        
        <div class="card border-0 shadow-lg overflow-hidden w-100" style="max-width: 1000px; border-radius: var(--radius-card, 16px);">
            <div class="row g-0">

                {{-- SISI KIRI (Branding & Syarat) --}}
                <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-center" style="background: var(--color-primary); color: var(--color-white);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: rgba(217, 119, 6, 0.15); color: var(--color-accent-bright);">
                        <i class="fa-solid fa-handshake fa-2xl"></i>
                    </div>
                    <h3 class="fw-bold mb-3" style="color: var(--color-accent-bright); letter-spacing: -0.02em;">Mitra Pengadaan</h3>
                    <p class="mb-4" style="color: var(--color-border); font-size: 0.95rem; line-height: 1.6;">
                        Bergabung sebagai vendor resmi untuk mengikuti proses tender, pengadaan, dan kerjasama procurement perusahaan.
                    </p>
                    
                    <div class="text-start border-top pt-4 mt-auto" style="border-color: rgba(255,255,255,0.1) !important;">
                        <h6 class="fw-bold mb-3 text-uppercase small" style="color: var(--color-border); letter-spacing: 0.05em;">Tahapan Verifikasi</h6>
                        <ul class="list-unstyled small" style="color: #94a3b8; line-height: 1.8;">
                            <li><i class="fa-solid fa-circle-check me-2" style="color: var(--color-accent-bright);"></i> Isi Form Pendaftaran</li>
                            <li><i class="fa-solid fa-circle-check me-2" style="color: var(--color-accent-bright);"></i> Lolos Peninjauan Panitia</li>
                            <li><i class="fa-solid fa-circle-check me-2" style="color: var(--color-accent-bright);"></i> Mulai Mengikuti Lelang</li>
                        </ul>
                    </div>
                </div>

                {{-- SISI KANAN (Formulir Registrasi) --}}
                <div class="col-md-7 p-4 p-md-5" style="background: var(--color-white);">
                    <div class="mb-4">
                        <h3 class="fw-bold mb-1" style="color: var(--color-text-main);">Registrasi Akun Vendor</h3>
                        <p class="text-muted fw-medium">Lengkapi form berikut untuk membuat akun awal.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Nama Lengkap / Perwakilan</label>
                            <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Cth: Budi Santoso">
                            @error('name')
                                <span class="invalid-feedback fw-bold" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Email Bisnis</label>
                            <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="kontak@perusahaan.com">
                            @error('email')
                                <span class="invalid-feedback fw-bold" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Password</label>
                                <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required placeholder="Min. 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback fw-bold" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Ulangi Password</label>
                                <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" required placeholder="Konfirmasi password">
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input mt-1" type="checkbox" name="agreement" id="agreement" required style="cursor: pointer; transform: scale(1.1);">
                            <label class="form-check-label text-muted small ms-2" for="agreement" style="cursor: pointer; line-height: 1.4;">
                                Saya menyatakan bahwa data yang diberikan adalah benar dan menyetujui syarat serta ketentuan sistem <em>e-procurement</em>.
                            </label>
                        </div>

                        {{-- TOMBOL DAFTAR: DISESUAIKAN DENGAN LANDING PAGE (SOLID AMBER + HOVER EFFECT) --}}
                        <button type="submit" class="btn w-100 py-3 fs-5 mb-4 shadow-sm" style="
                            background-color: var(--color-accent); 
                            color: var(--color-white); 
                            border: 2px solid var(--color-accent);
                            border-radius: 8px; 
                            font-weight: 700;
                            transition: all 0.25s ease;
                        " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                            Daftarkan Perusahaan <i class="fa-solid fa-user-check ms-2"></i>
                        </button>

                        <div class="text-center">
                            <span class="text-muted fw-medium">Sudah memiliki akun terverifikasi?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1" style="color: var(--color-accent);">
                                Login di sini
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection