@extends('layouts.admin')
@section('title', 'Pusat Laporan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pusat Unduhan Laporan</h4>
    <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Export data mentah tabel komparatif dan pantau statistik keuangan</p>
</div>

{{-- SECTION 1: KARTU UNDUHAN LAPORAN --}}
<div class="row g-4 mb-4">
    {{-- Card 1: Rekapitulasi --}}
    <div class="col-md-6">
        <div class="card card-custom p-5 text-center border-0 shadow-sm d-flex flex-column align-items-center" style="background: var(--color-white); border-radius: var(--radius-card); height: 100%;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: var(--color-surface); border: 2px solid var(--color-border);">
                <i class="fa-solid fa-file-csv fa-2xl" style="color: var(--color-primary);"></i>
            </div>
            <h5 class="fw-bold" style="color: var(--color-text-main);">Laporan Rekapitulasi Pengadaan</h5>
            <p class="text-muted mb-4 px-3">Ekstrak seluruh daftar riwayat tender yang pernah dibuat ke dalam format CSV.</p>
            <a href="{{ route('admin.reports.download', 'procurement') }}" class="btn btn-outline-action w-100 mt-auto">
                <i class="fa-solid fa-download me-2"></i> Download Data CSV
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
            <p class="text-muted mb-4 px-3">Unduh basis rekap profil mitra kerja berstatus terverifikasi dan akumulasi nilai PO.</p>
            <a href="{{ route('admin.reports.download', 'vendor') }}" class="btn btn-primary-action w-100 mt-auto">
                <i class="fa-solid fa-download me-2"></i> Download Ringkasan Finansial
            </a>
        </div>
    </div>
</div>

{{-- SECTION 2: GRAFIK LAPORAN KEUANGAN BARU --}}
<div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
    <div class="card-header border-bottom pt-4 pb-3" style="background-color: var(--color-surface);">
        <h6 class="fw-bold mb-0" style="color: var(--color-text-main);">
            <i class="fa-solid fa-chart-line me-2" style="color: var(--color-accent);"></i>Grafik Realisasi Anggaran Purchase Order (PO)
        </h6>
    </div>
    <div class="card-body p-4">
        {{-- Tempat grafik dirender --}}
        <div style="position: relative; height:320px; width:100%">
            <canvas id="financialReportChart"></canvas>
        </div>
    </div>
</div>

{{-- Elemen Tersembunyi untuk Oper Data Safe dari Blade ke JS --}}
<div id="chart-data-bridge"
    data-labels="{{ json_encode($chartLabels) }}"
    data-values="{{ json_encode($chartValues) }}"
    style="display: none;">
</div>
@endsection

@push('scripts')
{{-- Load Library Chart.js Melalui CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Ambil data dari jembatan HTML data-attribute
        const bridge = document.getElementById('chart-data-bridge');
        const labels = JSON.parse(bridge.getAttribute('data-labels'));
        const values = JSON.parse(bridge.getAttribute('data-values'));

        // 2. Inisialisasi Chart.js
        const ctx = document.getElementById('financialReportChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Jenis grafik: batang/bar (bids/keuangan cocok pakai ini)
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Anggaran Disetujui (Rp)',
                    data: values,
                    backgroundColor: '#d97706', // Menggunakan warna aksen emas amber kodemu
                    borderColor: '#0f172a', // Kombinasi warna biru navy utama
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                weight: 'bold',
                                family: 'sans-serif'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Format angka ke Rupiah ringkas (misal: 50M atau 50 Jt)
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush