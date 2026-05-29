@extends('layouts.app')
@section('title', 'Login Portal')

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh; padding-top: 2rem; padding-bottom: 2rem;">
        
        <div class="card border-0 shadow-lg overflow-hidden w-100" style="max-width: 1000px; border-radius: var(--radius-card, 16px);">
            <div class="row g-0">
                
                {{-- SISI KIRI (Branding & Ilustrasi Teks) --}}
                <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-center" style="background: var(--color-primary); color: var(--color-white);">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: rgba(217, 119, 6, 0.15); color: var(--color-accent-bright);">
                        <i class="fa-solid fa-cube fa-2xl"></i>
                    </div>
                    <h2 class="fw-bold mb-3" style="color: var(--color-accent-bright); letter-spacing: -0.02em;">Welcome Back</h2>
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
                            <label class="form-label fw-bold text-uppercase small mb-1" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Password</label>
                            
                            {{-- Input Password (Otomatis menjadi merah 'is-invalid' jika login gagal) --}}
                            <input type="password" name="password" class="form-control auth-input form-control-lg @error('password') is-invalid @enderror @if($errors->has('email') && (str_contains(strtolower($errors->first('email')), 'records') || str_contains(strtolower($errors->first('email')), 'match') || str_contains(strtolower($errors->first('email')), 'kredensial'))) is-invalid @endif" required placeholder="••••••••">
                            
                            {{-- PERBAIKAN: Menampilkan "Password anda salah" di bawah form password saat login gagal --}}
                            @if($errors->has('password') || ($errors->has('email') && (str_contains(strtolower($errors->first('email')), 'records') || str_contains(strtolower($errors->first('email')), 'match') || str_contains(strtolower($errors->first('email')), 'kredensial'))))
                                <div class="text-danger small fw-bold mt-2" style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-circle-xmark me-1"></i> email atau password anda salah
                                </div>
                            @endif

                            {{-- Posisi Lupa Password tetap di bawah form password --}}
                            @if (Route::has('password.request'))
                                <div class="mt-2 text-end">
                                    <a href="{{ route('password.request') }}" class="small fw-bold text-decoration-none" style="color: var(--color-accent); font-size: 0.85rem;">Lupa Password?</a>
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
                            Secure Login <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                        </button>

                        <div class="text-center">
                            <span class="text-muted fw-medium">Ingin bergabung sebagai mitra vendor?</span>
                            <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1" style="color: var(--color-accent);">
                                Daftar Sekarang
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection