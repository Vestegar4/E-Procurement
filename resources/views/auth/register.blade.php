@extends('layouts.app')

@section('title', 'Vendor Registration')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-11">

                <div class="row shadow-lg rounded-4 overflow-hidden bg-white">

                    {{-- LEFT SIDE --}}
                    <div class="col-lg-4 auth-side d-flex flex-column justify-content-center p-5">

                        <div class="mb-4">
                            <div class="logo-circle">
                                <i class="fa-solid fa-building"></i>
                            </div>
                        </div>

                        <h2 class="fw-bold mb-3">
                            Proculus E-Procurement
                        </h2>

                        <p class="text-light mb-4" style="line-height: 1.8;">
                            Bergabung sebagai vendor resmi untuk mengikuti proses tender,
                            pengadaan, dan kerjasama procurement perusahaan.
                        </p>

                        <div class="border-top border-secondary pt-4">
                            <h6 class="fw-semibold mb-3">
                                Informasi Registrasi
                            </h6>

                            <ul class="list-unstyled small text-light">

                                <li class="mb-3">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    Data perusahaan akan diverifikasi admin.
                                </li>

                                <li class="mb-3">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    Akun vendor harus disetujui sebelum dapat login.
                                </li>

                                <li>
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    Pastikan data perusahaan valid dan aktif.
                                </li>

                            </ul>
                        </div>

                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="col-lg-8 p-5">

                        <div class="mb-4">

                            <h2 class="fw-bold text-dark">
                                Vendor Registration
                            </h2>

                            <p class="text-muted">
                                Lengkapi informasi akun dan data perusahaan untuk mengajukan registrasi vendor.
                            </p>

                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- ACCOUNT INFORMATION --}}
                            <div class="mb-4">

                                <h5 class="fw-bold text-dark mb-3">
                                    Informasi Akun
                                </h5>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Nama PIC
                                        </label>

                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>

                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Email
                                        </label>

                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="example@company.com" required>

                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Password
                                        </label>

                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter" required>

                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Konfirmasi Password
                                        </label>

                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Ulangi password" required>
                                    </div>

                                </div>

                            </div>

                            <hr class="my-4">

                            {{-- COMPANY INFORMATION --}}
                            <div class="mb-4">

                                <h5 class="fw-bold text-dark mb-3">
                                    Informasi Perusahaan
                                </h5>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Nama Perusahaan
                                        </label>

                                        <input type="text" name="company_name"
                                            class="form-control @error('company_name') is-invalid @enderror"
                                            value="{{ old('company_name') }}" placeholder="PT / CV / Firma" required>

                                        @error('company_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            NPWP
                                        </label>

                                        <input type="text" name="npwp"
                                            class="form-control @error('npwp') is-invalid @enderror"
                                            value="{{ old('npwp') }}" placeholder="Nomor NPWP perusahaan">

                                        @error('npwp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Nomor Telepon
                                        </label>

                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>

                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">
                                            Alamat Perusahaan
                                        </label>

                                        <textarea name="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Masukkan alamat lengkap perusahaan" required>{{ old('address') }}</textarea>

                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            {{-- AGREEMENT --}}
                            <div class="form-check mb-4">

                                <input class="form-check-input" type="checkbox" id="agreement" required>

                                <label class="form-check-label text-muted" for="agreement">
                                    Saya menyetujui syarat dan ketentuan registrasi vendor Proculus.
                                </label>

                            </div>

                            {{-- ALERT --}}
                            <div class="alert alert-warning border-0 mb-4">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Setelah registrasi, akun vendor akan melalui proses verifikasi admin procurement.
                            </div>

                            {{-- BUTTON --}}
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-semibold">
                                Register Vendor
                            </button>

                            {{-- LOGIN --}}
                            <div class="text-center mt-4">

                                <span class="text-muted">
                                    Sudah memiliki akun?
                                </span>

                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Login di sini
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
