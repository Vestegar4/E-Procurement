@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid p-0">

    {{-- HEADER HALAMAN --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pengaturan Sistem</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola profil instansi, perbarui tingkat keamanan, dan atur opsi peringatan toast</p>
    </div>

    {{-- NAVIGATION TABS (Dibuat responsif & bisa di-scroll horizontal pada Mobile dengan Swipe) --}}
    <ul class="settings-tab-container nav flex-nowrap overflow-x-auto pb-2 mb-4" id="settingsTab" role="tablist" 
        style="white-space: nowrap; -webkit-overflow-scrolling: touch; border-bottom: 2px solid var(--color-border);">
        <li class="nav-item" role="presentation">
            <button class="settings-tab-link active py-3 px-4" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab" style="font-weight: 700; transition: all 0.3s ease;">
                <i class="fa-solid fa-building me-2"></i> Profil Instansi
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="settings-tab-link py-3 px-4" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane" type="button" role="tab" style="font-weight: 700; transition: all 0.3s ease; color: var(--color-text-muted);">
                <i class="fa-solid fa-shield-halved me-2"></i> Keamanan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="settings-tab-link py-3 px-4" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification-pane" type="button" role="tab" style="font-weight: 700; transition: all 0.3s ease; color: var(--color-text-muted);">
                <i class="fa-solid fa-bell me-2"></i> Notifikasi & Toast
            </button>
        </li>
    </ul>

    {{-- KONTEN INTERAKTIF TABS --}}
    <div class="tab-content" id="settingsTabContent">
        
        {{-- 1. TAB: PROFIL INSTANSI --}}
        <div class="tab-pane fade show active" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--color-text-main);">
                        <i class="fa-solid fa-circle-info me-2 text-warning"></i>Informasi Umum Instansi
                    </h5>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); shadowToast('Sukses', 'Profil instansi berhasil diperbarui!', 'success');">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-uppercase small text-muted">Nama Instansi / Perusahaan</label>
                                <input type="text" class="form-control auth-input" value="Proculus Procurement Group" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-uppercase small text-muted">Email Korespondensi Resmi</label>
                                <input type="email" class="form-control auth-input" value="admin@proculus-enterprise.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase small text-muted">Alamat Kantor Pusat</label>
                                <textarea class="form-control auth-input" rows="3" required>Gedung Menara Aureate Lt. 24, Jl. Jenderal Sudirman Kav. 21, Jakarta Selatan, DKI Jakarta</textarea>
                            </div>
                        </div>
                        
                        {{-- TOMBOL 1: Simpan Perubahan Profile (Premium Solid Amber Gold) --}}
                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-action-amber px-4 py-2-5 fw-bold text-white shadow-sm border-0" 
                                    style="background: linear-gradient(135deg, var(--color-accent) 0%, #ff7913 100%); border-radius: 8px; transition: transform 0.2s ease;">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. TAB: KEAMANAN --}}
        <div class="tab-pane fade" id="security-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
            <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--color-text-main);">
                        <i class="fa-solid fa-key me-2 text-danger"></i>Perbarui Kredensial Akses
                    </h5>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); shadowToast('Sukses', 'Kredensial akun berhasil diperbarui secara aman!', 'success');">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase small text-muted">Password Saat Ini</label>
                                <input type="password" class="form-control auth-input" placeholder="••••••••••••" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-uppercase small text-muted">Password Baru</label>
                                <input type="password" class="form-control auth-input" placeholder="Minimal 8 karakter unik..." required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-uppercase small text-muted">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control auth-input" placeholder="Ulangi input password baru..." required>
                            </div>
                        </div>

                        {{-- TOMBOL 2: Update Keamanan Akun (Premium Dark Navy Slate) --}}
                        <div class="mt-4 pt-2">
                            <button type="submit" class="btn btn-action-navy px-4 py-2-5 fw-bold text-white shadow-sm border-0" 
                                    style="background: linear-gradient(135deg, var(--color-primary) 0%, #1e293b 100%); border-radius: 8px; transition: transform 0.2s ease;">
                                <i class="fa-solid fa-shield-halved me-2"></i> Update Keamanan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. TAB: NOTIFIKASI & TOAST --}}
        <div class="tab-pane fade" id="notification-pane" role="tabpanel" aria-labelledby="notification-tab" tabindex="0">
            <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-2" style="color: var(--color-text-main);">
                        <i class="fa-solid fa-sliders me-2 text-success"></i>Kontrol Toast Notification Cerita / Sistem
                    </h5>
                    <p class="text-muted small mb-4">Aktifkan atau nonaktifkan jendela notifikasi popup (Toast) interaktif yang muncul di sudut layar secara realtime.</p>
                    
                    <div class="p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3" 
                         style="background-color: var(--color-surface); border: 1px solid var(--color-border);">
                        <div>
                            <strong class="d-block mb-1" style="color: var(--color-text-main);">Popup Interaction Toast Alert</strong>
                            <small class="text-muted d-block">Matikan sakelar ini untuk menghentikan seluruh notifikasi otomatis bermodel Toast di Dashboard Admin.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            {{-- RIIL TOGGLE NOTIFIKASI (Tersambung ke localStorage via Script) --}}
                            <input class="form-check-input" type="checkbox" role="switch" id="realToastNotificationToggle" style="transform: scale(1.4); cursor: pointer;">
                        </div>
                    </div>

                    {{-- TOMBOL 3: Simpan Opsi Peringatan (Premium Emerald Success) --}}
                    <div>
                        <button type="button" id="saveNotificationOptionsBtn" class="btn btn-action-success px-4 py-2-5 fw-bold text-white shadow-sm border-0" 
                                style="background: linear-gradient(135deg, var(--color-success-border) 0%, #047857 100%); border-radius: 8px; transition: transform 0.2s ease;">
                            <i class="fa-solid fa-check-double me-2"></i> Simpan Opsi Peringatan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LOGIKA RESPONSIVE TAB SLIDING ACTIVE STYLE ---
        const triggerTabList = document.querySelectorAll('#settingsTab button');
        triggerTabList.forEach(triggerEl => {
            triggerEl.addEventListener('click', function(event) {
                triggerTabList.forEach(btn => {
                    btn.style.color = 'var(--color-text-muted)';
                    btn.style.borderBottom = 'none';
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                this.style.color = 'var(--color-primary)';
            });
        });

        // --- 2. FITUR RIIL: KONTROL POPUP TOAST NOTIFIKASI ---
        const toggleSwitch = document.getElementById('realToastNotificationToggle');
        const saveBtn = document.getElementById('saveNotificationOptionsBtn');

        // Membaca status awal dari localStorage, default jika kosong adalah TRUE (Aktif)
        const currentToastStatus = localStorage.getItem('enable_admin_toasts');
        if (currentToastStatus === null || currentToastStatus === 'true') {
            toggleSwitch.checked = true;
        } else {
            toggleSwitch.checked = false;
        }

        // Event listener saat sakelar digeser langsung oleh admin
        toggleSwitch.addEventListener('change', function() {
            if (this.checked) {
                localStorage.setItem('enable_admin_toasts', 'true');
                shadowToast('Notifikasi Aktif', 'Toast alert berhasil diaktifkan kembali secara global.', 'success');
            } else {
                localStorage.setItem('enable_admin_toasts', 'false');
                // Menggunakan native alert bawaan apabila sweetalert dinonaktifkan
                Swal.fire({
                    icon: 'warning',
                    title: 'Toast Dinonaktifkan',
                    text: 'Seluruh popup interaksi toast tidak akan dimunculkan lagi di halaman sistem.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });

        // Event listener saat tombol "Simpan Opsi Peringatan" ditekan
        saveBtn.addEventListener('click', function() {
            const statusLabel = toggleSwitch.checked ? 'AKTIF' : 'NON-AKTIF';
            shadowToast('Konfigurasi Disimpan', 'Opsi peringatan toast berhasil disimpan dengan status: ' + statusLabel, 'success');
        });
    });

    // Wrapper fungsi toast pintar agar mematuhi konfigurasi sakelar riil
    function shadowToast(title, message, iconType) {
        const isToastEnabled = localStorage.getItem('enable_admin_toasts');
        
        // Jika admin memilih untuk me-nonaktifkan toast, blokir kemunculan popup!
        if (isToastEnabled === 'false') {
            console.warn('Proculus Blocked: Toast ditolak muncul karena opsi sakelar dinonaktifkan.');
            return;
        }

        // Sebaliknya, jika aktif, panggil popup SweetAlert2 yang menawan
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: iconType,
            title: title,
            text: message,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    }
</script>
@endpush