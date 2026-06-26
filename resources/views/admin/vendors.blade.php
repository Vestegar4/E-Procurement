@extends('layouts.admin')
@section('title', 'Vendor Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Data Rekanan Vendor</h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Tinjau pendaftaran vendor baru dan lakukan
                persetujuan</p>
        </div>
    </div>

    {{-- KOTAK FILTER & SEARCH --}}
    <div class="card card-custom border-0 shadow-sm mb-4"
        style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-3">
            <form action="{{ route('admin.vendors') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 auth-input px-0"
                                placeholder="Ketik lalu enter / klik Terapkan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{-- Fitur Auto-Submit pada Dropdown --}}
                        <select name="status" class="form-select auth-input" onchange="this.form.submit()"
                            style="cursor: pointer;">
                            <option value="">-- Semua Status Vendor --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Masa
                                Review)</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                (Terverifikasi)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                (Ditolak)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 fw-bold shadow-sm"
                            style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                            <i class="fa-solid fa-filter me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom border-0 shadow-sm"
        style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface);">
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width: 35%;">Nama Perusahaan</th>
                            <th style="width: 25%;">Dokumen Pendukung</th>
                            <th style="width: 20%;">Status</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $v)
                            <tr>
                                <td class="fw-bold text-center text-muted">#{{ $v->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold"
                                            style="width: 40px; height: 40px; background-color: var(--color-surface); border: 1px solid var(--color-border); color: var(--color-primary);">
                                            {{ substr($v->company_name ?? 'V', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $v->company_name ?? '-' }}</h6>
                                            <small class="text-muted"><i class="fa-regular fa-envelope me-1"></i>
                                                {{ $v->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $hasNib = $v->documents->where('document_type', 'nib')->isNotEmpty();
                                        $hasNpwp = $v->documents->where('document_type', 'npwp')->isNotEmpty();
                                        $hasSiup = $v->documents->where('document_type', 'siup')->isNotEmpty();
                                    @endphp

                                    <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                                        <li class="mb-1">
                                            <strong>NIB:</strong>
                                            @if ($hasNib)
                                                <span class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i>
                                                    Dilampirkan</span>
                                            @else
                                                <span class="text-danger"><i class="fa-solid fa-xmark me-1"></i>
                                                    Belum</span>
                                            @endif
                                        </li>
                                        <li class="mb-1">
                                            <strong>NPWP:</strong>
                                            @if ($hasNpwp)
                                                <span class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i>
                                                    Dilampirkan</span>
                                            @else
                                                <span class="text-danger"><i class="fa-solid fa-xmark me-1"></i>
                                                    Belum</span>
                                            @endif
                                        </li>
                                        <li>
                                            <strong>SIUP:</strong>
                                            @if ($hasSiup)
                                                <span class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i>
                                                    Dilampirkan</span>
                                            @else
                                                <span class="text-danger"><i class="fa-solid fa-xmark me-1"></i>
                                                    Belum</span>
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    @if (($v->status ?? '') === 'approved')
                                        <span class="badge badge-pastel-success rounded-pill px-3 py-2"><i
                                                class="fa-solid fa-check-circle me-1"></i> Terverifikasi</span>
                                    @elseif(($v->status ?? '') === 'rejected')
                                        <span class="badge badge-pastel-danger rounded-pill px-3 py-2"><i
                                                class="fa-solid fa-ban me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-pastel-warning rounded-pill px-3 py-2"><i
                                                class="fa-solid fa-clock-rotate-left me-1"></i> Menunggu Review</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn fw-bold px-4 py-2 shadow-sm" data-bs-toggle="modal"
                                        data-bs-target="#reviewModal-{{ $v->id }}"
                                        style="background-color: transparent; 
                                                border: 2px solid var(--color-accent); 
                                                color: var(--color-accent); 
                                                border-radius: 8px; 
                                                font-size: 0.95rem;
                                                transition: all 0.2s ease;"
                                        onmouseover="this.style.backgroundColor='var(--color-accent)'; this.style.color='#ffffff';"
                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-accent)';">
                                        Tinjau
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Review --}}
                            <div class="modal fade" id="reviewModal-{{ $v->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg"
                                        style="border-radius: var(--radius-card);">
                                        <div class="modal-header border-bottom p-4">
                                            <h5 class="modal-title fw-bold">Tinjau Vendor #{{ $v->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.vendors.update-status', $v->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted small fw-bold">Status Penilaian Saat
                                                        Ini</label>
                                                    <select name="status" class="form-select auth-input">
                                                        <option value="pending"
                                                            {{ ($v->status ?? '') == 'pending' ? 'selected' : '' }}>Pending
                                                            (Masa Review)
                                                        </option>
                                                        <option value="approved"
                                                            {{ ($v->status ?? '') == 'approved' ? 'selected' : '' }}>
                                                            Approved (Terverifikasi)</option>
                                                        <option value="rejected"
                                                            {{ ($v->status ?? '') == 'rejected' ? 'selected' : '' }}>
                                                            Rejected (Tolak & Blacklist)</option>
                                                    </select>
                                                    {{-- BLOK BARU: TAMPILAN DOKUMEN VENDOR --}}
                                                    <div class="p-4 rounded-3 mb-3"
                                                        style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                                                        <label class="form-label fw-bold mb-3 text-uppercase small"
                                                            style="color: var(--color-text-muted);">
                                                            <i class="fa-solid fa-folder-open me-2"
                                                                style="color: var(--color-accent-bright);"></i> Berkas &
                                                            Lampiran Vendor
                                                        </label>

                                                        @if (isset($v->documents) && $v->documents->count() > 0)
                                                            <div class="d-flex flex-column gap-2">
                                                                @foreach ($v->documents as $doc)
                                                                    <div class="border p-3 rounded-3 shadow-sm mb-2"
                                                                        style="background: var(--color-white); border-color: var(--color-border);">

                                                                        {{-- Bagian Atas: Link Dokumen --}}
                                                                        <a href="{{ $doc->file_url ?? asset('storage/' . $doc->file_path) }}"
                                                                            target="_blank"
                                                                            class="text-decoration-none d-flex align-items-center mb-3 pb-2 border-bottom">
                                                                            <div class="bg-light rounded p-2 me-3">
                                                                                <i
                                                                                    class="fa-solid fa-file-pdf fs-4 text-danger"></i>
                                                                            </div>
                                                                            <div>
                                                                                <span class="fw-bold d-block"
                                                                                    style="color: var(--color-text-main); font-size: 0.9rem;">
                                                                                    {{ $doc->document_name ?? (strtoupper($doc->document_type) ?? 'Dokumen Pendukung') }}
                                                                                </span>
                                                                                <small class="text-muted"
                                                                                    style="font-size: 0.75rem;">Klik untuk
                                                                                    melihat file <i
                                                                                        class="fa-solid fa-arrow-up-right-from-square ms-1"></i></small>
                                                                            </div>
                                                                        </a>

                                                                        {{-- Bagian Bawah: Dropdown Verifikasi per Dokumen --}}
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between">
                                                                            <span class="small fw-bold text-muted">Status
                                                                                Dokumen:</span>
                                                                            <select
                                                                                name="documents[{{ $doc->id }}][status]"
                                                                                class="form-select form-select-sm fw-bold {{ $doc->status == 'approved' ? 'text-success' : ($doc->status == 'rejected' ? 'text-danger' : 'text-warning') }}"
                                                                                style="width: auto; min-width: 140px; cursor: pointer;">
                                                                                <option value="pending"
                                                                                    class="text-warning"
                                                                                    {{ $doc->status == 'pending' ? 'selected' : '' }}>
                                                                                    ⏳ Pending</option>
                                                                                <option value="approved"
                                                                                    class="text-success"
                                                                                    {{ $doc->status == 'approved' ? 'selected' : '' }}>
                                                                                    ✅ Approved</option>
                                                                                <option value="rejected"
                                                                                    class="text-danger"
                                                                                    {{ $doc->status == 'rejected' ? 'selected' : '' }}>
                                                                                    ❌ Rejected</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center py-3 border border-dashed rounded-3"
                                                                style="background: var(--color-white); border-color: var(--color-border);">
                                                                <i
                                                                    class="fa-regular fa-folder-closed fs-3 mb-2 opacity-50 text-muted"></i>
                                                                <p class="text-muted small mb-0 fw-medium">Vendor belum
                                                                    melampirkan dokumen apapun.</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer p-4 pt-0 border-0">
                                                <button type="button" class="btn btn-light border"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary-action px-4"
                                                    style="color: var(--color-text-main); background-color: var(--color-accent-bright);">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-building-circle-xmark display-5 mb-3 opacity-25"></i>
                                    <p class="fw-bold mb-0">Tidak ada data vendor ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            @if ($vendors instanceof \Illuminate\Pagination\LengthAwarePaginator && $vendors->hasPages())
                <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                    {{ $vendors->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>
    @push('scripts')
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // // Jika sebelumnya ada pemanggilan Swal.fire default di sini, jadikan komentar.

                    // Memicu fungsi pintar global berdasarkan session dari backend
                    if (typeof window.triggerSystemEvent === 'function') {
                        window.triggerSystemEvent('Aktivitas Vendor', '{{ session('success') }}', 'success');
                    }
                });
            </script>
        @endif
    @endpush
@endsection
