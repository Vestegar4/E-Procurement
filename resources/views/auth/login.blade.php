@extends('layouts.app')
@section('title', 'Login Portal')

@push('styles')
<style>
    /* Menyembunyikan ikon mata bawaan Edge/Chrome */
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 2rem; padding-bottom: 2rem;">

    <div class="card border-0 shadow-lg overflow-hidden w-100" style="max-width: 1000px; border-radius: var(--radius-card, 16px);">
        <div class="row g-0">

            {{-- SISI KIRI (Branding & Ilustrasi Teks) --}}
            <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-center" style="background: var(--color-primary); color: var(--color-white);">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4"
                    style="width: 120px; 
                                height: 120px; 
                                aspect-ratio: 1/1; 
                                flex-shrink: 0; 
                                background-color: #fff1e1; 
                                border: 1px solid #5c361ba8; 
                                box-shadow: 0 0 30px #f59f0baa; 
                                transition: transform 0.3s ease;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Proculus Logo" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>

                <h2 class="fw-bold mb-3" style="color: var(--color-accent-bright); letter-spacing: -0.02em;">Selamat Datang</h2>
                <p class="mb-0" style="color: var(--color-border); font-size: 0.95rem; line-height: 1.6;">
                    Sistem E-Procurement modern untuk manajemen tender internal dan kolaborasi vendor perusahaan.
                </p>
            </div>

            {{-- SISI KANAN (Formulir Login) --}}
            <div class="col-md-7 p-4 p-md-5" style="background: var(--color-white);">
                <div class="mb-4">
                    <h3 class="fw-bold mb-1" style="color: var(--color-text-main);">Login Account</h3>
                    <p class="text-muted fw-medium">Masukkan kredensial Anda untuk masuk ke sistem.</p>
                </div>

                {{-- Pesan Error Global / Flash Session --}}
                @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm p-3 mb-4" style="background-color: var(--color-danger-bg); color: var(--color-danger-text); border-left: 4px solid var(--color-danger-border) !important;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- KOLOM EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Email Address</label>

                        {{-- Input Email --}}
                        <input type="email" name="email" class="form-control auth-input form-control-lg @error('email') @if(!str_contains(strtolower($message), 'records') && !str_contains(strtolower($message), 'match')) is-invalid @endif @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">

                        {{-- Error khusus Email (hanya muncul jika email kosong / format salah, BUKAN karena gagal login) --}}
                        @error('email')
                        @if(!str_contains(strtolower($message), 'records') && !str_contains(strtolower($message), 'match') && !str_contains(strtolower($message), 'kredensial'))
                        <div class="text-danger small fw-bold mt-2" style="font-size: 0.85rem;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> format email tidak valid
                        </div>
                        @endif
                        @enderror
                    </div>

                    {{-- KOLOM PASSWORD --}}
                    <div class="mb-4">
                        <label for="loginPassword" class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            {{-- Tambahkan border-end-0 di input agar menyatu dengan ikon mata --}}
                            <input type="password" name="password" id="loginPassword" class="form-control border-start-0 border-end-0 auth-input px-0" placeholder="Masukkan kata sandi..." required>

                            {{-- TOMBOL IKON MATA --}}
                            <span class="input-group-text bg-white border-start-0 text-muted" id="togglePasswordBtn" style="cursor: pointer;">
                                <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>

                        {{-- PESAN ERROR --}}
                        @if ($errors->has('password') || ($errors->has('email') && (str_contains(strtolower($errors->first('email')), 'records') || str_contains(strtolower($errors->first('email')), 'match') || str_contains(strtolower($errors->first('email')), 'kredensial'))))
                        <div class="text-danger small fw-bold mt-2" style="font-size: 0.85rem;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> email atau password anda salah
                        </div>
                        @endif
                    </div>

                    {{-- TOMBOL SUBMIT SECURE LOGIN --}}
                    <button type="submit" class="btn w-100 py-3 fs-5 mb-4 shadow-sm mt-3" style="
                            background-color: var(--color-accent); 
                            color: var(--color-white); 
                            border: 2px solid var(--color-accent);
                            border-radius: 8px; 
                            font-weight: 700;
                            transition: all 0.25s ease;
                        " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                        Login <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById('loginPassword');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput && togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function() {
                // Cek status saat ini
                const isPassword = passwordInput.type === 'password';

                // Ubah tipe input
                passwordInput.type = isPassword ? 'text' : 'password';

                // Ubah ikon mata FontAwesome
                if (isPassword) {
                    togglePasswordIcon.classList.remove('fa-eye');
                    togglePasswordIcon.classList.add('fa-eye-slash');
                } else {
                    togglePasswordIcon.classList.remove('fa-eye-slash');
                    togglePasswordIcon.classList.add('fa-eye');
                }
            });
        }
    });
</script>
@endpush
@endsection