@extends('layouts.admin')
@section('title', 'Vendor Management')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Vendor[] $vendors */
@endphp

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Data Rekanan Vendor</h4>
    <p class="text-muted mb-0">Review berkas, profile komparatif, dan penentuan status verifikasi</p>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #fffaf5;">
                    <tr>
                        <th class="text-muted border-0">Nama Perusahaan</th>
                        <th class="text-muted border-0">Email Bisnis</th>
                        <th class="text-muted border-0">Status</th>
                        <th class="text-muted border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors ?? [] as $v)
                    <tr>
                        <td class="fw-bold">{{ $v->name }}</td>
                        <td class="text-muted">{{ $v->email }}</td>
                        <td>
                            <span class="badge px-3 py-2 rounded-pill bg-light text-dark border">
                                {{ ucfirst($v->status ?? 'Pending') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light text-secondary fw-semibold border" data-bs-toggle="modal" data-bs-target="#vendorModal{{ $v->id }}">
                                <i class="fa-solid fa-folder-open me-1"></i> Periksa Profile
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Data vendor kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($vendors ?? [] as $v)
<div class="modal fade" id="vendorModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="backface-visibility: hidden; transform: translateZ(0);">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4" style="background-color: #fffaf5;">
                <h5 class="modal-title fw-bold">Detail Profil: {{ $v->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/vendors/{{ $v->id }}/update-status" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Nomor Pokok Wajib Pajak (NPWP)</label>
                            <span class="fw-semibold">{{ $v->npwp ?? 'Belum mengisi NPWP' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Alamat Operasional</label>
                            <span class="fw-semibold">{{ $v->address ?? 'Belum mengisi Alamat' }}</span>
                        </div>
                    </div>
                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                        <label class="form-label fw-bold mb-2">Ubah Status Penilaian Vendor</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ ($v->status ?? '') == 'pending' ? 'selected' : '' }}>Pending (Masa Review)</option>
                            <option value="verified" {{ ($v->status ?? '') == 'verified' ? 'selected' : '' }}>Terverifikasi (Aktif)</option>
                            <option value="blacklist" {{ ($v->status ?? '') == 'blacklist' ? 'selected' : '' }}>Blacklist (Blokir)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn text-white" style="background-color: #fe81d4;">Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection