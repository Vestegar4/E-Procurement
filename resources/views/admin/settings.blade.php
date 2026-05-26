@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid p-0">

    {{-- LINK SUB-MENU TABS NAVIGATION (Gaya Neo Resign) --}}
    <ul class="nav nav-tabs border-0 mb-4 gap-4" id="settingsTab" role="tablist" style="border-bottom: 2px solid var(--color-border) !important; padding-bottom: 5px;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold border-0 bg-transparent p-0 pb-2 text-uppercase tracking-wider"
                id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab"
                style="color: var(--color-primary); border-bottom: 3px solid var(--color-accent) !important; font-size: 0.95rem;">
                Profile Instansi
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold border-0 bg-transparent p-0 pb-2 text-uppercase tracking-wider"
                id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane" type="button" role="tab"
                style="color: var(--color-text-muted); font-size: 0.95rem;">
                Security
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold border-0 bg-transparent p-0 pb-2 text-uppercase tracking-wider"
                id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification-pane" type="button" role="tab"
                style="color: var(--color-text-muted); font-size: 0.95rem;">
                Notifications
            </button>
        </li>
    </ul>

    {{-- ISI KONTEN PADA MASING-MASING SUB-MENU --}}
    <div class="tab-content" id="settingsTabContent">

        {{-- PANE 1: PROFIL INSTANSI --}}
        <div class="tab-pane fade show active" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom p-4 border-0 shadow-sm">
                        <h5 class="fw-bold mb-4" style="color: var(--color-text-main);">Identitas Profil Perusahaan</h5>
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-text-muted);">Nama Instansi / Perusahaan</label>
                                <input type="text" class="form-control auth-input" value="PT. Solusi Digital Enterprise" style="font-weight: 600;">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: var(--color-text-muted);">Email Notifikasi Sistem Utama</label>
                                <input type="email" class="form-control auth-input" value="admin@procurement.com" style="font-weight: 600;">
                            </div>
                            <button type="button" class="btn btn-gold px-4">Simpan Perubahan Profil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANE 2: SECURITY (UBAH PASSWORD) --}}
        <div class="tab-pane fade" id="security-pane" role="tabpanel" aria-labelledby="security-tab">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom p-4 border-0 shadow-sm">
                        <h5 class="fw-bold mb-4" style="color: var(--color-text-main);">Perbarui Kata Sandi Akses</h5>
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-text-muted);">Password Saat Ini</label>
                                <input type="password" class="form-control auth-input" placeholder="Masukkan password lama">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: var(--color-text-muted);">Password Baru</label>
                                <input type="password" class="form-control auth-input" placeholder="Min. 8 karakter gabungan">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: var(--color-text-muted);">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control auth-input" placeholder="Ulangi password baru">
                            </div>
                            <button type="button" class="btn btn-primary-action px-4">Update Keamanan Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANE 3: NOTIFICATIONS (TOGGLE SWITCH) --}}
        <div class="tab-pane fade" id="notification-pane" role="tabpanel" aria-labelledby="notification-tab">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom p-4 border-0 shadow-sm">
                        <h5 class="fw-bold mb-3" style="color: var(--color-text-main);">Preferensi Pusat Notifikasi</h5>
                        <p style="color: var(--color-text-muted); font-size: 0.95rem;" class="mb-4">Atur bagaimana sistem mengirim informasi pengadaan penting kepada manajemen.</p>

                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <h6 class="fw-bold m-0" style="color: var(--color-text-main);">Email Pengumuman Tender</h6>
                                <small style="color: var(--color-text-muted); font-weight: 500;">Kirim laporan ringkasan berkas pengadaan mingguan secara otomatis.</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked style="transform: scale(1.3); cursor: pointer;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                            <div>
                                <h6 class="fw-bold m-0" style="color: var(--color-text-main);">Peringatan Aktivitas Login Keamanan</h6>
                                <small style="color: var(--color-text-muted); font-weight: 500;">Beritahu admin dengan segera jika terdeteksi akses dari IP asing.</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" checked style="transform: scale(1.3); cursor: pointer;">
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary-action px-4">Simpan Opsi Peringatan</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika skrip mini untuk mengubah style tab aktif saat diklik (meniru interaksi dinamis CSS Anda)
    document.addEventListener('DOMContentLoaded', function() {
        const triggerTabList = document.querySelectorAll('#settingsTab button');
        triggerTabList.forEach(triggerEl => {
            triggerEl.addEventListener('click', function(event) {
                // Reset semua tab style ke warna standard redup
                triggerTabList.forEach(btn => {
                    btn.style.color = 'var(--color-text-muted)';
                    btn.style.borderBottom = 'none';
                });
                // Pasang style tebal & aksen kuning pada tab terpilih
                this.style.color = 'var(--color-primary)';
                this.style.borderBottom = '3px solid var(--color-accent)';
            });
        });
    });
</script>
@endpush