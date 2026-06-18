@extends('layouts.app')
@section('title', 'Hubungi Kontak Admin')

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh; padding-top: 3rem; padding-bottom: 3rem;">
        
        {{-- CARD CONTAINER UTAMA (Mengikuti Standard Ukuran & Radius Proculus) --}}
        <div class="card border-0 shadow-lg overflow-hidden w-100" style="max-width: 1000px; border-radius: var(--radius-card, 16px);">
            <div class="row g-0">

                {{-- SISI KIRI: BRANDING & INFO HUBUNGI (Tema Navy & Gold Glow) --}}
                <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-center" style="background: var(--color-primary); color: var(--color-white); position: relative;">
                    <div style="position: absolute; top: -50px; left: -50px; width: 150px; height: 150px; background: rgba(217, 119, 6, 0.1); border-radius: 50%;"></div>
                    
                    {{-- Logo Bulat Premium Proculus --}}
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" 
                        style="width: 110px; 
                                height: 110px; 
                                aspect-ratio: 1/1; 
                                flex-shrink: 0; 
                                background-color: #fff1e1;
                                border: 1px solid #5c361ba8; 
                                box-shadow: 0 0 25px #f59f0baa; 
                                transition: transform 0.3s ease;">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Proculus Logo" class="img-fluid" style="max-width: 70%; max-height: 70%; object-fit: contain;">
                    </div>

                    <h4 class="fw-bold mb-2" style="letter-spacing: -0.01em;">Kontak Admin</h4>
                    <p class="mb-4 opacity-75 small">Mengalami kendala teknis pendaftaran, integrasi sistem, atau akses aplikasi mobile?</p>

                    <div class="text-start mx-auto" style="max-width: 260px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); flex-shrink:0;">
                                <i class="fa-solid fa-clock text-warning small"></i>
                            </div>
                            <span class="small fw-medium opacity-90">Respon Cepat: <br><strong>Senin - Jumat (08.00 - 17.00)</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); flex-shrink:0;">
                                <i class="fa-solid fa-shield-halved text-warning small"></i>
                            </div>
                            <span class="small fw-medium opacity-90">Tiket Pengaduan: <br><strong>Diawasi oleh Tim Auditor</strong></span>
                        </div>
                    </div>
                </div>

                {{-- SISI KANAN: FORM INPUT (Tema Putih & Input Group Bergaya Premium) --}}
                <div class="col-md-7 p-5 bg-white">
                    <div class="mb-4">
                        <h3 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.02em;">Kirim Pesan ke Admin</h3>
                        <p class="text-muted small">Lengkapi data di bawah ini untuk mendapatkan bantuan langsung dari administrator proculus.</p>
                    </div>

                    {{-- Form Handler Terintegrasi Skrip Animasi Keluar --}}
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Pesan pengaduan Anda berhasil terkirim ke Admin Proculus! Tim kami akan segera menghubungi Anda melalui email.'); window.location.href='/';">
                        @csrf

                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Nama Lengkap / Nama Perusahaan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fa-solid fa-building-user"></i></span>
                                <input type="text" class="form-control border-start-0 px-2 shadow-none" placeholder="Masukkan nama instansi atau nama Anda..." style="border-color: var(--color-border); border-radius: 0 8px 8px 0; height: 48px;" required>
                            </div>
                        </div>

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Alamat Email Korespondensi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control border-start-0 px-2 shadow-none" placeholder="contoh: company@email.com" style="border-color: var(--color-border); border-radius: 0 8px 8px 0; height: 48px;" required>
                            </div>
                        </div>

                        {{-- Input Detail Pesan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted);">Rincian Pertanyaan / Deskripsi Kendala</label>
                            <textarea class="form-control shadow-none p-3" rows="4" placeholder="Tulis secara detail kendala sistem, nomor pengadaan lelang, atau pesan Anda di sini..." style="border-color: var(--color-border); border-radius: 8px; resize: none;" required></textarea>
                        </div>

                        <form action="#" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); alert('Pesan pengaduan Anda berhasil terkirim ke Admin Proculus! Tim kami akan segera menghubungi Anda melalui email.'); window.location.href='/';"></form>

                        {{-- Button Kirim (Style Solid Amber Beranimasi) --}}
                        <button type="submit" class="btn w-100 py-3 mb-3 shadow-sm text-white" style="
                            background-color: var(--color-accent); 
                            color: var(--color-white); 
                            border: 2px solid var(--color-accent);
                            border-radius: 8px; 
                            font-weight: 700;
                            font-size: 1.05rem;
                            transition: all 0.25s ease;
                        " onmouseover="this.style.backgroundColor='#b45309'; this.style.borderColor='#b45309';" onmouseout="this.style.backgroundColor='var(--color-accent)'; this.style.borderColor='var(--color-accent)';">
                            Kirim Tiket Pengaduan <i class="fa-solid fa-paper-plane ms-2"></i>
                        </button>

                        {{-- Kembali ke Beranda --}}
                        <div class="text-center">
                            <a href="{{ url('/') }}" class="text-decoration-none small fw-bold" style="color: var(--color-accent);">
                                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Halaman Utama
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, textarea');
        const STORAGE_KEY = 'contact_form_autosave';

        // 1. Fungsi Auto-Save (Menyimpan setiap ada perubahan)
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const formData = {};
                inputs.forEach(i => formData[i.name || i.getAttribute('placeholder')] = i.value);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
            });
        });

        // 2. Fungsi Load Data (Mengembalikan data saat halaman di-refresh)
        const savedData = localStorage.getItem(STORAGE_KEY);
        if (savedData) {
            const formData = JSON.parse(savedData);
            inputs.forEach(input => {
                const key = input.name || input.getAttribute('placeholder');
                if (formData[key]) input.value = formData[key];
            });
        }

        // 3. Bersihkan data setelah form berhasil terkirim
        form.addEventListener('submit', () => {
            localStorage.removeItem(STORAGE_KEY);
        });
    });
</script>
@endpush
@endsection