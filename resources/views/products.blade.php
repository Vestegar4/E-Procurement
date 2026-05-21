@extends('home')
@section('title', 'Produk & Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Master Katalog Barang</h4>
    </div>
    <button class="btn text-white px-4 py-2" style="background-color: #fe81d4; border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-box me-2"></i>Tambah Produk
    </button>
</div>

<div class="card card-custom p-5 text-center">
    <span class="text-muted">Katalog data master barang e-procurement kosong.</span>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4" style="background-color: #fffaf5;">
                <h5 class="modal-title fw-bold">Tambah Item Barang Master</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Produk/Barang</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Kategori</label>
                        <select class="form-select">
                            <option>Infrastruktur & Jaringan</option>
                            <option>Perangkat Keras Komputer</option>
                            <option>Alat Tulis Kantor</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn text-white" style="background-color: #fe81d4;" data-bs-dismiss="modal">Simpan Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection