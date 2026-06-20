@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@push('styles')
    <style>
        /* Menyembunyikan ikon mata bawaan Edge/Chrome */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">

        {{-- HEADER HALAMAN --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Pengaturan Sistem</h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Perbarui tingkat keamanan akun dan atur opsi
                pemberitahuan sistem</p>
        </div>

        {{-- NAVIGATION TABS --}}
        <ul class="settings-tab-container nav flex-nowrap overflow-x-auto pb-2 mb-4" id="settingsTab" role="tablist"
            style="white-space: nowrap; -webkit-overflow-scrolling: touch; border-bottom: 2px solid var(--color-border);">

            {{-- Tab Profil Instansi dinonaktifkan sementara --}}
            {{-- 
        <li class="nav-item" role="presentation">
            <button class="settings-tab-link py-3 px-4" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab" style="font-weight: 700; transition: all 0.3s ease;">
                <i class="fa-solid fa-building me-2"></i> Profil Instansi
            </button>
        </li>
        --}}

            {{-- Tab Keamanan (Diubah menjadi Default Active) --}}
            <li class="nav-item" role="presentation">
                <button class="settings-tab-link active py-3 px-4" id="security-tab" data-bs-toggle="tab"
                    data-bs-target="#security-pane" type="button" role="tab" aria-selected="true"
                    style="font-weight: 700; transition: all 0.3s ease;">
                    <i class="fa-solid fa-shield-halved me-2"></i> Keamanan
                </button>
            </li>

            {{-- Tab Notifikasi & Toast --}}
            <li class="nav-item" role="presentation">
                <button class="settings-tab-link py-3 px-4" id="notification-tab" data-bs-toggle="tab"
                    data-bs-target="#notification-pane" type="button" role="tab" aria-selected="false"
                    style="font-weight: 700; transition: all 0.3s ease;">
                    <i class="fa-solid fa-bell me-2"></i> Notifikasi
                </button>
            </li>
        </ul>

        {{-- KONTEN TABS --}}
        <div class="tab-content" id="settingsTabContent">

            {{-- Konten Profil Instansi dinonaktifkan sementara --}}
            {{-- 
        <div class="tab-pane fade" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab">
            </div> 
        --}}

            {{-- TAB 1: KEAMANAN (Langsung Muncul/Active) --}}
            <div class="tab-pane fade show active" id="security-pane" role="tabpanel" aria-labelledby="security-tab">
                <div class="card card-custom border-0 shadow-sm"
                    style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: var(--color-text-main);"><i
                                class="fa-solid fa-lock me-2 text-warning"></i>Ubah Password Administrator</h6>

                        {{-- Pastikan action diarahkan ke rute update password Anda --}}
                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control border-end-0 auth-input" placeholder="Masukkan password lama"
                                        required>
                                    <span class="input-group-text bg-white border-start-0 text-muted toggle-password"
                                        data-target="current_password" style="cursor: pointer;">
                                        <i class="fa-solid fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control border-end-0 auth-input" placeholder="Minimal 8 karakter"
                                            required>
                                        <span class="input-group-text bg-white border-start-0 text-muted toggle-password"
                                            data-target="new_password" style="cursor: pointer;">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation"
                                            id="new_password_confirmation" class="form-control border-end-0 auth-input"
                                            placeholder="Ketik ulang password baru" required>
                                        <span class="input-group-text bg-white border-start-0 text-muted toggle-password"
                                            data-target="new_password_confirmation" style="cursor: pointer;">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-action px-4 py-2 fw-bold shadow-sm"
                                style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px;">
                                <i class="fa-solid fa-key me-2"></i> Update Keamanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TAB 2: NOTIFIKASI & TOAST (Auto-Save Active) --}}
            <div class="tab-pane fade" id="notification-pane" role="tabpanel" aria-labelledby="notification-tab">

                {{-- Card 1: Popup Interaction Toast --}}
                <div class="card card-custom border-0 shadow-sm mb-3"
                    style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1" style="color: var(--color-text-main);">Popup Interaction Toast Alert
                            </h6>
                            <p class="small mb-0" style="color: var(--color-text-muted);">Aktifkan untuk memunculkan pesan
                                popup di kanan atas saat berinteraksi.</p>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0">
                            <input class="form-check-input shadow-none cursor-pointer" type="checkbox" id="toggleToast"
                                checked style="border-color: var(--color-border);">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Pemberitahuan Sistem (Tender & Vendor) --}}
                <div class="card card-custom border-0 shadow-sm mb-4"
                    style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold mb-1" style="color: var(--color-text-main);">Pemberitahuan Sistem (Tender &
                                Vendor)</h6>
                            <p class="small mb-0" style="color: var(--color-text-muted);">Matikan sakelar ini untuk
                                menghentikan pengiriman pesan notifikasi otomatis ke dalam sistem log user.</p>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0">
                            <input class="form-check-input shadow-none cursor-pointer" type="checkbox"
                                id="toggleSystemNotif" checked style="border-color: var(--color-border);">
                        </div>
                    </div>
                </div>

                {{-- Tombol Save Dinonaktifkan --}}
                {{-- 
            <div>
                <button id="saveNotifSettings" class="btn btn-primary-action px-4 py-2 fw-bold shadow-sm" style="background-color: var(--color-accent); color: var(--color-white); border: none; border-radius: 8px;">
                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Opsi Peringatan
                </button>
            </div> 
            --}}

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleToast = document.getElementById('toggleToast');
            const toggleSystemNotif = document.getElementById('toggleSystemNotif');

            // Mengambil status konfigurasi yang tersimpan di Local Storage
            const isToastEnabled = localStorage.getItem('enable_admin_toasts') !== 'false';
            const isSystemNotifEnabled = localStorage.getItem('enable_system_notif') !== 'false';

            // Setel antarmuka sakelar ke posisi tersimpan
            if (toggleToast) toggleToast.checked = isToastEnabled;
            if (toggleSystemNotif) toggleSystemNotif.checked = isSystemNotifEnabled;

            // Fungsi pembantu untuk memunculkan Toast konfirmasi instan
            function showAutoSaveToast(featureName) {
                const currentToastState = localStorage.getItem('enable_admin_toasts') !== 'false';

                if (currentToastState && typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Auto-Save Berhasil',
                        text: `Pengaturan ${featureName} diperbarui.`,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }

            // Event Listener: Auto-save saat Toggle Toast digeser
            if (toggleToast) {
                toggleToast.addEventListener('change', function() {
                    localStorage.setItem('enable_admin_toasts', this.checked);
                    showAutoSaveToast('Popup Interaksi Toast');
                });
            }

            // Event Listener: Auto-save saat Toggle Notifikasi Sistem digeser
            if (toggleSystemNotif) {
                toggleSystemNotif.addEventListener('change', function() {
                    localStorage.setItem('enable_system_notif', this.checked);
                    showAutoSaveToast('Log Notifikasi Sistem');
                });
            }

            // Toggle Password Visibility untuk Form Ubah Password
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput) {
                        const isPassword = passwordInput.type === 'password';
                        passwordInput.type = isPassword ? 'text' : 'password';

                        if (isPassword) {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });
        });

        // Wrapper fungsi toast untuk halaman Settings
        function shadowToast(title, message, iconType) {
            const isToastEnabled = localStorage.getItem('enable_admin_toasts') !== 'false';

            if (!isToastEnabled) {
                console.warn('Proculus Blocked: Toast ditolak muncul karena opsi sakelar dinonaktifkan.');
                return;
            }

            if (typeof Swal !== 'undefined') {
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
        }
    </script>
@endpush
