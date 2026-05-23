@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4">Profil Perusahaan</h5>
            <form>
                <div class="mb-3">
                    <label class="form-label text-muted">Nama Instansi</label>
                    <input type="text" class="form-control" value="PT. Solusi Digital">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Email Notifikasi Sistem</label>
                    <input type="email" class="form-control" value="admin@procurement.com">
                </div>
                <button type="button" class="btn text-white mt-3" style="background-color: #fe81d4;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection