@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
    <div class="container-fluid p-0">

        {{-- HEADER --}}
        <div class="mb-4">
            <h3 class="fw-bold mb-1">
                Halo, {{ Auth::user()->vendor->company_name ?? Auth::user()->name }}
            </h3>

            <p class="text-muted mb-0">
                Pantau tender, penawaran, dan aktivitas perusahaan Anda.
            </p>
        </div>

        {{-- STATS --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card card-custom p-4 h-100">
                    <p class="text-muted fw-semibold mb-1">
                        Tender Tersedia
                    </p>

                    <h2 class="fw-bold text-primary">
                        {{ $availableTenders ?? 0 }}
                    </h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 h-100">
                    <p class="text-muted fw-semibold mb-1">
                        Tender Diikuti
                    </p>

                    <h2 class="fw-bold text-success">
                        {{ $joinedTenders ?? 0 }}
                    </h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 h-100">
                    <p class="text-muted fw-semibold mb-1">
                        Bid Dikirim
                    </p>

                    <h2 class="fw-bold text-warning">
                        {{ $submittedBids ?? 0 }}
                    </h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-custom p-4 h-100">
                    <p class="text-muted fw-semibold mb-1">
                        Status Akun
                    </p>

                    @if (Auth::user()->vendor->status === 'approved')
                        <span class="badge bg-success px-3 py-2">
                            Approved
                        </span>
                    @elseif(Auth::user()->vendor->status === 'pending')
                        <span class="badge bg-warning text-dark px-3 py-2">
                            Pending
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2">
                            Rejected
                        </span>
                    @endif
                </div>
            </div>

        </div>

        {{-- ACCOUNT STATUS --}}
        <div class="card card-custom mb-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    Status Verifikasi Vendor
                </h5>

                @if (Auth::user()->vendor->status === 'approved')
                    <div class="alert alert-success mb-0">
                        Akun vendor Anda telah diverifikasi dan dapat mengikuti proses tender.
                    </div>
                @elseif(Auth::user()->vendor->status === 'pending')
                    <div class="alert alert-warning mb-0">
                        Akun vendor sedang menunggu verifikasi admin.
                    </div>
                @else
                    <div class="alert alert-danger mb-0">
                        Akun vendor ditolak. Silakan hubungi administrator.
                    </div>
                @endif

            </div>
        </div>

        {{-- QUICK ACTION --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <a href="{{ route('vendor.tenders') }}" class="text-decoration-none">

                    <div class="card card-custom p-4 text-center h-100">

                        <i class="fa-solid fa-file-contract fs-2 mb-3 text-primary"></i>

                        <h6 class="fw-bold mb-1">
                            Lihat Tender
                        </h6>

                        <p class="text-muted small mb-0">
                            Cari tender yang tersedia
                        </p>

                    </div>

                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('vendor.documents.index') }}" class="text-decoration-none">

                    <div class="card card-custom p-4 text-center h-100">

                        <i class="fa-solid fa-folder-open fs-2 mb-3 text-warning"></i>

                        <h6 class="fw-bold mb-1">
                            Dokumen Vendor
                        </h6>

                        <p class="text-muted small mb-0">
                            Upload dan kelola dokumen
                        </p>

                    </div>

                </a>
            </div>

        </div>

        <div class="card card-custom">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">
                    Tender Terbaru
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Tender</th>
                                <th>Status</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($latestTenders as $tender)
                                <tr>

                                    <td>
                                        <div class="fw-bold">
                                            {{ $tender->title }}
                                        </div>
                                    </td>

                                    <td>

                                        @if ($tender->status == 'open')
                                            <span class="badge bg-success">
                                                Open
                                            </span>
                                        @else
                                            <span class="badge bg-primary">
                                                Bidding
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        @if ($tender->timeline)
                                            {{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('d M Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">

                                        Belum ada tender terbaru.

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
