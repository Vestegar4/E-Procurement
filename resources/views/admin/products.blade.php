@extends('layouts.admin')
@section('title', 'Produk & Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Master Katalog Barang</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Pantau stok masuk dan keluar inventaris terbaru</p>
    </div>
    <button class="btn btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-box-open me-2"></i> Tambah Barang
    </button>
</div>

{{-- KOTAK FILTER & SEARCH PRODUK --}}
    <div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
        <div class="card-body p-3">
            <form action="{{ route('admin.products') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik nama barang lalu enter..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{-- Fitur Auto-Submit pada Dropdown --}}
                        <select name="category" class="form-select auth-input" onchange="this.form.submit()" style="cursor: pointer;">
                            <option value="">-- Semua Kategori --</option>
                            <option value="Infrastruktur & Jaringan" {{ request('category') == 'Infrastruktur & Jaringan' ? 'selected' : '' }}>Infrastruktur & Jaringan</option>
                            <option value="Perangkat Keras Komputer" {{ request('category') == 'Perangkat Keras Komputer' ? 'selected' : '' }}>Perangkat Keras Komputer</option>
                            <option value="Alat Tulis Kantor" {{ request('category') == 'Alat Tulis Kantor' ? 'selected' : '' }}>Alat Tulis Kantor</option>
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

{{-- TABEL INVENTARIS BARANG --}}
@if(isset($products) && $products->count() > 0)
    <div class="card card-custom p-4 border-0 shadow-sm" style="border-radius: var(--radius-card);">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--color-surface);">
                    <tr>
                        <th width="25%">Nama Barang (Terbaru)</th>
                        <th width="20%">Kategori</th>
                        <th class="text-center" width="15%" style="color: var(--color-success-border);"><i class="fa-solid fa-arrow-turn-down me-1"></i> Masuk (In)</th>
                        <th class="text-center" width="15%" style="color: var(--color-danger-border);"><i class="fa-solid fa-arrow-turn-up me-1"></i> Keluar (Out)</th>
                        <th class="text-center" width="15%">Sisa Stok</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                    <tr>
                        <td>
                            <h6 class="fw-bold mb-1" style="color: var(--color-text-main);">{{ $item->name ?? '-' }}</h6>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ $item->created_at->diffForHumans() }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $item->category ?? '-' }}</span></td>
                        
                        {{-- Kolom Pemantauan Barang Masuk/Keluar --}}
                        <td class="text-center fw-bold text-success">+ {{ $item->stock_in ?? 0 }} Unit</td>
                        <td class="text-center fw-bold text-danger">- {{ $item->stock_out ?? 0 }} Unit</td>
                        
                        <td class="text-center">
                            <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">{{ ($item->stock_in ?? 0) - ($item->stock_out ?? 0) }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-action btn-sm"><i class="fa-solid fa-pen"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@else
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-5 text-center">
            <i class="fa-solid fa-boxes-stacked display-4 mb-3" style="color: var(--color-primary); opacity: 0.2;"></i>
            <h5 class="fw-bold text-muted">Katalog master barang masih kosong.</h5>
            <p class="text-muted small">Tambahkan item untuk memantau trafik barang masuk dan keluar.</p>
        </div>
    </div>
@endif

{{-- MODAL TAMBAH PRODUK (TETAP SAMA) --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm" style="border-radius: var(--radius-card);">
            <div class="modal-header border-bottom p-4" style="background-color: var(--color-surface);">
                <h5 class="modal-title fw-bold" style="color: var(--color-text-main);"><i class="fa-solid fa-box me-2" style="color: var(--color-accent);"></i> Tambah Item Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Nama Produk/Barang</label>
                        <input type="text" name="name" class="form-control auth-input" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Kategori</label>
                        <select name="category" class="form-select auth-input">
                            <option>Infrastruktur & Jaringan</option>
                            <option>Perangkat Keras Komputer</option>
                            <option>Alat Tulis Kantor</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold text-uppercase small text-success">Total Stock Masuk (In)</label>
                            <input type="number" name="stock_in" class="form-control auth-input" placeholder="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-uppercase small text-danger">Total Stock Keluar (Out)</label>
                            <input type="number" name="stock_out" class="form-control auth-input" placeholder="0" value="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-4 pt-3 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary-action px-4 shadow-sm">Simpan Item <i class="fa-solid fa-floppy-disk ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection