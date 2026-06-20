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
        cursor: pointer;
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
@php
// Mengambil data vendor asli dari database
$chatVendors = \App\Models\Vendor::latest()->take(10)->get();
@endphp

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
                @forelse($chatVendors as $vendor)
                    <div id="vendor-item-{{ $vendor->user_id ?? $vendor->id }}" 
                        class="vendor-chat-item d-block p-3 border-bottom {{ $loop->first ? 'active' : '' }}" 
                        onclick="bukaPercakapan('{{ $vendor->user_id ?? $vendor->id }}', '{{ addslashes($vendor->company_name ?? $vendor->name) }}')"
                        style="border-color: var(--color-border) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-truncate vendor-name" style="font-size: 0.95rem;">{{ $vendor->company_name ?? $vendor->name }}</span>
                            <small style="color: var(--color-text-muted); font-size: 0.75rem;">Vendor</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-truncate small" style="color: var(--color-text-muted);">Ketuk untuk melihat pesan...</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted small">Belum ada vendor terdaftar.</div>
                @endforelse
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
                        <h6 id="chatHeaderName" class="fw-bold mb-0" style="color: var(--color-primary);">
                            {{ $chatVendors->count() > 0 ? ($chatVendors->first()->company_name ?? $chatVendors->first()->name) : 'Pilih Vendor' }}
                        </h6>
                        <span class="badge rounded-pill mt-1" style="font-size: 0.7rem; background-color: var(--color-success-bg); color: var(--color-success-text); border: 1px solid var(--color-success-border);">Verified Vendor</span>
                    </div>
                </div>
                <button class="btn btn-sm fw-bold shadow-sm" style="background-color: var(--color-danger-bg); color: var(--color-danger-text); border: 1px solid var(--color-danger-border); border-radius: 8px;">
                    <i class="fa-solid fa-check-double me-1"></i> Selesai
                </button>
            </div>

            {{-- Body Room (Chat Area) --}}
            <div class="scrollable-area flex-grow-1 p-4 d-flex flex-column gap-3" id="chatArea">
                <div class="text-center w-100 mb-3">
                    <span class="badge border px-3 py-2 shadow-sm" style="background-color: var(--color-white); color: var(--color-text-muted); border-color: var(--color-border) !important; border-radius: 8px;">Hari Ini</span>
                </div>

                {{-- Chat Placeholder (Akan hilang jika chat baru dikirim) --}}
                <div class="text-center text-muted my-4 opacity-50" id="emptyChatPlaceholder">
                    <i class="fa-regular fa-message display-6 mb-2"></i>
                    <p class="small">Belum ada riwayat percakapan. Mulai kirim pesan di bawah.</p>
                </div>
            </div>

            {{-- Area Ketik Pesan (Terhubung ke Script AJAX) --}}
            <div class="p-3 chat-input-area">
                <form id="formBalasChat" onsubmit="kirimPesan(event)" class="m-0">
                    @csrf
                    {{-- Input tersembunyi untuk ID Vendor tujuan --}}
                    <input type="hidden" id="receiver_id" name="receiver_id" value="{{ $chatVendors->count() > 0 ? ($chatVendors->first()->user_id ?? $chatVendors->first()->id) : '' }}">

                    <div class="input-group align-items-end" style="background: var(--color-white); border-radius: 8px; border: 1px solid var(--color-border); overflow: hidden; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);">
                        <textarea id="chatMessage" name="message" class="form-control border-0 px-3 py-3" rows="1" placeholder="Tulis balasan pesan di sini..." style="resize: none; box-shadow: none; font-size: 0.95rem; color: var(--color-text-main);" required></textarea>
                        <button type="button" class="btn bg-white border-0 px-3" style="color: var(--color-text-muted);" title="Lampirkan File">
                            <i class="fa-solid fa-paperclip fs-5"></i>
                        </button>
                        <div class="p-2 bg-white">
                            <button type="submit" id="btnKirimChat" class="btn btn-send-aureate px-4 py-2">
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

@push('scripts')
<script>
    // 1. FUNGSI UNTUK MENGGANTI VENDOR DI SEBELAH KIRI
    function bukaPercakapan(vendorId, vendorName) {
        // Set ID ke form input
        document.getElementById('receiver_id').value = vendorId;
        
        // Ubah Header Kanan
        document.getElementById('chatHeaderName').innerText = vendorName;
        
        // Hapus class 'active' dari semua vendor
        document.querySelectorAll('.vendor-chat-item').forEach(el => {
            el.classList.remove('active');
        });
        
        // Tambahkan class 'active' ke vendor yang diklik
        let activeEl = document.getElementById('vendor-item-' + vendorId);
        if(activeEl) {
            activeEl.classList.add('active');
        }

        // Tampilkan loading sebentar di layar chat
        let chatArea = document.getElementById('chatArea');
        chatArea.innerHTML = `
            <div class="text-center text-muted my-5">
                <i class="fa-solid fa-spinner fa-spin mb-2 fs-3 opacity-50"></i>
                <p class="small">Memuat riwayat chat dengan ${vendorName}...</p>
            </div>
        `;

        // Hapus loading (Simulasi data kosong)
        setTimeout(() => {
            chatArea.innerHTML = `
                <div class="text-center w-100 mb-3">
                    <span class="badge border px-3 py-2 shadow-sm" style="background-color: var(--color-white); color: var(--color-text-muted); border-color: var(--color-border) !important; border-radius: 8px;">Hari Ini</span>
                </div>
            `;
        }, 500);
    }

    // 2. FUNGSI AJAX UNTUK MENGIRIM PESAN KE DATABASE & IONIC
    function kirimPesan(event) {
        event.preventDefault(); // Mencegah halaman reload
        
        let messageInput = document.getElementById('chatMessage');
        let receiverId = document.getElementById('receiver_id').value;
        let btnKirim = document.getElementById('btnKirimChat');
        let chatArea = document.getElementById('chatArea');
        
        let messageText = messageInput.value;
        if(messageText.trim() === '') return;

        // Ubah tombol jadi loading
        btnKirim.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btnKirim.disabled = true;

        // Tembak Data ke Backend Route
        fetch("{{ route('admin.cs.send') }}", { 
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                receiver_id: receiverId, 
                message: messageText     
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Kosongkan form teks
                messageInput.value = '';
                
                // Panggil Toast Pintar
                if (typeof window.triggerSystemEvent === 'function') {
                    window.triggerSystemEvent('Pesan Terkirim', 'Pesan diteruskan ke vendor bersangkutan.', 'success');
                }

                // Hapus tulisan "Belum ada riwayat" jika ada
                let placeholder = document.getElementById('emptyChatPlaceholder');
                if(placeholder) placeholder.remove();

                // Tambahkan Bubble Chat Admin (Navy) ke layar
                let waktuSekarang = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                let bubbleHTML = `
                    <div class="d-flex flex-column align-items-end mt-2">
                        <span class="small fw-bold mb-1 me-1" style="color: var(--color-text-muted);">Admin (Anda)</span>
                        <div class="chat-bubble chat-bubble-admin">
                            ${messageText}
                        </div>
                        <span class="small mt-1 me-1" style="color: var(--color-text-muted); font-size: 0.75rem;">${waktuSekarang} <i class="fa-solid fa-check ms-1" style="color: var(--color-accent);"></i></span>
                    </div>
                `;
                
                chatArea.insertAdjacentHTML('beforeend', bubbleHTML);
                chatArea.scrollTop = chatArea.scrollHeight; // Auto scroll bawah
            }
        })
        .catch(error => {
            console.error("Kesalahan API:", error);
            if (typeof window.triggerSystemEvent === 'function') {
                window.triggerSystemEvent('Gagal Terkirim', 'Route backend belum tersedia atau terjadi kesalahan jaringan.', 'error');
            }
        })
        .finally(() => {
            // Kembalikan tombol seperti semula
            btnKirim.innerHTML = 'Kirim <i class="fa-solid fa-paper-plane ms-1"></i>';
            btnKirim.disabled = false;
        });
    }

    // Bisa enter untuk mengirim (Shift+Enter untuk baris baru)
    document.getElementById('chatMessage').addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('formBalasChat').dispatchEvent(new Event('submit'));
        }
    });
</script>
@endpush