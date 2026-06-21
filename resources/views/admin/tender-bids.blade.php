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
                        <th style="width: 15%;">Waktu Pekerjaan</th>
                        <th style="width: 15%;">Status Berkas</th>
                        <th style="width: 20%;" class="text-center">Aksi Pengumuman</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tender->bids as $bid)
                    <tr>
                        <td>{{ $bid->vendor->company_name ?? 'Nama Vendor' }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}</td>
                        <td>{{ $bid->estimated_time }} Hari</td>
                        <td>
                            @if ($bid->bid_document)
                                <a href="{{ asset('storage/' . $bid->bid_document) }}" target="_blank" class="btn btn-sm shadow-sm" style="background-color: var(--color-surface); color: var(--color-primary); border: 1px solid var(--color-border);">
                                    <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Lihat Berkas
                                </a>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill" style="opacity: 0.7;">
                                    <i class="fa-solid fa-folder-closed"></i> Kosong
                                </span>
                            @endif
                        </td>
                        
                        <td class="text-center align-middle">
                            @if (!$result)
                            <button class="btn btn-sm text-white fw-bold shadow-sm px-3"
                                style="background-color: #059669; border-radius: 6px; transition: 0.2s;"
                                data-bs-toggle="modal"
                                data-bs-target="#winnerModal-{{ $bid->id }}">
                                <i class="fa-solid fa-trophy me-1"></i> Pilih Pemenang
                            </button>
                            @else
                            @if ($result->vendor_id == $bid->vendor_id)
                            <span class="badge px-3 py-2 shadow-sm" style="background-color: var(--color-accent); color: white; font-size: 0.85rem;">
                                <i class="fa-solid fa-crown me-1"></i> PEMENANG
                            </span>
                            @else
                            <span class="badge bg-secondary px-3 py-2 shadow-sm text-white" style="font-size: 0.85rem; opacity: 0.7;">
                                Gugur
                            </span>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (!$result)
@foreach ($tender->bids as $bid)
<div class="modal fade" id="winnerModal-{{ $bid->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card, 16px);">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: #064e3b;">
                    <i class="fa-solid fa-trophy me-2" style="color: var(--color-accent-bright);"></i> Tetapkan Pemenang
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.tenders.select-winner', $tender->id) }}" method="POST">
                @csrf
                <input type="hidden" name="bid_id" value="{{ $bid->id }}">
                <input type="hidden" name="vendor_id" value="{{ $bid->vendor_id }}">

                <div class="modal-body px-4 py-3">
                    <p class="text-muted mb-3" style="font-size: 0.95rem;">
                        Anda akan menetapkan <strong>{{ $bid->vendor->company_name ?? 'Vendor ini' }}</strong> sebagai pemenang lelang. Silakan berikan catatan pengumuman publik.
                    </p>

                    <div class="p-3 mb-3" style="background-color: var(--color-surface); border: 1px dashed var(--color-border); border-radius: 8px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small fw-medium">Nilai Disetujui:</span>
                            <span class="fw-bold" style="color: var(--color-text-main);">Rp {{ number_format($bid->bid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small fw-medium">Estimasi Waktu:</span>
                            <span class="fw-bold" style="color: var(--color-text-main);">{{ $bid->estimated_time }} Hari</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Catatan Pengumuman Pemenang</label>
                        <textarea name="notes" class="form-control auth-input" rows="3" placeholder="Contoh: Terpilih berdasarkan evaluasi teknis terbaik dan penawaran harga terendah..." required></textarea>
                    </div>

                    <div class="alert alert-warning py-2 mb-0 border-0 d-flex align-items-center" style="background-color: var(--color-warning-bg); color: var(--color-warning-text); border-radius: 8px; font-size: 0.85rem;">
                        <i class="fa-solid fa-bullhorn fs-5 me-2"></i>
                        <span>Keputusan ini bersifat final. Setelah disimpan, sistem akan <b>langsung mengumumkan</b> hasil tender ini ke seluruh partisipan.</span>
                    </div>
                </div>

                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn text-white fw-bold shadow-sm px-4" style="background-color: #059669; border-radius: 8px;">
                        <i class="fa-solid fa-check-circle me-1"></i> Sahkan Pemenang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection