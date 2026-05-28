@extends('layouts.vendor')

@section('title', 'Laporan Vendor')

@section('content')

    <div class="container-fluid p-0">

        {{-- HEADER --}}
        <div class="mb-4">

            <h3 class="fw-bold mb-1">
                Laporan Vendor
            </h3>

            <p class="text-muted mb-0">
                Ringkasan aktivitas procurement dan performa perusahaan Anda.
            </p>

        </div>

        {{-- STATS --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Tender Diikuti
                        </p>

                        <h3 class="fw-bold">
                            {{ $joinedTenders }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Bid Dikirim
                        </p>

                        <h3 class="fw-bold">
                            {{ $submittedBids }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Tender Menang
                        </p>

                        <h3 class="fw-bold text-success">
                            {{ $winningBids }}
                        </h3>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Total Penawaran
                        </p>

                        <h5 class="fw-bold">
                            Rp {{ number_format($totalBidAmount, 0, ',', '.') }}
                        </h5>

                    </div>

                </div>

            </div>

        </div>

        <div class="row g-4">

            {{-- RECENT BIDS --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Penawaran Terbaru
                        </h5>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>Tender</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($recentBids as $bid)
                                        <tr>

                                            <td>
                                                {{ $bid->tender->title ?? '-' }}
                                            </td>

                                            <td>
                                                Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}
                                            </td>

                                            <td>

                                                @php
                                                    $result = $bid->tender->result ?? null;
                                                    $effectiveStatus = $bid->status;
                                                    if ($bid->status === 'pending' && $result) {
                                                        $effectiveStatus =
                                                            $result->winner_vendor_id === $bid->vendor_id
                                                                ? 'won'
                                                                : 'lost';
                                                    }
                                                @endphp

                                                @if ($effectiveStatus == 'won')
                                                    <span class="badge bg-success">
                                                        Won
                                                    </span>
                                                @elseif($effectiveStatus == 'lost')
                                                    <span class="badge bg-danger">
                                                        Lost
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        Pending
                                                    </span>
                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="3" class="text-center text-muted py-4">

                                                Belum ada data penawaran.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            {{-- RECENT TENDERS --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">
                            Tender Diikuti
                        </h5>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>Tender</th>
                                        <th>Tanggal Join</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($recentTenders as $item)
                                        <tr>

                                            <td>
                                                {{ $item->tender->title ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $item->joined_at?->format('d M Y') }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="2" class="text-center text-muted py-4">

                                                Belum mengikuti tender.

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
