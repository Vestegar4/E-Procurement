@extends('layouts.vendor')

@section('title', 'Dokumen Vendor')

@section('content')

    <div class="container-fluid p-0">

        {{-- PAGE HEADER --}}
        <div class="mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>
                    <h3 class="fw-bold mb-1">
                        Dokumen Vendor
                    </h3>

                    <p class="text-muted mb-0">
                        Kelola dokumen perusahaan untuk proses verifikasi procurement.
                    </p>
                </div>

            </div>

        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- VALIDATION ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-4">

                {{-- UPLOAD CARD --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">
                            Upload Dokumen
                        </h5>

                        <form action="{{ route('vendor.documents.store') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            {{-- DOCUMENT TYPE --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Jenis Dokumen
                                </label>

                                <select name="document_type" class="form-select" required>

                                    <option value="">
                                        -- Pilih Dokumen --
                                    </option>

                                    <option value="npwp">
                                        NPWP
                                    </option>

                                    <option value="nib">
                                        NIB
                                    </option>

                                    <option value="siup">
                                        SIUP
                                    </option>

                                    <option value="company_profile">
                                        Company Profile
                                    </option>

                                    <option value="domicile_letter">
                                        Surat Domisili
                                    </option>

                                    <option value="other">
                                        Lainnya
                                    </option>

                                </select>

                            </div>

                            {{-- FILE --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Upload PDF
                                </label>

                                <input type="file" name="document_file" class="form-control" accept=".pdf" required>

                                <small class="text-muted">
                                    Maksimal ukuran file 5MB.
                                </small>

                            </div>

                            {{-- BUTTON --}}
                            <button type="submit" class="btn btn-primary w-100 rounded-3">

                                Upload Dokumen

                            </button>

                        </form>

                    </div>

                </div>

                {{-- VERIFICATION STATUS --}}
                <div class="card border-0 shadow-sm rounded-4 mt-4">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            Status Verifikasi
                        </h5>

                        @php
                            $approvedCount = $documents->where('status', 'approved')->count();
                        @endphp

                        @if ($approvedCount >= 3)
                            <div class="alert alert-success mb-0">

                                Dokumen perusahaan telah diverifikasi.

                            </div>
                        @else
                            <div class="alert alert-warning mb-0">

                                Lengkapi dokumen perusahaan untuk mendapatkan persetujuan admin.

                            </div>
                        @endif

                    </div>

                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h5 class="fw-bold mb-0">
                                Daftar Dokumen
                            </h5>

                        </div>

                        @if ($documents->count() > 0)

                            <div class="table-responsive">

                                <table class="table align-middle">

                                    <thead>

                                        <tr>

                                            <th>Jenis Dokumen</th>
                                            <th>Status</th>
                                            <th>Upload</th>
                                            <th width="180">Aksi</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($documents as $document)
                                            <tr>

                                                {{-- DOCUMENT TYPE --}}
                                                <td>

                                                    <div class="fw-semibold">

                                                        @switch($document->document_type)
                                                            @case('npwp')
                                                                NPWP
                                                            @break

                                                            @case('nib')
                                                                NIB
                                                            @break

                                                            @case('siup')
                                                                SIUP
                                                            @break

                                                            @case('company_profile')
                                                                Company Profile
                                                            @break

                                                            @case('domicile_letter')
                                                                Surat Domisili
                                                            @break

                                                            @default
                                                                Lainnya
                                                        @endswitch

                                                    </div>

                                                    <small class="text-muted">
                                                        {{ $document->document_name }}
                                                    </small>

                                                </td>

                                                {{-- STATUS --}}
                                                <td>

                                                    @if ($document->status == 'approved')
                                                        <span class="badge bg-success px-3 py-2">
                                                            Approved
                                                        </span>
                                                    @elseif($document->status == 'rejected')
                                                        <span class="badge bg-danger px-3 py-2">
                                                            Rejected
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark px-3 py-2">
                                                            Pending
                                                        </span>
                                                    @endif

                                                </td>

                                                {{-- UPLOAD DATE --}}
                                                <td>

                                                    {{ $document->uploaded_at ? $document->uploaded_at->format('d M Y') : '-' }}

                                                </td>

                                                {{-- ACTION --}}
                                                <td>

                                                    <div class="d-flex gap-2">

                                                        {{-- DOWNLOAD --}}
                                                        <a href="{{ route('vendor.documents.download', $document->id) }}"
                                                            class="btn btn-sm btn-outline-primary rounded-3">

                                                            Download

                                                        </a>

                                                        {{-- DELETE --}}
                                                        <form
                                                            action="{{ route('vendor.documents.destroy', $document->id) }}"
                                                            method="POST" onsubmit="return confirm('Hapus dokumen ini?')">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-danger rounded-3">

                                                                Hapus

                                                            </button>

                                                        </form>

                                                    </div>

                                                </td>

                                            </tr>

                                            {{-- NOTES --}}
                                            @if ($document->notes)
                                                <tr>

                                                    <td colspan="4">

                                                        <div class="alert alert-danger py-2 mb-0">

                                                            <strong>Catatan Admin:</strong>
                                                            {{ $document->notes }}

                                                        </div>

                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                            {{-- PAGINATION --}}
                            <div class="mt-4">

                                {{ $documents->links() }}

                            </div>
                        @else
                            {{-- EMPTY STATE --}}
                            <div class="text-center py-5">

                                <h5 class="fw-bold mb-2">
                                    Belum Ada Dokumen
                                </h5>

                                <p class="text-muted mb-0">
                                    Upload dokumen perusahaan untuk memulai proses verifikasi vendor.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
