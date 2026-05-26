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

                <div class="row g-3 align-items-center">

                    <div class="col-md-5">
                        <input type="text" class="form-control" placeholder="Cari tender...">
                    </div>

                    <div class="col-md-3">
                        <select class="form-select">
                            <option>Semua Status</option>
                            <option>Open</option>
                            <option>Bidding</option>
                            <option>Closed</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                </div>

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
                                        </div>

                                        <small class="text-muted">
                                            Tender ID #{{ $tender->id }}
                                        </small>

                                    </td>

                                    <td class="fw-semibold">
                                        Rp {{ number_format($tender->budget, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if ($tender->timeline)
                                            {{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('d M Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td>

                                        @if ($tender->status == 'open')
                                            <span class="badge bg-success px-3 py-2">
                                                Open
                                            </span>
                                        @elseif($tender->status == 'bidding')
                                            <span class="badge bg-primary px-3 py-2">
                                                Bidding
                                            </span>
                                        @elseif($tender->status == 'finished')
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

                                        <a href="#" class="btn btn-outline-primary btn-sm">

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

    </div>
@endsection
