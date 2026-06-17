@extends('layouts.admin')
@section('title', 'Purchase Order (PO)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Purchase Order</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Approval dan Tracking Alur Purchase Order</p>
    </div>
</div>

{{-- KOTAK FILTER & SEARCH PO (Dikembalikan & Diupdate Sesuai Alur ERP) --}}
<div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
    <div class="card-body p-3">
        <form action="{{ route('admin.purchase-order') }}" method="GET">
            <div class="row g-2 align-items-center">
                
                {{-- Input Pencarian Teks --}}
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-hashtag"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik ID PO lalu tekan enter..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Dropdown Status PO (Dengan 4 Alur Lengkap) --}}
                <div class="col-md-5">
                    <select name="status" class="form-select auth-input" onchange="this.form.submit()" style="cursor: pointer;">
                        <option value="">Semua Status PO</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Konsep Tersimpan)</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued (Menunggu Pengiriman)</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai & Lunas)</option>
                    </select>
                </div>
                
                {{-- Tombol Cari --}}
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                        <i class="fa-solid fa-filter me-1"></i> Terapkan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL DATA PURCHASE ORDER --}}
@if (isset($purchaseOrders) && count($purchaseOrders) > 0)
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface); color: var(--color-text-main); font-weight: 700;">
                        <tr>
                            <th class="px-4 py-3" style="width: 10%;">ID PO</th>
                            <th class="py-3" style="width: 30%;">Paket Tender</th>
                            <th class="py-3" style="width: 20%;">Nama Vendor</th>
                            <th class="py-3" style="width: 15%;">Total Anggaran</th>
                            <th class="py-3" style="width: 15%;">Status PO</th>
                            <th class="text-center py-3" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrders as $po)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                            <td class="px-4 fw-bold" style="color: var(--color-primary);">#{{ $po->id }}</td>
                            <td class="fw-semibold" style="color: var(--color-text-main);">{{ $po->tender->title ?? 'N/A' }}</td>
                            <td style="color: var(--color-text-muted); font-weight: 500;">{{ $po->vendor->company_name ?? ($po->vendor->name ?? 'N/A') }}</td>
                            <td class="fw-bold" style="color: var(--color-accent);">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            
                            <td>
                                @if(strtolower($po->status) == 'draft')
                                    <span class="badge rounded-pill px-3 py-2 fw-bold text-dark shadow-sm" style="background-color: #d1d5db; border: 1.5px solid #a6a9ae; font-size: 0.75rem;">
                                        <i class="fa-solid fa-file-pen me-1"></i> DRAFT
                                    </span>
                                @elseif(strtolower($po->status) == 'issued')
                                    <span class="badge rounded-pill px-3 py-2 fw-bold text-white shadow-sm" style="background-color: #41ff00; font-size: 0.75rem;">
                                        <i class="fa-solid fa-paper-plane me-1"></i> ISSUED
                                    </span>
                                @elseif(strtolower($po->status) == 'cancelled')
                                    <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm" style="background-color: var(--color-danger-bg); color: var(--color-danger-text); border: 1px solid var(--color-danger-border); font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> CANCELLED
                                    </span>
                                @elseif(strtolower($po->status) == 'completed')
                                    <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm" style="background-color: var(--color-success-bg); color: var(--color-success-text); border: 1px solid var(--color-success-border); font-size: 0.75rem;">
                                        <i class="fa-solid fa-check-double me-1"></i> COMPLETED
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2 fw-bold bg-secondary text-white shadow-sm" style="font-size: 0.75rem;">
                                        {{ strtoupper($po->status ?? 'UNKNOWN') }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="/admin/purchase-orders/{{ $po->id }}/export-pdf" target="_blank" class="btn btn-outline-danger btn-sm mb-2 w-100 fw-bold" style="border-radius: 6px;">
                                    <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                                </a>

                                {{-- Form Ubah Status --}}
                                <form action="{{ route('admin.purchase-orders.update-status', $po->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm text-center shadow-sm" onchange="this.form.submit()" style="border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: 600;">
                                        <option value="draft" {{ strtolower($po->status) == 'draft' ? 'selected' : '' }}>Ubah ke Draft</option>
                                        <option value="issued" {{ strtolower($po->status) == 'issued' ? 'selected' : '' }}>Issued PO</option>
                                        <option value="cancelled" {{ strtolower($po->status) == 'cancelled' ? 'selected' : '' }}>Cancel PO</option>
                                        <option value="completed" {{ strtolower($po->status) == 'completed' ? 'selected' : '' }}>Complete PO</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Navigasi Paginasi Halaman --}}
            @if ($purchaseOrders instanceof \Illuminate\Pagination\LengthAwarePaginator && $purchaseOrders->hasPages())
                <div class="d-flex justify-content-center mt-4 pt-3 border-top pb-4">
                    {{ $purchaseOrders->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>
@else
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-5 text-center">
            <i class="fa-solid fa-receipt display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
            <p class="fw-bold text-muted mb-0">Belum ada dokumen Purchase Order yang terbit atau sesuai pencarian filter.</p>
        </div>
    </div>
@endif
@endsection