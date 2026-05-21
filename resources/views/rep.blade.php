@extends('home')
@section('title', 'Laporan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Pusat Unduhan Laporan</h4>
    <p class="text-muted mb-0">Export data mentah tabel komparatif sistem</p>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card card-custom p-4 text-center border-0 shadow-sm">
            <i class="fa-solid fa-file-csv fa-3x mb-3" style="color: #fe81d4;"></i>
            <h5 class="fw-bold">Laporan Rekapitulasi Pengadaan</h5>
            <p class="text-muted small">Ekstrak seluruh daftar riwayat tender yang pernah dibuat</p>
            <a href="/reports/download/procurement" class="btn btn-light border mt-2 w-100 fw-semibold">
                <i class="fa-solid fa-download me-2"></i>Download Data Pengadaan
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom p-4 text-center border-0 shadow-sm">
            <i class="fa-solid fa-file-invoice-dollar fa-3x mb-3 text-success"></i>
            <h5 class="fw-bold">Laporan Keuangan Vendor</h5>
            <p class="text-muted small">Unduh basis rekap profil mitra kerja berstatus terverifikasi</p>
            <a href="/reports/download/finance" class="btn btn-light border mt-2 w-100 fw-semibold">
                <i class="fa-solid fa-download me-2"></i>Download Data Mitra
            </a>
        </div>
    </div>
</div>
@endsection