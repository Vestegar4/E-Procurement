@extends('layouts.admin')
@section('title', 'Procurement / Tender')

@section('content')
    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $tenders */
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Daftar Pengadaan Aktif
            </h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola pembuatan dan publikasi paket tender</p>
        </div>
        <button class="btn btn-primary-action" data-bs-toggle="modal" data-bs-target="#createTenderModal">
            <i class="fa-solid fa-plus me-2" style="color: var(--color-accent);"></i> Buat Tender Baru
        </button>
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

    @if (isset($tenders) && $tenders->count() > 0)
        <div class="card card-custom border-0 shadow-sm"
            style="background: var(--color-white); border-radius: var(--radius-card);">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: var(--color-surface);">
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 40%;">Nama Tender</th>
                                <th style="width: 20%;">Status</th>
                                <th style="width: 20%;">Registrasi</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenders as $tender)
                                <tr>
                                    <td class="fw-bold">#{{ $tender->id }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--color-text-main);">{{ $tender->title }}
                                        </div>
                                        <div class="text-muted small">Budget:
                                            {{ $tender->budget ? number_format($tender->budget, 0, ',', '.') : '-' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $status = $tender->status ?? 'draft';
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2"
                                            style="background-color: var(--color-surface); color: var(--color-text-main); border: 1px solid var(--color-border);">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        @if ($tender->timeline)
                                            {{ \Carbon\Carbon::parse($tender->timeline->registration_start)->format('d M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($tender->timeline->registration_end)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-2 align-items-center">
                                            @if (($tender->status ?? '') === 'draft')
                                                <form action="{{ route('admin.procurement.publish', $tender->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary-action">Publish</button>
                                                </form>
                                            @endif

                                            <button type="button" class="btn btn-sm btn-outline-action"
                                                data-bs-toggle="modal" data-bs-target="#editTender{{ $tender->id }}">
                                                Edit
                                            </button>

                                            <a href="{{ route('admin.tenders.bids', $tender->id) }}"
                                                class="btn btn-sm btn-outline-action">
                                                Bids
                                            </a>

                                            <form action="{{ route('admin.procurement.status', $tender->id) }}"
                                                method="POST" class="w-100">
                                                @csrf
                                                <div class="d-flex gap-2">
                                                    <select name="status" class="form-select form-select-sm">
                                                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>
                                                            Draft</option>
                                                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>
                                                            Open</option>
                                                        <option value="aanwijzing"
                                                            {{ $status === 'aanwijzing' ? 'selected' : '' }}>
                                                            Aanwijzing</option>
                                                        <option value="bidding"
                                                            {{ $status === 'bidding' ? 'selected' : '' }}>
                                                            Bidding</option>
                                                        <option value="closed"
                                                            {{ $status === 'closed' ? 'selected' : '' }}>
                                                            Closed</option>
                                                        <option value="finished"
                                                            {{ $status === 'finished' ? 'selected' : '' }}>
                                                            Finished</option>
                                                    </select>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-action">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @foreach ($tenders as $tender)
            <div class="modal fade" id="editTender{{ $tender->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg"
                        style="border-radius: var(--radius-card); overflow: hidden;">
                        <div class="modal-header border-0 p-4" style="background-color: var(--color-surface);">
                            <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">Edit Tender</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.procurement.update', $tender->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-uppercase small"
                                        style="color: var(--color-text-muted);">Nama / Paket Pengadaan</label>
                                    <input type="text" name="title" class="form-control auth-input"
                                        value="{{ $tender->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-uppercase small"
                                        style="color: var(--color-text-muted);">Deskripsi</label>
                                    <textarea name="description" class="form-control auth-input" rows="3" required>{{ $tender->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-uppercase small"
                                        style="color: var(--color-text-muted);">Spesifikasi</label>
                                    <textarea name="specification" class="form-control auth-input" rows="3" required>{{ $tender->specification }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-uppercase small"
                                        style="color: var(--color-text-muted);">Budget</label>
                                    <input type="number" name="budget" class="form-control auth-input" min="0"
                                        step="0.01" value="{{ $tender->budget }}" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-uppercase small"
                                            style="color: var(--color-text-muted);">Registrasi Mulai</label>
                                        <input type="datetime-local" name="registration_start"
                                            class="form-control auth-input"
                                            value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->registration_start)->format('Y-m-d\TH:i') : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-uppercase small"
                                            style="color: var(--color-text-muted);">Registrasi Selesai</label>
                                        <input type="datetime-local" name="registration_end"
                                            class="form-control auth-input"
                                            value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->registration_end)->format('Y-m-d\TH:i') : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-uppercase small"
                                            style="color: var(--color-text-muted);">Aanwijzing</label>
                                        <input type="datetime-local" name="aanwijzing_at" class="form-control auth-input"
                                            value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->aanwijzing_at)->format('Y-m-d\TH:i') : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-uppercase small"
                                            style="color: var(--color-text-muted);">Bidding Mulai</label>
                                        <input type="datetime-local" name="bidding_start" class="form-control auth-input"
                                            value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_start)->format('Y-m-d\TH:i') : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-uppercase small"
                                            style="color: var(--color-text-muted);">Bidding Selesai</label>
                                        <input type="datetime-local" name="bidding_end" class="form-control auth-input"
                                            value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('Y-m-d\TH:i') : '' }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                                <button type="button" class="btn btn-outline-action"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary-action">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card card-custom border-0 shadow-sm"
            style="background: var(--color-white); border-radius: var(--radius-card);">
            <div class="card-body p-5 text-center">
                <i class="fa-solid fa-folder-open display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
                <p class="fw-bold text-muted mb-0">Belum ada paket tender yang dibuat.</p>
            </div>
        </div>
    @endif

    {{-- MODAL BUAT TENDER --}}
    <div class="modal fade" id="createTenderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card); overflow: hidden;">
                <div class="modal-header border-0 p-4" style="background-color: var(--color-surface);">
                    <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">Formulir Rencana Tender Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.procurement.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small"
                                style="color: var(--color-text-muted);">Nama / Paket Pengadaan</label>
                            <input type="text" name="title" class="form-control auth-input"
                                placeholder="Contoh: Pengadaan Laptop Divisi IT 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small"
                                style="color: var(--color-text-muted);">Deskripsi</label>
                            <textarea name="description" class="form-control auth-input" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small"
                                style="color: var(--color-text-muted);">Spesifikasi</label>
                            <textarea name="specification" class="form-control auth-input" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small"
                                style="color: var(--color-text-muted);">Budget</label>
                            <input type="number" name="budget" class="form-control auth-input" min="0"
                                step="0.01" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase small"
                                    style="color: var(--color-text-muted);">Registrasi Mulai</label>
                                <input type="datetime-local" name="registration_start" class="form-control auth-input"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase small"
                                    style="color: var(--color-text-muted);">Registrasi Selesai</label>
                                <input type="datetime-local" name="registration_end" class="form-control auth-input"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase small"
                                    style="color: var(--color-text-muted);">Aanwijzing</label>
                                <input type="datetime-local" name="aanwijzing_at" class="form-control auth-input"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase small"
                                    style="color: var(--color-text-muted);">Bidding Mulai</label>
                                <input type="datetime-local" name="bidding_start" class="form-control auth-input"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase small"
                                    style="color: var(--color-text-muted);">Bidding Selesai</label>
                                <input type="datetime-local" name="bidding_end" class="form-control auth-input" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                        <button type="button" class="btn btn-outline-action" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary-action">Rilis Rencana</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
