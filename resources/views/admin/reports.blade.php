@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pusat Unduhan Laporan</h4>
    <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Export data mentah tabel komparatif sistem</p>
</div>

<div class="row g-4">
    {{-- Card 1: Rekapitulasi --}}
    <div class="col-md-6">
        <div class="card card-custom p-5 text-center border-0 shadow-sm d-flex flex-column align-items-center" style="background: var(--color-white); border-radius: var(--radius-card); height: 100%;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: var(--color-surface); border: 2px solid var(--color-border);">
                <i class="fa-solid fa-file-csv fa-2xl" style="color: var(--color-primary);"></i>
            </div>
            <h5 class="fw-bold" style="color: var(--color-text-main);">Laporan Rekapitulasi Pengadaan</h5>
            <p class="text-muted mb-4 px-3">Ekstrak seluruh daftar riwayat tender yang pernah dibuat ke dalam format CSV.</p>
            <a href="{{ route('admin.reports.download', 'procurement') }}" class="btn btn-outline-action w-100 mt-auto">
                <i class="fa-solid fa-download me-2"></i> Download Data
            </a>
        </div>
    </div>
    
    {{-- Card 2: Keuangan Vendor --}}
    <div class="col-md-6">
        <div class="card card-custom p-5 text-center border-0 shadow-sm d-flex flex-column align-items-center" style="background: var(--color-white); border-radius: var(--radius-card); height: 100%;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: var(--color-primary);">
                <i class="fa-solid fa-file-invoice-dollar fa-2xl" style="color: var(--color-accent);"></i>
            </div>
            <h5 class="fw-bold" style="color: var(--color-text-main);">Laporan Keuangan Vendor</h5>
            <p class="text-muted mb-4 px-3">Unduh basis rekap profil mitra kerja berstatus terverifikasi dan riwayat PO.</p>
            <a href="{{ route('admin.reports.download', 'vendor') }}" class="btn btn-primary-action w-100 mt-auto">
                <i class="fa-solid fa-download me-2" style="color: var(--color-accent);"></i> Download Data
            </a>
        </div>
    </div>
</div>
@endsection