@extends('layouts.admin')
@section('title', 'Procurement / Tender')

@section('content')
    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Daftar Pengadaan Aktif</h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola pembuatan dan publikasi paket pekerjaan</p>
        </div>
        <button class="btn btn-primary-action shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#createTenderModal">
            <i class="fa-solid fa-plus me-2"></i> Buat Tender Baru
        </button>
    </div>

    {{-- KOTAK FILTER & SEARCH PENGADAAN --}}
    <div class="card card-custom border-0 shadow-sm mb-4" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
        <div class="card-body p-3">
            <form action="{{ route('admin.procurement') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Cari ID atau Nama Paket Tender lalu enter..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{-- Fitur Auto-Submit pada Dropdown --}}
                        <select name="status" class="form-select auth-input" onchange="this.form.submit()">
                            <option value="">Semua Status Tender</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Belum Rilis)</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open (Pendaftaran Dibuka)</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed (Selesai/Tutup)</option>
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

    {{-- KONTEN TABEL DATA TENDER --}}
    @if (isset($tenders) && $tenders->count() > 0)
        <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: var(--color-surface);">
                            <tr>
                                <th class="text-center" style="width: 5%;">No.</th>
                                <th style="width: 35%;">Nama Paket Pengadaan</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 25%;">Timeline Jadwal (WIB)</th>
                                <th class="text-center" style="width: 20%;">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenders as $tender)
                                <tr>
                                    <td class="fw-bold text-center" style="color: var(--color-text-muted);">
                                        {{ $tenders->firstItem() + $loop->index }}
                                    </td>
                                    
                                    <td>
                                        <h6 class="fw-bold mb-1" style="color: var(--color-text-main);">{{ $tender->title }}</h6>
                                        <div class="text-muted small text-truncate d-inline-block" style="max-width: 280px;">
                                            {{ $tender->description }}
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @if ($tender->status === 'open')
                                            <span class="badge badge-pastel-success rounded-pill px-3 py-2">Open / Aktif</span>
                                        @elseif ($tender->status === 'aanwijzing')
                                            <span class="badge badge-pastel-warning rounded-pill px-3 py-2" style="background-color: var(--color-warning-bg); color: var(--color-warning-border);">Tanya Jawab</span>
                                        @elseif ($tender->status === 'bidding')
                                            <span class="badge badge-pastel-warning rounded-pill px-3 py-2" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb;">Masa Bidding</span>
                                        @elseif ($tender->status === 'closed')
                                            <span class="badge badge-pastel-danger rounded-pill px-3 py-2" style="background-color: var(--color-danger-bg); color: var(--color-danger-text);">Ditutup</span>
                                        @elseif ($tender->status === 'finished')
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">Selesai</span>
                                        @else
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">{{ ucfirst($tender->status) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($tender->timeline)
                                            <div class="small fw-medium" style="color: var(--color-text-muted); line-height: 1.6;">
                                                <div><i class="fa-solid fa-users-viewfinder me-1" style="color: var(--color-warning-border);"></i> Tanya Jawab: <strong>{{ \Carbon\Carbon::parse($tender->timeline->aanwijzing_at)->format('d M Y') }}</strong> - <strong>{{ \Carbon\Carbon::parse($tender->timeline->aanwijzing_at)->format('H:i') }}</strong></div>
                                                <div class="mt-1"><i class="fa-regular fa-clock me-1" style="color: var(--color-primary);"></i> Batas Akhir: <strong>{{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('d M Y') }}</strong> - <strong>{{ \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('H:i') }}</strong></div>
                                            </div>
                                        @else
                                            <span class="small text-muted fst-italic">Jadwal belum dikonfigurasi</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle shadow-sm px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: var(--color-surface); border: 1px solid var(--color-border); color: var(--color-text-main); font-weight: 600; border-radius: 6px;">
                                                <i class="fa-solid fa-gear me-1" style="color: var(--color-primary);"></i> Kelola
                                            </button>
                                            
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 8px; font-size: 0.85rem; min-width: 200px;">
                                                <li>
                                                    <a class="dropdown-item py-2 fw-medium" href="{{ route('admin.tenders.bids', $tender->id) }}">
                                                        <i class="fa-solid fa-file-contract text-center me-2" style="width: 20px; color: var(--color-primary);"></i> Cek Penawaran (Bids)
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item py-2 fw-medium" href="{{ route('admin.tenders.aanwijzing', $tender->id) }}">
                                                        <i class="fa-solid fa-comments text-center me-2" style="width: 20px; color: var(--color-accent);"></i> Forum Tanya Jawab
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item py-2 fw-medium" type="button" data-bs-toggle="modal" data-bs-target="#editTenderModal-{{ $tender->id }}">
                                                        <i class="fa-solid fa-pen-to-square text-center me-2" style="width: 20px; color: #64748b;"></i> Edit Data Tender
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- ================================================================= --}}
                                {{-- MODAL EDIT TENDER --}}
                                {{-- ================================================================= --}}
                                <div class="modal fade" id="editTenderModal-{{ $tender->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card, 16px); overflow: hidden;">
                                            <div class="modal-header border-bottom p-4" style="background-color: var(--color-surface);">
                                                <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">
                                                    <i class="fa-solid fa-pen-to-square me-2" style="color: var(--color-accent);"></i> Edit Paket Tender
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <form action="{{ route('admin.tenders.update', $tender->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Nama Paket Pekerjaan</label>
                                                        <input type="text" name="title" class="form-control auth-input" value="{{ $tender->title }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Deskripsi Singkat</label>
                                                        <textarea name="description" class="form-control auth-input" rows="3" required>{{ $tender->description }}</textarea>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Ubah Status Tahapan</label>
                                                        <select name="status" class="form-select auth-input">
                                                            <option value="open" {{ $tender->status === 'open' ? 'selected' : '' }}>Open (Buka Pendaftaran)</option>
                                                            <option value="aanwijzing" {{ $tender->status === 'aanwijzing' ? 'selected' : '' }}>Tanya Jawab (Aanwijzing)</option>
                                                            <option value="bidding" {{ $tender->status === 'bidding' ? 'selected' : '' }}>Bidding (Masa Terima Penawaran)</option>
                                                            <option value="closed" {{ $tender->status === 'closed' ? 'selected' : '' }}>Closed (Tutup & Evaluasi)</option>
                                                            <option value="finished" {{ $tender->status === 'finished' ? 'selected' : '' }}>Finished (Pekerjaan Selesai)</option>
                                                        </select>
                                                    </div>

                                                    {{-- SECTION EDIT JADWAL (INPUT JAM TEXT MANUAL) --}}
                                                    <div class="p-3 rounded-3" style="background-color: var(--color-surface); border: 1px dashed var(--color-border);">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="fw-bold mb-0 small text-uppercase" style="color: var(--color-primary); letter-spacing: 0.05em;">
                                                                <i class="fa-regular fa-calendar-days me-2" style="color: var(--color-accent);"></i> Pengaturan Jadwal
                                                            </h6>
                                                            <span class="badge bg-info text-dark rounded-pill small"><i class="fa-solid fa-keyboard me-1"></i>Ketik Angka Saja</span>
                                                        </div>
                                                        
                                                        <div class="row g-3 mb-3 pb-3 border-bottom" style="border-bottom-style: dashed !important;">
                                                            <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-comments me-2 text-muted"></i> 1. Tahap Forum Tanya Jawab</span></div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                                                                <input type="date" name="aanwijzing_date" class="form-control auth-input" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->aanwijzing_at)->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 14:30)</label>
                                                                <input type="text" name="aanwijzing_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->aanwijzing_at)->format('H:i') : '' }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="row g-3 mb-3 pb-3 border-bottom" style="border-bottom-style: dashed !important;">
                                                            <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-play me-2 text-muted"></i> 2. Pembukaan Penerimaan Harga</span></div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                                                                <input type="date" name="bidding_start_date" class="form-control auth-input" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_start)->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 09:00)</label>
                                                                <input type="text" name="bidding_start_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_start)->format('H:i') : '' }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="row g-3">
                                                            <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-flag-checkered me-2 text-muted"></i> 3. Penutupan Penerimaan Harga</span></div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Tanggal Penutupan</label>
                                                                <input type="date" name="bidding_end_date" class="form-control auth-input" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('Y-m-d') : '' }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 16:00)</label>
                                                                <input type="text" name="bidding_end_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" value="{{ $tender->timeline ? \Carbon\Carbon::parse($tender->timeline->bidding_end)->format('H:i') : '' }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer border-top p-4 pt-3 justify-content-between" style="background-color: #fafafa;">
                                                    <button type="button" class="btn fw-bold" style="color: var(--color-text-muted);" data-bs-dismiss="modal">Batalkan</button>
                                                    <button type="submit" class="btn btn-primary-action px-4 shadow-sm">
                                                        Simpan Perubahan <i class="fa-solid fa-floppy-disk ms-2"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- END MODAL EDIT --}}
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if ($tenders->hasPages())
                    <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                        {{ $tenders->links('components.pagination') }}
                    </div>
                @endif
                
            </div>
        </div>
    @else
        <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
            <div class="card-body p-5 text-center">
                <i class="fa-solid fa-boxes-packing display-4 mb-3" style="color: var(--color-primary); opacity: 0.2;"></i>
                <h5 class="fw-bold" style="color: var(--color-text-main);">Belum Ada Paket Pengadaan</h5>
                <p class="text-muted mb-4">Mulai kelola pengadaan barang dan jasa dengan membuat tender pertama Anda.</p>
                <button class="btn btn-primary-action shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#createTenderModal">
                    <i class="fa-solid fa-plus me-2"></i> Buat Tender Sekarang
                </button>
            </div>
        </div>
    @endif

    {{-- ================================================================= --}}
    {{-- MODAL CREATE TENDER BARU --}}
    {{-- ================================================================= --}}
    <div class="modal fade" id="createTenderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-card, 16px); overflow: hidden;">
                <div class="modal-header border-bottom p-4" style="background-color: var(--color-surface);">
                    <h5 class="modal-title fw-bold" style="color: var(--color-text-main);">
                        <i class="fa-solid fa-folder-plus me-2" style="color: var(--color-accent);"></i> Buat Paket Tender Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('admin.tenders.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Nama Paket Pekerjaan</label>
                            <input type="text" name="title" class="form-control auth-input" placeholder="Contoh: Pengadaan Server Infrastruktur IT 2026" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control auth-input" rows="3" placeholder="Jelaskan secara garis besar ruang lingkup pekerjaan..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Status Awal Rilis</label>
                            <select name="status" class="form-select auth-input">
                                <option value="open">Open (Buka Pendaftaran)</option>
                                <option value="aanwijzing">Aanwijzing (Masa Tanya Jawab)</option>
                                <option value="bidding">Bidding (Masa Terima Penawaran Harga)</option>
                            </select>
                        </div>

                        {{-- SECTION JADWAL TIMELINE (INPUT JAM TEXT MANUAL) --}}
                        <div class="p-3 rounded-3" style="background-color: var(--color-surface); border: 1px dashed var(--color-border);">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 small text-uppercase" style="color: var(--color-primary); letter-spacing: 0.05em;">
                                    <i class="fa-regular fa-calendar-days me-2" style="color: var(--color-accent);"></i> Pengaturan Jadwal
                                </h6>
                                <span class="badge bg-info text-dark rounded-pill small"><i class="fa-solid fa-keyboard me-1"></i>Ketik Angka Saja</span>
                            </div>
                            
                            <div class="row g-3 mb-3 pb-3 border-bottom" style="border-bottom-style: dashed !important;">
                                <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-comments me-2 text-muted"></i> 1. Tahap Forum Aanwijzing</span></div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                                    <input type="date" name="aanwijzing_date" class="form-control auth-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 14:30)</label>
                                    <input type="text" name="aanwijzing_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3 pb-3 border-bottom" style="border-bottom-style: dashed !important;">
                                <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-play me-2 text-muted"></i> 2. Pembukaan Penerimaan Harga</span></div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                                    <input type="date" name="bidding_start_date" class="form-control auth-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 09:00)</label>
                                    <input type="text" name="bidding_start_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12"><span class="fw-bold small text-dark"><i class="fa-solid fa-flag-checkered me-2 text-muted"></i> 3. Penutupan Penerimaan Harga</span></div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Tanggal Penutupan</label>
                                    <input type="date" name="bidding_end_date" class="form-control auth-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Ketik Jam (Contoh: 16:00)</label>
                                    <input type="text" name="bidding_end_time" class="form-control auth-input time-formatter" placeholder="00:00" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-top p-4 pt-3 justify-content-between" style="background-color: #fafafa;">
                        <button type="button" class="btn fw-bold" style="color: var(--color-text-muted);" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary-action px-4 shadow-sm">
                            Rilis Rencana Tender <i class="fa-solid fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SCRIPT: SWEETALERT TOAST NOTIFICATION (SOLUSI NOTIF DOUBLE) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: 'Pastikan form tanggal dan jam terisi dengan benar.',
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            });
        </script>
    @endif

    {{-- SCRIPT: AUTO-FORMATTER JAM --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const timeInputs = document.querySelectorAll('.time-formatter');
            
            timeInputs.forEach(input => {
                input.addEventListener('input', function (e) {
                    let val = this.value.replace(/\D/g, ''); 
                    if (val.length > 2) {
                        this.value = val.substring(0, 2) + ':' + val.substring(2, 4);
                    } else {
                        this.value = val;
                    }
                });
            });
        });
    </script>
@endpush