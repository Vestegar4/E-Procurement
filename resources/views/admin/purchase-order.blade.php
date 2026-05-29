@extends('layouts.admin')
@section('title', 'Purchase Order (PO)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Purchase Order</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Approval dan Tracking Purchase Order</p>
    </div>
</div>

{{-- KOTAK FILTER & SEARCH PO --}}
    <div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
        <div class="card-body p-3">
            <form action="{{ route('admin.purchase-order') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik ID PO lalu enter / klik Terapkan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{-- Fitur Auto-Submit pada Dropdown --}}
                        <select name="status" class="form-select auth-input" onchange="this.form.submit()" style="cursor: pointer;">
                            <option value="">-- Semua Status Dokumen --</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                            <i class="fa-solid fa-filter me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@if(isset($purchaseOrders) && $purchaseOrders->count() > 0)
    <div class="card card-custom p-4 border-0 shadow-sm" style="border-radius: var(--radius-card);">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--color-surface);">
                    <tr>
                        <th width="10%">ID PO</th>
                        <th width="25%">Nama Tender</th>
                        <th width="20%">Vendor</th>
                        <th width="15%">Total Nominal</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrders as $order)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $order->id }}</td>
                        <td><div class="text-truncate" style="max-width: 200px;">{{ $order->tender->title ?? '-' }}</div></td> 
                        <td><span class="fw-medium">{{ $order->vendor->company_name ?? '-' }}</span></td>
                        <td class="fw-bold" style="color: var(--color-primary);">
                            Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td>
                            @if(($order->status ?? '') == 'approved')
                                <span class="badge badge-pastel-success rounded-pill px-3 py-2">Approved</span>
                            @elseif(($order->status ?? '') == 'rejected')
                                <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Rejected</span>
                            @elseif(($order->status ?? '') == 'completed')
                                <span class="badge bg-secondary rounded-pill px-3 py-2">Completed</span>
                            @else
                                <span class="badge badge-pastel-warning rounded-pill px-3 py-2">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/admin/purchase-orders/{{ $order->id }}/export-pdf" target="_blank" class="btn btn-outline-action btn-sm">
                                <i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if ($purchaseOrders instanceof \Illuminate\Pagination\LengthAwarePaginator && $purchaseOrders->hasPages())
            <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                {{ $purchaseOrders->links('components.pagination') }}
            </div>
        @endif
    </div>
@else
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-5 text-center">
            <i class="fa-solid fa-receipt display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
            <p class="fw-bold text-muted mb-0">Belum ada dokumen Purchase Order yang terbit atau sesuai filter.</p>
        </div>
    </div>
@endif
@endsection