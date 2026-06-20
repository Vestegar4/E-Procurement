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

                {{-- TAB 1: TABEL PENGADAAN (KODE TABEL ASLI) --}}
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
                @php
                // Mengambil data vendor untuk list chat di Dashboard
                $dashboardVendors = \App\Models\Vendor::latest()->get();
                @endphp

                <div class="row g-0 border-top">
                    {{-- KIRI: DAFTAR VENDOR --}}
                    <div class="col-md-4 border-end" style="background: var(--color-white); max-height: 500px; overflow-y: auto;">
                        <div class="p-3 border-bottom">
                            <input type="text" class="form-control" placeholder="Cari percakapan..." style="border-radius: 8px;">
                        </div>

                        <div id="dashboardVendorList">
                            @forelse($dashboardVendors as $vendor)
                            <div id="dash-vendor-{{ $vendor->id }}"
                                class="p-3 border-bottom dash-vendor-item {{ $loop->first ? 'bg-navy text-white active' : 'bg-white text-dark' }}"
                                style="cursor: pointer; transition: 0.2s;"
                                onclick="bukaChatDashboard('{{ $vendor->id }}', '{{ addslashes($vendor->company_name ?? $vendor->name) }}')">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold" style="font-size: 0.95rem;">{{ $vendor->company_name ?? $vendor->name }}</span>
                                    <small style="opacity: 0.7; font-size: 0.75rem;">Vendor</small>
                                </div>
                                <p class="mb-0 small text-truncate" style="opacity: 0.8;">Ketuk untuk melihat pesan...</p>
                            </div>
                            @empty
                            <div class="p-4 text-center text-muted small">Belum ada vendor terdaftar.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- KANAN: RUANG CHAT --}}
                    <div class="col-md-8 d-flex flex-column" style="background: #f8fafc; max-height: 500px;">
                        {{-- Header Chat --}}
                        <div class="p-3 border-bottom bg-white d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 text-white fw-bold" style="width: 40px; height: 40px; background-color: var(--color-accent);">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div>
                                <h6 id="dashChatHeader" class="fw-bold mb-0 text-dark">
                                    {{ $dashboardVendors->count() > 0 ? ($dashboardVendors->first()->company_name ?? $dashboardVendors->first()->name) : 'Pilih Vendor' }}
                                </h6>
                                <small class="text-success"><i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Sedang aktif</small>
                            </div>
                        </div>

                        {{-- Area Pesan --}}
                        <div class="flex-grow-1 p-4 overflow-auto" id="dashChatArea">
                            <div class="text-center text-muted my-5">
                                <i class="fa-solid fa-spinner fa-spin mb-2 fs-3 opacity-50"></i>
                                <p class="small">Memuat percakapan...</p>
                            </div>
                        </div>

                        {{-- Input Pesan --}}
                        <div class="p-3 bg-white border-top">
                            <form id="dashFormChat" onsubmit="kirimChatDashboard(event)" class="m-0">
                                @csrf
                                <input type="hidden" id="dash_receiver_id" value="{{ $dashboardVendors->count() > 0 ? $dashboardVendors->first()->id : '' }}">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-paperclip text-muted"></i></span>
                                    <input type="text" id="dashChatMessage" class="form-control border-start-0" placeholder="Ketik balasan pesan di sini..." required>
                                    <button type="submit" id="dashBtnKirim" class="btn text-white px-4" style="background-color: var(--color-primary);">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <style>
                    /* Styling khusus untuk state active pada list vendor dashboard */
                    .dash-vendor-item.bg-navy {
                        background-color: var(--color-primary) !important;
                        color: white !important;
                    }

                    .dash-vendor-item.bg-navy .text-muted {
                        color: rgba(255, 255, 255, 0.7) !important;
                    }

                    .dash-chat-bubble-vendor {
                        background: white;
                        border: 1px solid #e2e8f0;
                        border-radius: 0 12px 12px 12px;
                        padding: 12px 16px;
                        max-width: 80%;
                        color: #1e293b;
                    }

                    .dash-chat-bubble-admin {
                        background: var(--color-primary);
                        color: white;
                        border-radius: 12px 0 12px 12px;
                        padding: 12px 16px;
                        max-width: 80%;
                        box-shadow: 0 4px 6px rgba(15, 23, 42, 0.1);
                    }
                </style>
            </div>

        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
    // 1. Load chat pertama kali saat halaman dibuka
    document.addEventListener("DOMContentLoaded", function() {
        let firstVendor = document.getElementById('dash_receiver_id').value;
        if(firstVendor) {
            let firstName = document.getElementById('dashChatHeader').innerText;
            bukaChatDashboard(firstVendor, firstName);
        }
    });
    
    // 2. Fungsi mengganti obrolan
    function bukaChatDashboard(vendorId, vendorName) {
        document.getElementById('dash_receiver_id').value = vendorId;
        document.getElementById('dashChatHeader').innerText = vendorName;

        // Ubah warna aktif di sidebar kiri
        document.querySelectorAll('.dash-vendor-item').forEach(el => {
            el.classList.remove('bg-navy', 'text-white', 'active');
            el.classList.add('bg-white', 'text-dark');
        });
        let activeEl = document.getElementById('dash-vendor-' + vendorId);
        if(activeEl) {
            activeEl.classList.remove('bg-white', 'text-dark');
            activeEl.classList.add('bg-navy', 'text-white', 'active');
        }

        let chatArea = document.getElementById('dashChatArea');
        chatArea.innerHTML = `<div class="text-center text-muted my-5"><i class="fa-solid fa-spinner fa-spin mb-2 fs-3 opacity-50"></i><p class="small">Menarik pesan...</p></div>`;

        // Fetch data dari database
        fetch(`/admin/chat/messages/${vendorId}`)
            .then(res => res.json())
            .then(data => {
                chatArea.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    data.data.forEach(chat => {
                        if (chat.is_admin) {
                            chatArea.innerHTML += `
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="dash-chat-bubble-admin">
                                        <p class="mb-1 small">${chat.message}</p>
                                        <small style="font-size: 0.7rem; opacity: 0.7;">${chat.time} <i class="fa-solid fa-check-double ms-1"></i></small>
                                    </div>
                                </div>`;
                        } else {
                            chatArea.innerHTML += `
                                <div class="d-flex justify-content-start mb-3">
                                    <div class="dash-chat-bubble-vendor shadow-sm">
                                        <p class="mb-1 small">${chat.message}</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">${chat.time}</small>
                                    </div>
                                </div>`;
                        }
                    });
                } else {
                    chatArea.innerHTML = `<div class="text-center text-muted my-4"><p class="small">Belum ada pesan dengan ${vendorName}.</p></div>`;
                }
                chatArea.scrollTop = chatArea.scrollHeight;
            }).catch(err => chatArea.innerHTML = `<div class="text-center text-danger my-4"><p class="small">Gagal memuat pesan.</p></div>`);
    }

    // 3. Fungsi mengirim pesan
    function kirimChatDashboard(e) {
        e.preventDefault();
        let input = document.getElementById('dashChatMessage');
        let receiverId = document.getElementById('dash_receiver_id').value;
        let btn = document.getElementById('dashBtnKirim');
        let chatArea = document.getElementById('dashChatArea');
        let msg = input.value.trim();

        if(!msg) return;

        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch("{{ route('admin.customer-service') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({ receiver_id: receiverId, message: msg })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                input.value = '';
                let time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                // Hapus placeholder kosong jika ada
                if(chatArea.innerHTML.includes('Belum ada pesan')) chatArea.innerHTML = '';

                chatArea.innerHTML += `
                    <div class="d-flex justify-content-end mb-3">
                        <div class="dash-chat-bubble-admin">
                            <p class="mb-1 small">${msg}</p>
                            <small style="font-size: 0.7rem; opacity: 0.7;">${time} <i class="fa-solid fa-check ms-1"></i></small>
                        </div>
                    </div>`;
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        })
        .finally(() => {
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
            btn.disabled = false;
        });
    }
</script>
@endpush