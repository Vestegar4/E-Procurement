@extends('layouts.vendor')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Ringkasan Vendor</h4>
            <p class="text-muted mb-0">Pantau status akun, pengadaan, dan dokumen kamu di sini.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card card-custom p-4">
                    <p class="text-muted mb-1 fw-semibold">Status Akun</p>
                    <h5 class="fw-bold m-0">{{ ucfirst(Auth::user()->role ?? 'vendor') }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-4">
                    <p class="text-muted mb-1 fw-semibold">Pengadaan Diikuti</p>
                    <h5 class="fw-bold m-0">0</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-4">
                    <p class="text-muted mb-1 fw-semibold">Dokumen Terkirim</p>
                    <h5 class="fw-bold m-0">0</h5>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2">Langkah Selanjutnya</h6>
                <p class="text-muted mb-0">Gunakan menu di kiri untuk mengisi dokumen, melihat pengadaan, dan mengirim
                    penawaran.</p>
            </div>
        </div>
    </div>
@endsection
