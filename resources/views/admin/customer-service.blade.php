@extends('layouts.admin')
@section('title', 'Pusat Bantuan & Pengaduan (CS)')

@push('styles')
<style>
    /* KONFIGURASI LAYOUT CHAT MENGGUNAKAN AUREATE THEME */
    .chat-container {
        height: calc(100vh - 180px);
        min-height: 500px;
    }
    
    .chat-sidebar {
        border-right: 1px solid var(--color-border);
        background: var(--color-white);
        border-radius: var(--radius-card, 16px) 0 0 var(--radius-card, 16px);
    }
    
    .chat-main {
        background: var(--color-surface); 
        border-radius: 0 var(--radius-card, 16px) var(--radius-card, 16px) 0;
    }

    /* ITEM DAFTAR VENDOR (LEFT SIDE) */
    .vendor-chat-item {
        border-left: 4px solid transparent;
        transition: all 0.25s ease;
        color: var(--color-text-main);
        background-color: var(--color-white);
    }

    .vendor-chat-item:hover {
        background-color: var(--color-surface);
        text-decoration: none;
    }

    /* STATE AKTIF TEMA AUREATE (NAVY & AMBER) */
    .vendor-chat-item.active {
        background-color: var(--color-surface); 
        border-left-color: var(--color-accent); /* Amber */
        color: var(--color-primary) !important; /* Navy */
    }

    .vendor-chat-item.active .fw-bold {
        color: var(--color-primary) !important;
    }

    /* BUBBLE CHAT (RIGHT SIDE) */
    .chat-bubble {
        max-width: 75%;
        padding: 14px 20px;
        border-radius: 16px;
        font-size: 0.95rem;
        line-height: 1.6;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05); /* Navy shadow lembut */
    }

    .chat-bubble-vendor {
        background-color: var(--color-white);
        color: var(--color-text-main);
        border: 1px solid var(--color-border);
        border-bottom-left-radius: 4px;
    }

    .chat-bubble-admin {
        background-color: var(--color-primary); /* Navy Solid */
        color: var(--color-white);
        border: none;
        border-bottom-right-radius: 4px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
    }

    .chat-input-area {
        background: var(--color-white);
        border-top: 1px solid var(--color-border);
        border-radius: 0 0 var(--radius-card, 16px) 0;
    }
    
    /* TOMBOL KIRIM AMBER/GOLD */
    .btn-send-aureate {
        background-color: var(--color-accent);
        border: 2px solid var(--color-accent);
        color: var(--color-white);
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    .btn-send-aureate:hover {
        background-color: var(--color-accent-bright);
        border-color: var(--color-accent-bright);
        transform: translateY(-2px);
        color: var(--color-white);
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.2);
    }

    /* Scrollbar */
    .scrollable-area {
        overflow-y: auto;
    }
    .scrollable-area::-webkit-scrollbar { width: 6px; }
    .scrollable-area::-webkit-scrollbar-track { background: transparent; }
    .scrollable-area::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 10px; }
    .scrollable-area::-webkit-scrollbar-thumb:hover { background: var(--color-text-muted); }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pusat Bantuan & Pengaduan</h4>
    <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola dan balas tiket pertanyaan dari Vendor</p>
</div>

{{-- BUNGKUSAN UTAMA APLIKASI CHAT --}}
<div class="card border-0 mb-4 shadow-sm" style="border-radius: var(--radius-card, 16px); background: var(--color-white);">
    <div class="row g-0 chat-container">
        
        {{-- BAGIAN KIRI: DAFTAR OBROLAN VENDOR --}}
        <div class="col-md-4 d-flex flex-column chat-sidebar">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--color-border) !important;">
                <h6 class="fw-bold mb-0 text-uppercase" style="color: var(--color-primary); letter-spacing: 0.05em;">Pesan Masuk</h6>
            </div>
            
            <div class="p-3 border-bottom" style="border-color: var(--color-border) !important;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="color: var(--color-text-muted); border-color: var(--color-border);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 px-0 auth-input" placeholder="Cari vendor..." style="border-color: var(--color-border); box-shadow: none;">
                </div>
            </div>

            <div class="scrollable-area flex-grow-1 py-2">
                {{-- Vendor Aktif --}}
                <a href="#" class="vendor-chat-item active d-block p-3 border-bottom text-decoration-none" style="border-color: var(--color-border) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-truncate" style="font-size: 0.95rem;">PT. Artha Konstruksi</span>
                        <small style="color: var(--color-text-muted); font-size: 0.75rem;">10:30</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-truncate small" style="color: var(--color-text-muted);">Terkait pencairan invoice...</span>
                        <span class="badge rounded-pill" style="background-color: var(--color-accent); color: var(--color-white); font-size: 0.7rem;">2</span>
                    </div>
                </a>

                {{-- Vendor Pasif --}}
                <a href="#" class="vendor-chat-item d-block p-3 border-bottom text-decoration-none" style="border-color: var(--color-border) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-truncate" style="color: var(--color-text-main); font-size: 0.95rem;">CV. Global Makmur</span>
                        <small style="color: var(--color-text-muted); font-size: 0.75rem;">Kemarin</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-truncate small" style="color: var(--color-text-muted);">Terima kasih atas konfirmasinya min.</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- BAGIAN KANAN: RUANG OBROLAN (ROOM) --}}
        <div class="col-md-8 d-flex flex-column chat-main">
            {{-- Header Room --}}
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: var(--color-white); border-color: var(--color-border) !important; border-radius: 0 var(--radius-card, 16px) 0 0;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background: rgba(217, 119, 6, 0.1); color: var(--color-accent);">
                        <i class="fa-solid fa-building fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color: var(--color-primary);">PT. Artha Konstruksi</h6>
                        <span class="badge rounded-pill mt-1" style="font-size: 0.7rem; background-color: var(--color-success-bg); color: var(--color-success-text); border: 1px solid var(--color-success-border);">Verified Vendor</span>
                    </div>
                </div>
                <button class="btn btn-sm fw-bold shadow-sm" style="background-color: var(--color-danger-bg); color: var(--color-danger-text); border: 1px solid var(--color-danger-border); border-radius: 8px;">
                    <i class="fa-solid fa-check-double me-1"></i> Selesai
                </button>
            </div>

            {{-- Body Room --}}
            <div class="scrollable-area flex-grow-1 p-4 d-flex flex-column gap-3">
                <div class="text-center w-100 mb-3">
                    <span class="badge border px-3 py-2 shadow-sm" style="background-color: var(--color-white); color: var(--color-text-muted); border-color: var(--color-border) !important; border-radius: 8px;">Hari Ini</span>
                </div>

                {{-- Chat dari Vendor --}}
                <div class="d-flex flex-column align-items-start">
                    <span class="small fw-bold mb-1 ms-1" style="color: var(--color-text-muted);">PT. Artha Konstruksi</span>
                    <div class="chat-bubble chat-bubble-vendor">
                        Selamat Pagi Admin, izin bertanya. Untuk invoice termin 1 pada proyek pengadaan Gedung A apakah sudah bisa diterbitkan? Mengingat progress sudah 30%.
                    </div>
                    <span class="small mt-1 ms-1" style="color: var(--color-text-muted); font-size: 0.75rem;">10:28 AM</span>
                </div>

                <div class="d-flex flex-column align-items-start mt-n2">
                    <div class="chat-bubble chat-bubble-vendor">
                        Kami juga telah mengunggah berkas BAST di sistem.
                    </div>
                    <span class="small mt-1 ms-1" style="color: var(--color-text-muted); font-size: 0.75rem;">10:30 AM</span>
                </div>

                {{-- Chat dari Admin (Kita) --}}
                <div class="d-flex flex-column align-items-end mt-2">
                    <span class="small fw-bold mb-1 me-1" style="color: var(--color-text-muted);">Admin (Anda)</span>
                    <div class="chat-bubble chat-bubble-admin">
                        Selamat Pagi. Baik, kami sudah menerima berkas BAST. Sedang direview oleh tim Pejabat Pembuat Komitmen (PPK). Mohon ditunggu selambatnya 2x24 jam kerja ya pak.
                    </div>
                    <span class="small mt-1 me-1" style="color: var(--color-text-muted); font-size: 0.75rem;">10:45 AM <i class="fa-solid fa-check-double ms-1" style="color: var(--color-accent);"></i></span>
                </div>
            </div>

            {{-- Area Ketik Pesan --}}
            <div class="p-3 chat-input-area">
                <form action="#" method="POST" class="m-0">
                    <div class="input-group align-items-end" style="background: var(--color-white); border-radius: 8px; border: 1px solid var(--color-border); overflow: hidden; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);">
                        <textarea class="form-control border-0 px-3 py-3" rows="1" placeholder="Tulis balasan pesan di sini..." style="resize: none; box-shadow: none; font-size: 0.95rem; color: var(--color-text-main);"></textarea>
                        <button type="button" class="btn bg-white border-0 px-3" style="color: var(--color-text-muted);" title="Lampirkan File">
                            <i class="fa-solid fa-paperclip fs-5"></i>
                        </button>
                        <div class="p-2 bg-white">
                            <button type="submit" class="btn btn-send-aureate px-4 py-2">
                                Kirim <i class="fa-solid fa-paper-plane ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection