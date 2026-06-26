@extends('layouts.admin')
@section('title', 'Vendor Management')

@section('content')
    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Vendor[] $vendors */
    @endphp

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Data Rekanan Vendor</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Review berkas, profile komparatif, dan penentuan status verifikasi</p>
    </div>

    {{-- FITUR SEARCH & FILTER (BARU) --}}
    <div class="card card-custom mb-4 border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-3">
            <form action="{{ route('admin.vendors') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control auth-input" placeholder="Cari nama vendor atau email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select auth-input">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Masa Review)</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Terverifikasi (Aktif)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Blacklist (Blokir)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary-action w-100" style="padding: 12px 24px;">
                            <i class="fa-solid fa-filter me-2"></i> Filter Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATA VENDOR --}}
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface);">
                        <tr>
                            <th style="width: 35%;">Nama Perusahaan</th>
                            <th style="width: 30%;">Email Bisnis</th>
                            <th style="width: 20%;">Status</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors ?? [] as $v)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase"
                                            style="width: 40px; height: 40px; background: var(--color-surface); color: var(--color-primary); border: 1px solid var(--color-border);">
                                            {{ substr($v->company_name ?? $v->name, 0, 1) }}
                                        </div>
                                        <span class="fw-bold" style="color: var(--color-text-main);">{{ $v->company_name ?? $v->name }}</span>
                                    </div>
                                </td>
                                <td style="color: var(--color-text-muted); font-weight: 500;">
                                    {{ $v->user->email ?? '-' }}
                                </td>
                                <td>
                                    @if (($v->status ?? '') == 'approved')
                                        <span class="badge badge-pastel-success rounded-pill px-3 py-2">Terverifikasi</span>
                                    @elseif(($v->status ?? '') == 'rejected')
                                        <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Blacklist</span>
                                    @else
                                        <span class="badge badge-pastel-warning rounded-pill px-3 py-2">Pending Review</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-action btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editVendorModal{{ $v->id }}">
                                        Review
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--color-text-muted);">
                                    <div class="mb-3 opacity-50">
                                        <i class="fa-solid fa-building-circle-xmark display-5" style="color: var(--color-primary);"></i>
                                    </div>
                                    <p class="fw-bold mb-0" style="font-size: 1.1rem;">Belum ada vendor yang mendaftar atau ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL REVIEW VENDOR --}}
    @foreach ($vendors ?? [] as $v)
        <div class="modal fade" id="editVendorModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card); overflow: hidden;">
                    <div class="modal-header border-0 p-4" style="background-color: var(--color-surface);">
                        <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">Review Profil Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/vendors/{{ $v->id }}/status" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Nama Vendor</label>
                                <p class="mb-0 fw-bold fs-5" style="color: var(--color-text-main);">{{ $v->company_name ?? $v->name }}</p>
                            </div>
                            <div class="p-4 rounded-3 mb-3" style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                                <label class="form-label fw-bold mb-2 text-uppercase small" style="color: var(--color-text-muted);">Ubah Status Penilaian</label>
                                <select name="status" class="form-select auth-input mt-2">
                                    <option value="pending" {{ ($v->status ?? '') == 'pending' ? 'selected' : '' }}>Pending (Masa Review)</option>
                                    <option value="approved" {{ ($v->status ?? '') == 'approved' ? 'selected' : '' }}>Terverifikasi (Aktif)</option>
                                    <option value="rejected" {{ ($v->status ?? '') == 'rejected' ? 'selected' : '' }}>Blacklist (Blokir)</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                            <button type="button" class="btn btn-outline-action" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary-action">Simpan Keputusan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection