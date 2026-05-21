@extends('home')
@section('title', 'Procurement / Tender')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $tenders */
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Daftar Pengadaan aktif</h4>
    </div>
    <button class="btn text-white px-4 py-2" style="background-color: #fe81d4; border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#createTenderModal">
        <i class="fa-solid fa-plus me-2"></i>Buat Tender Baru
    </button>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        </div>
</div>

<div class="modal fade" id="createTenderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4" style="background-color: #fffaf5;">
                <h5 class="modal-title fw-bold">Formulir Rencana Tender Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/procurement/store" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Nama / Paket Pengadaan</label>
                        <input type="text" name="title" class="form-control form-control-lg text-dark" placeholder="Contoh: Pengadaan Laptop Divisi IT 2026" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #fe81d4;">Rilis Rencana Tender</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection