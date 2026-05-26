@extends('layouts.app')
@section('title', 'Login')

@section('content')
    <div class="container auth-container d-flex align-items-center justify-content-center">
        <div class="row w-100 shadow-lg auth-card overflow-hidden" style="max-width: 1000px; padding: 0;">

            {{-- LEFT SIDE --}}
            <div class="col-md-5 auth-side d-flex flex-column justify-content-center p-5 text-center" style="background: #1A1A1A; color: #FFFFFF;">
                <div class="logo-circle mb-4" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37;">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <h2 class="fw-bold" style="color: #D4AF37;">Hello Proculus</h2>
                <p class="mt-3" style="color: #A1A1AA;">
                    Sistem E-Procurement modern untuk manajemen tender dan vendor perusahaan.
                </p>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-md-7 bg-white p-5">
                <h2 class="auth-title mb-2">Login Account</h2>
                <p class="auth-subtitle mb-4">Masukkan email dan password untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control auth-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control auth-input @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary-action w-100 py-3" style="border-radius: 8px;">
                        Login
                    </button>

                    <div class="text-center mt-4">
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #D4AF37;">
                            Register
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection