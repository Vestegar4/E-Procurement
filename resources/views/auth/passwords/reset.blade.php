@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 2rem; padding-bottom: 2rem;">
        
        <div class="card border-0 shadow-lg overflow-hidden w-100" style="max-width: 1000px; border-radius: var(--radius-card, 16px);">
            <div class="row g-0">

                {{-- SISI KIRI (Branding & Logo - Identik) --}}
                <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-center" style="background: var(--color-primary); color: var(--color-white);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" 
                        style="width: 120px; 
                                height: 120px; 
                                aspect-ratio: 1/1; 
                                flex-shrink: 0; 
                                background-color: #fff1e1;
                                border: 1px solid #5c361ba8; 
                                box-shadow: 0 0 30px #f59f0baa;">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Proculus Logo" class="img-fluid" style="max-width: 70%; max-height: 70%; object-fit: contain;">
                    </div>
                    <h4 class="fw-bold text-uppercase tracking-wider mb-2" style="color: var(--color-accent-bright);">Proculus</h4>
                    <p class="small mb-0 opacity-75">Enterprise Procurement Solution</p>
                </div>

                {{-- SISI KANAN (Form Pembuatan Password Baru) --}}
                <div class="col-md-7 p-5 bg-white d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark mb-2">Reset Password</h3>
                        <p class="text-muted fw-medium mb-0">Silakan masukkan kata sandi baru Anda yang aman.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Input Email (Readonly agar aman & presisi) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small text-muted mb-2">Email Terdaftar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                                       class="form-control border-start-0 auth-input px-0 style-readonly" 
                                       style="background-color: #f8f9fa; color: #6c757d; font-weight: 600;">
                            </div>
                        </div>

                        {{-- Input Password Baru --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small text-muted mb-2">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" required autofocus
                                       class="form-control border-start-0 auth-input px-0 @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter...">
                                @error('password')
                                    <span class="invalid-feedback mt-2" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Input Konfirmasi Password --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small text-muted mb-2">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-circle-check"></i></span>
                                <input type="password" name="password_confirmation" required
                                       class="form-control border-start-0 auth-input px-0" 
                                       placeholder="Ulangi password baru...">
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn w-100 py-3 fs-5 mb-4 shadow-sm" style="
                            background-color: var(--color-accent); 
                            color: var(--color-white); 
                            border: 2px solid var(--color-accent);
                            border-radius: 8px; 
                            font-weight: 700;
                            transition: all 0.25s ease;
                        " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                            Simpan Password Baru <i class="fa-solid fa-shield-check ms-2"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection