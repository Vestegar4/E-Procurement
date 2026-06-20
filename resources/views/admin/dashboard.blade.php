@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Tender[] $latestTenders */
@endphp

<div class="container-fluid p-0">

    {{-- SECTION 1: METRIC CARD RINGKASAN DATA (RESPONSIF ALL DEVICE) --}}
    <div class="row g-4 mb-5">

        {{-- Card 1: Total Vendor --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Total Vendor</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $vendorCount ?? 0 }}
                        </h2>
                    </div>
                    {{-- flex-shrink-0 memastikan lingkaran ikon tidak peyang di layar HP --}}
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-users fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Tender Aktif --}}
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Total Tender Aktif</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $tenderCount ?? 0 }}
                        </h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-file-contract fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Purchase Orders (PO) --}}
        <div class="col-12 col-xl-4">
            <div class="card card-custom p-4 border-0 position-relative overflow-hidden h-100 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="pe-3">
                        <p class="text-uppercase fw-bold small mb-2" style="color: var(--color-text-muted); letter-spacing: 0.05em; font-size: 0.85rem;">Purchase Orders (PO)</p>
                        <h2 class="m-0" style="color: var(--color-text-main); font-weight: 800; font-size: clamp(2rem, 5vw, 2.5rem); letter-spacing: -0.02em;">
                            {{ $purchaseOrderCount ?? 0 }}
                        </h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="background-color: var(--color-primary); width: 56px; height: 56px;">
                        <i class="fa-solid fa-file-invoice-dollar fa-xl" style="color: var(--color-accent-bright);"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SECTION 2: WRAPPER TAB AKTIVITAS & CHAT (MENGGANTIKAN TABEL LAMA) --}}
    <div class="card card-custom border-0 shadow-sm mb-5" style="background: var(--color-white); border-radius: var(--radius-card, 16px); overflow: hidden;">
        
        {{-- NAV TABS HEADER --}}
        <div class="card-header bg-white border-bottom pt-4 pb-0 px-4">
            <ul class="nav nav-tabs border-0" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark border-0 border-bottom border-3 border-primary bg-transparent py-3" 
                        id="pengadaan-tab" data-bs-toggle="tab" data-bs-target="#pengadaan-pane" 
                        type="button" role="tab" aria-controls="pengadaan-pane" aria-selected="true" style="font-size: 1.05rem;">
                        <i class="fa-solid fa-folder-open me-2 text-primary"></i> Aktivitas Pengadaan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-muted border-0 border-bottom border-3 border-transparent bg-transparent py-3" 
                        id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat-pane" 
                        type="button" role="tab" aria-controls="chat-pane" aria-selected="false" style="font-size: 1.05rem;">
                        <i class="fa-solid fa-headset me-2 text-secondary"></i> Chat Customer Service
                    </button>
                </li>
            </ul>
        </div>

        {{-- TAB CONTENT --}}
        <div class="card-body p-0">
            <div class="tab-content" id="dashboardTabsContent">
                
                {{-- TAB 1: TABEL PENGADAAN (KODE TABEL ASLI ANDA) --}}
                <div class="tab-pane fade show active" id="pengadaan-pane" role="tabpanel" aria-labelledby="pengadaan-tab" tabindex="0">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0" style="color: var(--color-text-main); letter-spacing: -0.01em;">Aktivitas Pengadaan Terbaru</h5>
                            <span class="badge bg-light text-dark px-3 py-2 border fw-semibold" style="border-radius: 20px; font-size: 0.8rem;">Sistem Master Realtime</span>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">ID Paket</th>
                                        <th style="width: 50%;">Nama / Deskripsi Paket Tender</th>
                                        <th style="width: 20%;">Status Sistem</th>
                                        <th style="width: 15%;">Tanggal Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestTenders ?? [] as $tender)
                                    <tr>
                                        <td class="fw-bold" style="color: var(--color-text-muted);">#{{ $tender->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 36px; height: 36px; background: var(--color-surface);">
                                                    <i class="fa-solid fa-briefcase small" style="color: var(--color-primary);"></i>
                                                </div>
                                                <span class="fw-bold" style="color: var(--color-text-main); font-size: 1.05rem;">{{ $tender->title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($tender->result || $tender->status == 'finished')
                                            <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Finished</span>
                                            @elseif($tender->status == 'published' || $tender->status == 'open' || $tender->status == 'bidding')
                                            <span class="badge badge-pastel-success rounded-pill px-3 py-2">Active</span>
                                            @elseif($tender->status == 'closed')
                                            <span class="badge badge-pastel-danger rounded-pill px-3 py-2">Closed</span>
                                            @else
                                            <span class="badge badge-pastel-warning rounded-pill px-3 py-2">Draft / Pending</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--color-text-muted); font-weight: 600;">
                                            <i class="fa-regular fa-calendar-check me-1 small"></i>
                                            {{ $tender->created_at?->format('d M Y') ?? 'Belum ada tanggal' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5" style="color: var(--color-text-muted);">
                                            <div class="mb-3 opacity-50">
                                                <i class="fa-solid fa-folder-open display-5" style="color: var(--color-primary);"></i>
                                            </div>
                                            <p class="fw-bold mb-0" style="font-size: 1.1rem;">Belum ada berkas tender yang tersimpan dalam sistem.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: CHAT CUSTOMER SERVICE (SPLIT VIEW) --}}
                <div class="tab-pane fade" id="chat-pane" role="tabpanel" aria-labelledby="chat-tab" tabindex="0">
                    <div class="row g-0" style="min-height: 500px;">
                        
                        {{-- SISI KIRI: LIST VENDOR --}}
                        <div class="col-md-4 col-lg-3 border-end" style="background-color: var(--color-surface); max-height: 500px; overflow-y: auto;">
                            <div class="p-3 border-bottom position-sticky top-0 bg-light" style="z-index: 10;">
                                <input type="text" class="form-control auth-input" placeholder="Cari percakapan...">
                            </div>
                            
                            <div class="list-group list-group-flush">
                                {{-- Contoh Item Vendor Aktif --}}
                                <button type="button" class="list-group-item list-group-item-action p-3 border-bottom active" style="background-color: var(--color-primary); color: white;">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <strong class="text-truncate">PT. Digital Solusi</strong>
                                        <small style="font-size: 0.7rem;">10:42</small>
                                    </div>
                                    <small class="d-block text-truncate" style="opacity: 0.8;">Apakah dokumen NPWP harus legalisir?</small>
                                </button>

                                {{-- Contoh Item Vendor Pasif --}}
                                <button type="button" class="list-group-item list-group-item-action p-3 border-bottom bg-transparent">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <strong class="text-truncate text-dark">CV. Bangun Karya</strong>
                                        <small class="text-muted" style="font-size: 0.7rem;">Kemarin</small>
                                    </div>
                                    <small class="d-block text-muted text-truncate">Terima kasih atas informasinya min.</small>
                                </button>
                            </div>
                        </div>

                        {{-- SISI KANAN: RUANG CHAT & FORM BALAS --}}
                        <div class="col-md-8 col-lg-9 d-flex flex-column" style="background-color: var(--color-white); max-height: 500px;">
                            
                            {{-- Chat Header --}}
                            <div class="p-3 border-bottom d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background-color: var(--color-accent);">
                                    PT
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: var(--color-text-main);">PT. Digital Solusi</h6>
                                    <small class="text-success fw-medium"><i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i>Sedang aktif</small>
                                </div>
                            </div>

                            {{-- Chat Messages Area --}}
                            <div class="flex-grow-1 p-4 overflow-auto" style="background-color: #f8fafc;">
                                {{-- Bubble Kiri (Vendor) --}}
                                <div class="d-flex justify-content-start mb-3">
                                    <div class="p-3 shadow-sm" style="background-color: white; border-radius: 16px 16px 16px 0; max-width: 75%; border: 1px solid var(--color-border);">
                                        <p class="mb-1 text-dark small">Selamat pagi admin, untuk paket tender IT Infrastruktur, apakah dokumen NPWP harus dilegalisir basah atau cukup scan PDF?</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">10:42 AM</small>
                                    </div>
                                </div>

                                {{-- Bubble Kanan (Admin/Anda) --}}
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="p-3 shadow-sm text-white" style="background-color: var(--color-primary); border-radius: 16px 16px 0 16px; max-width: 75%;">
                                        <p class="mb-1 small">Selamat pagi. Cukup melampirkan hasil scan PDF berwarna dari NPWP asli perusahaan Anda.</p>
                                        <small style="font-size: 0.7rem; opacity: 0.8;">10:45 AM <i class="fa-solid fa-check-double ms-1"></i></small>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Input Balasan --}}
                            <div class="p-3 border-top bg-white">
                                <form action="#" method="POST" onsubmit="event.preventDefault();">
                                    <div class="input-group">
                                        <button class="btn btn-light border text-muted px-3" type="button"><i class="fa-solid fa-paperclip"></i></button>
                                        <input type="text" class="form-control border auth-input" placeholder="Ketik balasan pesan di sini..." required>
                                        <button class="btn btn-primary-action px-4" type="submit" style="background-color: var(--color-primary); color: white;">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll('#dashboardTabs .nav-link');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Hapus warna dan garis aktif dari semua tab
                tabs.forEach(t => {
                    t.classList.remove('text-dark', 'border-primary');
                    t.classList.add('text-muted', 'border-transparent');
                });
                
                // Tambahkan warna dan garis aktif ke tab yang diklik
                this.classList.remove('text-muted', 'border-transparent');
                this.classList.add('text-dark', 'border-primary');
            });
        });
    });
</script>
@endpush