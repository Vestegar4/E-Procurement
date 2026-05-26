@extends('layouts.vendor')

@section('title', 'Detail Tender')

@section('content')

    <div class="container-fluid p-0">

        {{-- HEADER --}}
        <div class="mb-4">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                <div>

                    <h3 class="fw-bold mb-1">
                        {{ $tender->title }}
                    </h3>

                    <p class="text-muted mb-0">
                        Tender ID #{{ $tender->id }}
                    </p>

                </div>

                <div>

                    @if ($tender->status == 'open')
                        <span class="badge bg-success px-3 py-2">
                            Open Registration
                        </span>
                    @elseif($tender->status == 'bidding')
                        <span class="badge bg-primary px-3 py-2">
                            Bidding
                        </span>
                    @elseif($tender->status == 'closed')
                        <span class="badge bg-danger px-3 py-2">
                            Closed
                        </span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">
                            Draft
                        </span>
                    @endif

                </div>

            </div>

        </div>

        <div class="row g-4">

            {{-- LEFT CONTENT --}}
            <div class="col-lg-8">

                {{-- TENDER INFO --}}
                <div class="card card-custom mb-4">

                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0">
                            Informasi Tender
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="mb-4">

                            <label class="text-muted small mb-1">
                                Deskripsi
                            </label>

                            <p class="mb-0">
                                {{ $tender->description ?? 'Tidak ada deskripsi tender.' }}
                            </p>

                        </div>

                        <div class="row g-4">

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">
                                    Budget
                                </label>

                                <div class="fw-bold fs-5">

                                    Rp {{ number_format($tender->budget ?? 0, 0, ',', '.') }}

                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">
                                    Tanggal Dibuat
                                </label>

                                <div class="fw-semibold">

                                    {{ $tender->created_at->format('d M Y') }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- TIMELINE --}}
                <div class="card card-custom mb-4">

                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0">
                            Timeline Tender
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        @if ($tender->timeline)
                            <div class="timeline-item mb-4">

                                <div class="fw-bold">
                                    Registrasi Vendor
                                </div>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse($tender->timeline->registration_start)->format('d M Y H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($tender->timeline->registration_end)->format('d M Y H:i') }}

                                </small>

                            </div>

                            <div class="timeline-item">

                                <div class="fw-bold">
                                    Masa Bidding
                                </div>

                                <small class="text-muted">

                                    {{ \Carbon\Carbon::parse($tender->timeline->bidding_start)->format('d M Y H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('d M Y H:i') }}

                                </small>

                            </div>
                        @else
                            <p class="text-muted mb-0">
                                Timeline belum tersedia.
                            </p>
                        @endif

                    </div>

                </div>

                {{-- ANNOUNCEMENTS --}}
                <div class="card card-custom">

                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0">
                            Pengumuman
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        @forelse($tender->announcements as $announcement)
                            <div class="border rounded-4 p-3 mb-3">

                                <div class="fw-bold mb-1">
                                    {{ $announcement->title }}
                                </div>

                                <p class="text-muted mb-2">
                                    {{ $announcement->content }}
                                </p>

                                <small class="text-muted">
                                    {{ $announcement->created_at->format('d M Y H:i') }}
                                </small>

                            </div>

                        @empty

                            <p class="text-muted mb-0">
                                Belum ada pengumuman.
                            </p>
                        @endforelse

                    </div>

                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="col-lg-4">

                {{-- ACTION CARD --}}
                <div class="card card-custom mb-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Aksi Tender
                        </h5>

                        @if ($tender->status == 'open')
                            <form action="{{ route('vendor.tenders.join', $tender->id) }}" method="POST">

                                @csrf

                                <button type="submit" class="btn btn-success w-100">

                                    Join Tender

                                </button>

                            </form>
                        @elseif($tender->status == 'bidding')
                            <a href="{{ route('vendor.bids.create', $tender->id) }}" class="btn btn-primary w-100">

                                Submit Penawaran

                            </a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>

                                Tender Closed

                            </button>
                        @endif

                    </div>

                </div>

                {{-- QUICK INFO --}}
                <div class="card card-custom">

                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-3">
                            Informasi Tambahan
                        </h6>

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Status Tender
                            </small>

                            <div class="fw-semibold">
                                {{ ucfirst($tender->status) }}
                            </div>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Total Peserta
                            </small>

                            <div class="fw-semibold">

                                {{ $tender->participants->count() ?? 0 }} Vendor

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
