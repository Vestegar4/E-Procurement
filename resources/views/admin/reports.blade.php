@extends('layouts.admin')
@section('title', 'Laporan & Analitik')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pusat Unduhan Laporan & Grafik Analisis</h4>
    <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Pantau statistik realisasi keuangan sistem dan unduh data mentah pengadaan</p>
</div>

<div class="row g-4">
    
    {{-- 1. DASHBOARD ANALITIK CHART KEUANGAN (STYLE INAPROC VIBES) --}}
    <div class="col-12 mb-2">
        <div class="card card-custom border-0 shadow-sm p-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--color-text-main);">
                        <i class="fa-solid fa-chart-line me-2" style="color: var(--color-accent-bright);"></i>Tren Realisasi Anggaran Pengadaan
                    </h5>
                    <p class="text-muted small mb-0">Grafik akumulasi volume nilai transaksi Purchase Order (PO) per bulan di tahun berjalan</p>
                </div>
                <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: var(--color-success-bg); color: var(--color-success-text); border: 1px solid var(--color-success-border);">
                    <i class="fa-solid fa-circle-check me-1"></i> Live Realtime Data
                </span>
            </div>
            
            {{-- Canvas Wadah Grafik --}}
            <div style="position: relative; width: 100%; height: 350px;">
                <canvas id="inaprocFinancialChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 2. DUA CARD ACTIONS UNDUHAN DATA --}}
    {{-- Card 1: Rekapitulasi --}}
    <div class="col-md-6">
        <div class="card card-custom p-5 text-center border-0 shadow-sm d-flex flex-column align-items-center" style="background: var(--color-white); border-radius: var(--radius-card, 16px); height: 100%;">
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
        <div class="card card-custom p-5 text-center border-0 shadow-sm d-flex flex-column align-items-center" style="background: var(--color-white); border-radius: var(--radius-card, 16px); height: 100%;">
            <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; background: var(--color-primary);">
                <i class="fa-solid fa-file-invoice-dollar fa-2xl" style="color: var(--color-accent);"></i>
            </div>
            <h5 class="fw-bold" style="color: var(--color-text-main);">Laporan Keuangan Vendor</h5>
            <p class="text-muted mb-4 px-3">Unduh basis rekap profil mitra kerja berstatus terverifikasi dan riwayat PO.</p>
            <a href="{{ route('admin.reports.download', 'vendor') }}" class="btn btn-primary-action w-100 mt-auto">
                <i class="fa-solid fa-download me-2"></i> Download Data
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Panggil Pustaka Pembentuk Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Inisialisasi Canvas
        const ctx = document.getElementById('inaprocFinancialChart').getContext('2d');
        
        // 2. TRIK ANTI-ERROR VSCODE: Gunakan tag PHP Native
        const labelsData = <?php echo json_encode($chartLabels ?? []); ?>;
        const valuesData = <?php echo json_encode($chartValues ?? []); ?>;

        // 3. Rekayasa Efek Gradasi Mengalir di bawah garis grafik (Glow Gradient)
        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(245, 159, 11, 0.35)'); // Amber Cerah Transparan
        gradient.addColorStop(1, 'rgba(245, 159, 11, 0.00)'); // Pudar Habis

        // 4. Render Grafik
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Volume Transaksi Pengadaan',
                    data: valuesData,
                    borderColor: '#F59E0B',
                    borderWidth: 3.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#F59E0B',
                    pointBorderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        bodyFont: { weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let val = context.raw;
                                return 'Total Nilai: Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#334155', font: { weight: '600' } }
                    },
                    y: {
                        grid: {
                            color: '#e2e8f0',
                            borderDash: [5, 5], 
                            drawBorder: false
                        },
                        ticks: {
                            color: '#334155',
                            font: { weight: '500' },
                            callback: function(value) {
                                if (value >= 1000000000) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + ' M';
                                } else if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                                }
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush