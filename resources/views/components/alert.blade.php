{{-- 1. Notifikasi Sukses --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4 auto-dismiss" role="alert" style="background-color: var(--color-success-bg); color: var(--color-success-text); border-left: 4px solid var(--color-success-border) !important; border-radius: 8px; padding: 16px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check me-3 fs-5" style="color: var(--color-success-border);"></i>
                <span class="fw-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

{{-- 2. Notifikasi Gagal / Error --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4 auto-dismiss" role="alert" style="background-color: var(--color-danger-bg); color: var(--color-danger-text); border-left: 4px solid var(--color-danger-border) !important; border-radius: 8px; padding: 16px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation me-3 fs-5" style="color: var(--color-danger-border);"></i>
                <span class="fw-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

{{-- SCRIPT AUTO-CLOSE 5 DETIK --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua alert
        const alerts = document.querySelectorAll('.auto-dismiss');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                // Cari tombol close yang ada di dalam alert tersebut
                const closeBtn = alert.querySelector('[data-bs-dismiss="alert"]');
                
                if (closeBtn) {
                    // Kita klik tombolnya secara otomatis agar animasi Bootstrap berjalan
                    closeBtn.click();
                } else {
                    // Fallback: Jika tombol tidak ditemukan, hapus elemen secara paksa
                    alert.remove();
                }
            }, 5000); // 5 detik
        });
    });
</script>