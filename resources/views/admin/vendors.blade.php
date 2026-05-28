@extends('layouts.admin')
@section('title', 'Vendor Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Data Rekanan Vendor</h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Tinjau pendaftaran vendor baru dan lakukan persetujuan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- KOTAK FILTER & SEARCH --}}
    <div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-3">
            <form action="{{ route('admin.vendors') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik lalu enter / klik Terapkan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{-- Fitur Auto-Submit pada Dropdown --}}
                        <select name="status" class="form-select auth-input" onchange="this.form.submit()" style="cursor: pointer;">
                            <option value="">-- Semua Status Vendor --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Masa Review)</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Terverifikasi)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                            <i class="fa-solid fa-filter me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface);">
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width: 35%;">Nama Perusahaan</th>
                            <th style="width: 25%;">Kredensial Bisnis</th>
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
                                        <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px; background-color: var(--color-surface); border: 1px solid var(--color-border); color: var(--color-primary);">
                                            {{ substr($v->company_name ?? 'V', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $v->company_name ?? '-' }}</h6>
                                            <small class="text-muted"><i class="fa-regular fa-envelope me-1"></i> {{ $v->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block small text-muted mb-1">Tipe: <strong>{{ strtoupper($v->company_type ?? '-') }}</strong></span>
                                    <span class="d-block small text-muted">NPWP: {{ $v->npwp ?? 'Belum dilampirkan' }}</span>
                                </td>
                                <td>
                                    @if(($v->status ?? '') === 'approved')
                                        <span class="badge badge-pastel-success rounded-pill px-3 py-2"><i class="fa-solid fa-check-circle me-1"></i> Terverifikasi</span>
                                    @elseif(($v->status ?? '') === 'rejected')
                                        <span class="badge badge-pastel-danger rounded-pill px-3 py-2"><i class="fa-solid fa-ban me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-pastel-warning rounded-pill px-3 py-2"><i class="fa-solid fa-clock-rotate-left me-1"></i> Menunggu Review</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-action fw-medium px-3" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $v->id }}">
                                        Tinjau
                                    </button>
                                </td>
                            </tr>
                            
                            {{-- Modal Review --}}
                            <div class="modal fade" id="reviewModal-{{ $v->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card);">
                                        <div class="modal-header border-bottom p-4">
                                            <h5 class="modal-title fw-bold">Tinjau Vendor #{{ $v->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.vendors.update-status', $v->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted small fw-bold">Status Penilaian Saat Ini</label>
                                                    <select name="status" class="form-select auth-input">
                                                        <option value="pending" {{ ($v->status ?? '') == 'pending' ? 'selected' : '' }}>Pending (Masa Review)</option>
                                                        <option value="approved" {{ ($v->status ?? '') == 'approved' ? 'selected' : '' }}>Approved (Terverifikasi)</option>
                                                        <option value="rejected" {{ ($v->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected (Tolak & Blacklist)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer p-4 pt-0 border-0">
                                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary-action px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan=\"5\" class="text-center py-5 text-muted">
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
                    {{ $vendors->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection