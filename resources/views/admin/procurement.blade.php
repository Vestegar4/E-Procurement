@extends('layouts.admin')
@section('title', 'Procurement / Tender')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $tenders */
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Daftar Pengadaan Aktif</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola pembuatan dan publikasi paket tender</p>
    </div>
    <button class="btn btn-primary-action" data-bs-toggle="modal" data-bs-target="#createTenderModal">
        <i class="fa-solid fa-plus me-2" style="color: var(--color-accent);"></i> Buat Tender Baru
    </button>
</div>

<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-5 text-center">
        <i class="fa-solid fa-folder-open display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
        <p class="fw-bold text-muted mb-0">Belum ada paket tender yang dibuat.</p>
    </div>
</div>

{{-- MODAL BUAT TENDER --}}
<div class="modal fade" id="createTenderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card); overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background-color: var(--color-surface);">
                <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">Formulir Rencana Tender Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/procurement/store" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Nama / Paket Pengadaan</label>
                        <input type="text" name="title" class="form-control auth-input" placeholder="Contoh: Pengadaan Laptop Divisi IT 2026" required>
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