@extends('layouts.admin')
@section('title', 'Bids Tender')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Penawaran Tender
            </h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">{{ $tender->title }} (ID #{{ $tender->id }})
            </p>
        </div>
        @include('components.back-button', ['url' => route('admin.procurement'), 'label' => 'Kembali ke Pengadaan'])
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-custom border-0 shadow-sm"
        style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface);">
                        <tr>
                            <th style="width: 30%;">Vendor</th>
                            <th style="width: 20%;">Nilai Penawaran</th>
                            <th style="width: 20%;">Waktu Submit</th>
                            <th style="width: 20%;">Catatan</th>
                            <th class="text-center" style="width: 10%;">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bids as $bid)
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color: var(--color-text-main);">
                                        {{ $bid->vendor->name ?? 'Vendor' }}
                                    </div>
                                    <div class="text-muted small">{{ $bid->vendor->email ?? '-' }}</div>
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($bid->bid_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-muted">
                                    {{ $bid->submitted_at ? $bid->submitted_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="text-muted">
                                    {{ $bid->notes ?? '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($result)
                                        @if (($result->winner_vendor_id ?? null) === $bid->vendor_id)
                                            <span class="badge bg-success">Winner</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    @else
                                        <input type="radio" name="bid_id" value="{{ $bid->id }}"
                                            form="selectWinnerForm">
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fa-solid fa-inbox fs-1 text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada penawaran.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (!$result)
        <div class="card card-custom border-0 shadow-sm mt-4"
            style="background: var(--color-white); border-radius: var(--radius-card);">
            <div class="card-body p-4">
                <form id="selectWinnerForm" action="{{ route('admin.tenders.select-winner', $tender->id) }}"
                    method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small"
                            style="color: var(--color-text-muted);">Catatan Penetapan</label>
                        <textarea name="notes" class="form-control auth-input" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-action">Tetapkan Pemenang</button>
                </form>
            </div>
        </div>
    @endif
@endsection
