@extends('home')
@section('title', 'Dashboard')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $latestTenders */
@endphp

<div class="container-fluid p-0">

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Total Vendor</p>
                        <h2 class="fw-bold m-0" style="color: #fe81d4;">{{ $vendorCount ?? 0 }}</h2>
                    </div>
                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background-color: #fcd5ce; width: 60px; height: 60px;">
                        <i class="fa-solid fa-users fa-xl" style="color: #fe81d4;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Total Tender</p>
                        <h2 class="fw-bold m-0" style="color: #fbaec1;">{{ $tenderCount ?? 0 }}</h2>
                    </div>
                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background-color: #ffe5b4; width: 60px; height: 60px;">
                        <i class="fa-solid fa-gavel fa-xl" style="color: #fbaec1;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Purchase Orders</p>
                        <h2 class="fw-bold m-0" style="color: #4a4a4a;">{{ $resultCount ?? 0 }}</h2>
                    </div>
                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background-color: #f1f5f9; width: 60px; height: 60px;">
                        <i class="fa-solid fa-receipt fa-xl text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-0">Tender Berjalan Saat Ini</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #fffaf5;">
                        <tr>
                            <th class="text-muted border-0">ID Tender</th>
                            <th class="text-muted border-0">Nama Pengadaan</th>
                            <th class="text-muted border-0">Status</th>
                            <th class="text-muted border-0">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($latestTenders ?? [] as $tender)
                        <tr>
                            <td class="fw-semibold text-secondary">#{{ $tender->id }}</td>
                            <td class="fw-bold">{{ $tender->title }}</td>
                            <td>
                                @if($tender->status == 'published' || $tender->status == 'open')
                                    <span class="badge badge-pastel-success px-3 py-2 rounded-pill">Active</span>
                                @elseif($tender->status == 'closed')
                                    <span class="badge badge-pastel-danger px-3 py-2 rounded-pill">Closed</span>
                                @else
                                    <span class="badge badge-pastel-warning px-3 py-2 rounded-pill">Draft / Pending</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $tender->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fa-solid fa-folder-open fs-1 mb-3 opacity-25"></i>
                                <p class="mb-0">Belum ada data tender terbaru.</p>
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