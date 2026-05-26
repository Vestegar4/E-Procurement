@extends('layouts.vendor')

@section('title', 'Settings')

@section('content')

    <div class="container-fluid p-0">

        {{-- HEADER --}}
        <div class="mb-4">

            <h3 class="fw-bold mb-1">
                Pengaturan Vendor
            </h3>

            <p class="text-muted mb-0">
                Kelola profil perusahaan dan keamanan akun vendor.
            </p>

        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- VALIDATION ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        <div class="row g-4">

            {{-- PROFILE --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            Profil Perusahaan
                        </h5>

                        <form method="POST" action="{{ route('vendor.settings.profile.update') }}">

                            @csrf
                            @method('PUT')

                            <div class="row">

                                {{-- PIC NAME --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Nama PIC
                                    </label>

                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $vendor->name) }}" required>

                                </div>

                                {{-- COMPANY NAME --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label fw-semibold">
                                        Nama Perusahaan
                                    </label>

                                    <input type="text" name="company_name" class="form-control"
                                        value="{{ old('company_name', $vendor->company_name) }}" required>

                                </div>

                            </div>

                            {{-- EMAIL --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Email
                                </label>

                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>

                            </div>

                            {{-- PHONE --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Nomor Telepon
                                </label>

                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $vendor->phone) }}" required>

                            </div>

                            {{-- ADDRESS --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Alamat Perusahaan
                                </label>

                                <textarea name="address" rows="4" class="form-control" required>{{ old('address', $vendor->address) }}</textarea>

                            </div>

                            {{-- NPWP --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    NPWP
                                </label>

                                <input type="text" name="npwp" class="form-control"
                                    value="{{ old('npwp', $vendor->npwp) }}">

                            </div>

                            <button type="submit" class="btn btn-primary px-4">

                                Simpan Perubahan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- STATUS CARD --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Status Vendor
                        </h5>

                        @if ($vendor->status == 'approved')
                            <span class="badge bg-success px-3 py-2">
                                Approved
                            </span>
                        @elseif($vendor->status == 'pending')
                            <span class="badge bg-warning text-dark px-3 py-2">
                                Pending Review
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2">
                                Rejected
                            </span>
                        @endif

                        <p class="text-muted small mt-3 mb-0">
                            Status vendor menentukan akses terhadap tender dan proses bidding.
                        </p>

                    </div>

                </div>

                {{-- PASSWORD CARD --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            Update Password
                        </h5>

                        <form method="POST" action="{{ route('vendor.settings.password.update') }}">

                            @csrf
                            @method('PUT')

                            {{-- CURRENT PASSWORD --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Password Lama
                                </label>

                                <input type="password" name="current_password" class="form-control" required>

                            </div>

                            {{-- NEW PASSWORD --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Password Baru
                                </label>

                                <input type="password" name="new_password" class="form-control" required>

                            </div>

                            {{-- CONFIRM --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Konfirmasi Password
                                </label>

                                <input type="password" name="new_password_confirmation" class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-dark w-100">

                                Update Password

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
