{{-- 1. Container Data (Hidden) --}}
@if(session()->has('success') || session()->has('error') || session()->has('info') || session()->has('warning'))
@php
$type = 'info';
$title = 'Pemberitahuan Sistem';
$message = '';

// FITUR BARU: Tangkap URL tujuan jika dikirim dari controller
$url = session('url') ?? '';

// Deteksi jenis notifikasi bawaan Laravel
if (session()->has('success')) {
$type = 'success';
$title = 'Aksi Berhasil!';
$message = session('success');
} elseif (session()->has('error')) {
$type = 'error';
$title = 'Terjadi Kesalahan!';
$message = session('error');
} elseif (session()->has('warning')) {
$type = 'warning';
$title = 'Peringatan!';
$message = session('warning');
} elseif (session()->has('info')) {
$type = 'info';
$title = 'Informasi Terbaru';
$message = session('info');
}

// FITUR CERDAS: Ubah judul otomatis berdasarkan kata kunci isi pesan
$message = $message ?? ''; // Cegah undefined
$lowerMessage = strtolower($message);

if (str_contains($lowerMessage, 'aanwijzing') || str_contains($lowerMessage, 'tanya jawab') || str_contains($lowerMessage, 'pesan baru')) {
$title = '<i class="fa-solid fa-comments me-2" style="color: var(--color-accent);"></i> Notifikasi Aanwijzing';
$type = 'info';
} elseif (str_contains($lowerMessage, 'tender') || str_contains($lowerMessage, 'pengadaan')) {
$title = '<i class="fa-solid fa-folder-open me-2" style="color: var(--color-primary);"></i> Update Pengadaan';
} elseif (str_contains($lowerMessage, 'vendor') || str_contains($lowerMessage, 'rekanan')) {
$title = '<i class="fa-solid fa-building-circle-check me-2" style="color: var(--color-success-border);"></i> Aktivitas Vendor';
} elseif (str_contains($lowerMessage, 'po') || str_contains($lowerMessage, 'purchase')) {
$title = '<i class="fa-solid fa-file-invoice-dollar me-2" style="color: var(--color-accent-bright);"></i> Dokumen PO';
}
@endphp

<div id="interaction-toast-data"
    data-type="{{ $type }}"
    data-title="{{ $title }}"
    data-message="{{ $message }}"
    data-url="{{ $url }}"
    style="display:none;">
</div>
@endif

{{-- 2. Logic Script (Hanya jalan jika data ada) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastElement = document.getElementById('interaction-toast-data');

        if (toastElement) {
            const type = toastElement.getAttribute('data-type') || 'info';
            const title = toastElement.getAttribute('data-title') || 'Pemberitahuan Sistem';
            const message = toastElement.getAttribute('data-message') || '';

            // Ambil link url-nya
            const targetUrl = toastElement.getAttribute('data-url');

            // Di dalam script interaction-toast.blade.php Anda
            document.addEventListener("DOMContentLoaded", function() {
                // CEK APAKAH USER MENONAKTIFKAN TOAST LEWAT SAKELAR SETTING
                if (localStorage.getItem('enable_admin_toasts') === 'false') {
                    console.log('Toast notification ditolak tampil oleh pengaturan pengguna.');
                    return; 
                }
            });

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: title,
                text: message,
                showConfirmButton: false,
                timer: 8000, // Diperpanjang jadi 8 detik biar aman
                timerProgressBar: true,
                showCloseButton: true,
                customClass: {
                    title: 'fs-6 fw-bold text-dark',
                    htmlContainer: 'text-muted small fw-medium mt-1'
                },
                didOpen: (toast) => {
                    // JIKA ADA URL: Ubah kursor jadi telunjuk (pointer) dan buat bisa diklik
                    if (targetUrl) {
                        toast.style.cursor = 'pointer';
                        toast.addEventListener('click', (e) => {
                            // Pastikan fungsi klik pindah halaman tidak aktif kalau admin ngeklik tombol "Silang (X)"
                            if (!e.target.classList.contains('swal2-close')) {
                                window.location.href = targetUrl;
                            }
                        });
                    }

                    // Hentikan timer kalau admin nahan mouse di atas popup biar nggak hilang
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
    });
</script>
@endpush