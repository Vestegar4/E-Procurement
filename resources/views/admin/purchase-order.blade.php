@extends('layouts.admin')
@section('title', 'Purchase Order (PO)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Purchase Order</h4>
        <p class="text-muted mb-0">Approval dan Tracking Purchase Order</p>
    </div>
</div>

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

</div>
@endsection