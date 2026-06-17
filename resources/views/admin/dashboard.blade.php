@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $latestTenders */
@endphp

<div class="container-fluid p-0">

    {{-- SECTION 1: METRIC CARD RINGKASAN DATA (RESPONSIF ALL DEVICE) --}}
    <div class="row g-4 mb-5">

        {{-- Card 1: Total Vendor --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Total Vendor</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $vendorCount ?? 0 }}
                        </h2>
                    </div>
                    {{-- flex-shrink-0 memastikan lingkaran ikon tidak peyang di layar HP --}}
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-users fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Tender Aktif --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Total Tender Aktif</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $tenderCount ?? 0 }}
                        </h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-file-contract fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Purchase Orders (PO) --}}
        <div class="col-12 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Purchase Orders (PO)</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $purchaseOrderCount ?? 0 }}
                        </h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-file-invoice-dollar fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SECTION 2: TABEL DATA DENGAN KONSEP KONTRAST TINGGI --}}
    <div class="card card-custom border-0 shadow-sm"
        style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0" style="color: var(--color-text-main); letter-spacing: -0.01em;">Aktivitas Pengadaan
                Terbaru</h5>
            <span class="badge bg-light text-dark px-3 py-2 border fw-semibold"
                style="border-radius: 20px; font-size: 0.8rem;">Sistem Master Realtime</span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 15%;">ID Paket</th>
                            <th style="width: 50%;">Nama / Deskripsi Paket Tender</th>
                            <th style="width: 20%;">Status Sistem</th>
                            <th style="width: 15%;">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestTenders ?? [] as $tender)
                        <tr>
                            <td class="fw-bold" style="color: var(--color-text-muted);">#{{ $tender->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px; background: var(--color-surface);">
                                        <i class="fa-solid fa-briefcase small"
                                            style="color: var(--color-primary);"></i>
                                    </div>
                                    <span class="fw-bold"
                                        style="color: var(--color-text-main); font-size: 1.05rem;">{{ $tender->title }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($tender->result || $tender->status == 'finished')
                                <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Finished</span>
                                @elseif($tender->status == 'published' || $tender->status == 'open' || $tender->status == 'bidding')
                                <span class="badge badge-pastel-success rounded-pill px-3 py-2">Active</span>
                                @elseif($tender->status == 'closed')
                                <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Closed</span>
                                @else
                                <span class="badge badge-pastel-warning rounded-pill px-3 py-2">Draft /
                                    Pending</span>
                                @endif
                            </td>
                            <td style="color: var(--color-text-muted); font-weight: 600;">
                                <i class="fa-regular fa-calendar-check me-1 small"></i>
                                {{ $tender->created_at?->format('d M Y') ?? 'Belum ada tanggal' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color: var(--color-text-muted);">
                                <div class="mb-3 opacity-50">
                                    <i class="fa-solid fa-folder-open display-5"
                                        style="color: var(--color-primary);"></i>
                                </div>
                                <p class="fw-bold mb-0" style="font-size: 1.1rem;">Belum ada berkas tender yang
                                    tersimpan dalam sistem.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection