@extends('layouts.admin')
@section('title', 'Purchase Order (PO)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Purchase Order</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Approval dan Tracking Purchase Order</p>
    </div>
</div>

<<<<<<< HEAD
<div class="card card-custom p-4">
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th width="10%">ID PO</th>
                    <th width="35%">Nama Paket Pekerjaan (Tender)</th>
                    <th width="25%">Vendor Pemenang</th>
                    <th width="15%">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="po-table-body">
                <tr>
                    <td><strong>#1</strong></td>
                    <td>Pengadaan Perangkat Komputer Server</td>
                    <td>PT Vendor Teknologi Maju</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td class="text-center">
                        
                        @foreach ( $purchaseOrders as $order )
                            
                        <a href="/purchase-orders/{{ $order->id }}/export-pdf" target="_blank" class="btn btn-sm btn-danger shadow-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                        </a>
                        @endforeach

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

=======
<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-5 text-center">
        <i class="fa-solid fa-receipt display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
        <p class="fw-bold text-muted mb-0">Daftar Purchase Order yang telah disetujui dari pemenang tender akan muncul di sini.</p>
    </div>
>>>>>>> 6c5fab4 (big update frontend)
</div>
@endsection