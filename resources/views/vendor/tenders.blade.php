@extends('layouts.vendor')

@section('title', 'Pengadaan')

@section('content')
    <div class="container-fluid p-0">

        {{-- HEADER --}}
        <div class="mb-4">
            <h3 class="fw-bold mb-1">
                Pengadaan
            </h3>

            <p class="text-muted mb-0">
                Lihat dan ikuti tender yang tersedia untuk perusahaan Anda.
            </p>
        </div>

        {{-- FILTER --}}
        <div class="card card-custom mb-4">
            <div class="card-body p-3">
                <form action="{{ route('vendor.tenders') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <input type="text" name="q" class="form-control" placeholder="Cari tender..."
                                value="{{ $search ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="open" {{ ($status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="aanwijzing" {{ ($status ?? '') === 'aanwijzing' ? 'selected' : '' }}>
                                    Aanwijzing</option>
                                <option value="bidding" {{ ($status ?? '') === 'bidding' ? 'selected' : '' }}>
                                    Bidding</option>
                                <option value="closed" {{ ($status ?? '') === 'closed' ? 'selected' : '' }}>
                                    Closed</option>
                                <option value="finished" {{ ($status ?? '') === 'finished' ? 'selected' : '' }}>
                                    Finished</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card card-custom">

            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">
                    Daftar Tender
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="table-responsive">

                    <table class="table align-middle table-hover">

                        <thead style="background-color: #f8fafc;">

                            <tr>
                                <th class="border-0 text-muted">
                                    Tender
                                </th>

                                <th class="border-0 text-muted">
                                    Budget
                                </th>

                                <th class="border-0 text-muted">
                                    Deadline
                                </th>

                                <th class="border-0 text-muted">
                                    Status
                                </th>

                                <th class="border-0 text-muted text-end">
                                    Aksi
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($tenders ?? [] as $tender)
                                <tr>

                                    <td>

                                        <div class="fw-bold">
                                            {{ $tender->title }}
                                            @if (!empty($joinedTenderIds) && in_array($tender->id, $joinedTenderIds, true))
                                                <span class="badge bg-info ms-2">Joined</span>
                                            @endif
                                            @if ($tender->is_winner ?? false)
                                                <span class="badge bg-success ms-2">Winner</span>
                                            @elseif($tender->is_loser ?? false)
                                                <span class="badge bg-danger ms-2">Lost</span>
                                            @endif
                                        </div>

                                        <small class="text-muted">
                                            Tender ID #{{ $tender->id }}
                                        </small>

                                    </td>

                                    <td class="fw-semibold">
                                        Rp {{ number_format($tender->budget ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if ($tender->timeline)
                                            {{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('d M Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td>

                                        @php
                                            $effectiveStatus = $tender->effective_status ?? $tender->status;
                                        @endphp

                                        @if ($effectiveStatus == 'open')
                                            <span class="badge bg-success px-3 py-2">
                                                Open
                                            </span>
                                        @elseif($effectiveStatus == 'aanwijzing')
                                            <span class="badge bg-warning px-3 py-2">
                                                Aanwijzing
                                            </span>
                                        @elseif($effectiveStatus == 'bidding')
                                            <span class="badge bg-primary px-3 py-2">
                                                Bidding
                                            </span>
                                        @elseif($effectiveStatus == 'closed' || $effectiveStatus == 'finished')
                                            <span class="badge bg-danger px-3 py-2">
                                                Closed
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">
                                                Draft
                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-end">

                                        <a href="{{ route('vendor.tenders.show', $tender->id) }}"
                                            class="btn btn-outline-primary btn-sm">

                                            Detail

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-5">

                                        <i class="fa-solid fa-folder-open fs-1 text-muted opacity-25 mb-3"></i>

                                        <p class="text-muted mb-0">
                                            Belum ada tender tersedia.
                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        @if (isset($tenders) && $tenders->hasPages())
            <div class="mt-4">
                {{ $tenders->links() }}
            </div>
        @endif

    </div>
@endsection
