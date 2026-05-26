@extends('layouts.admin')
@section('title', 'Produk & Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Master Katalog Barang</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Manajemen basis data infrastruktur dan barang</p>
    </div>
    <button class="btn btn-primary-action" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-box me-2" style="color: var(--color-accent);"></i> Tambah Produk
    </button>
</div>

<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-5 text-center">
        <i class="fa-solid fa-boxes-stacked display-4 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
        <p class="fw-bold text-muted mb-0">Katalog data master barang e-procurement masih kosong.</p>
    </div>
</div>

{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card); overflow: hidden;">
            <div class="modal-header border-0 p-4" style="background-color: var(--color-surface);">
                <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">Tambah Item Barang Master</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Nama Produk/Barang</label>
                        <input type="text" class="form-control auth-input" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="mb-3 mt-4">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Kategori</label>
                        <select class="form-select auth-input">
                            <option>Infrastruktur & Jaringan</option>
                            <option>Perangkat Keras Komputer</option>
                            <option>Alat Tulis Kantor</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                    <button type="button" class="btn btn-outline-action" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-primary-action" data-bs-dismiss="modal">Simpan Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection