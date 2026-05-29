@extends('layouts.admin')
@section('title', 'Forum Aanwijzing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Forum Aanwijzing (Tanya Jawab)</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Paket Pekerjaan: <strong style="color: var(--color-primary);">{{ $tender->title }}</strong></p>
    </div>
    <a href="{{ route('admin.procurement') }}" class="btn btn-outline-action shadow-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Pengadaan
    </a>
</div>

<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card, 16px);">
    <div class="card-header border-bottom pt-4 pb-3" style="background-color: var(--color-surface);">
        <h6 class="fw-bold mb-0" style="color: var(--color-text-main);"><i class="fa-solid fa-clipboard-question me-2" style="color: var(--color-accent);"></i>Daftar Pertanyaan Masuk dari Peserta</h6>
    </div>
    
    <div class="card-body p-4">
        @forelse($tender->aanwijzings ?? [] as $tanya)
            <div class="border rounded-4 p-4 mb-4 shadow-sm" style="border-color: var(--color-border) !important; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; background: var(--color-surface); color: var(--color-primary); border: 1px solid var(--color-border);">
                            {{ substr($tanya->vendor->company_name ?? 'V', 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="color: var(--color-text-main);">{{ $tanya->vendor->company_name ?? 'Peserta Vendor' }}</h6>
                            <span class="small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $tanya->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="mb-0 fw-medium p-3 rounded-3" style="background-color: var(--color-surface); color: var(--color-text-muted); border-left: 4px solid var(--color-accent);">
                        {{ $tanya->question }}
                    </p>
                </div>

                @if($tanya->answer)
                    <div class="p-3 rounded-3 shadow-sm" style="background-color: var(--color-success-bg); border: 1px solid var(--color-success-border);">
                        <strong style="color: var(--color-success-text); display: block; margin-bottom: 4px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Jawaban Panitia Resmi:
                        </strong>
                        <span style="color: var(--color-text-main); font-weight: 500;">{{ $tanya->answer }}</span>
                    </div>
                @else
                    {{-- Form Memberikan Jawaban --}}
                    <form action="{{ route('admin.aanwijzing.jawab', $tanya->id) }}" method="POST" class="m-0 bg-light p-3 rounded-3 border">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small" style="color: var(--color-text-muted); letter-spacing: 0.05em;">Tulis Jawaban Penjelasan Resmi</label>
                            <textarea name="answer" class="form-control auth-input" rows="2" placeholder="Berikan rincian teknis, adendum dokumen, atau konfirmasi spesifikasi di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn text-white px-4 fw-bold shadow-sm" style="background-color: var(--color-success-border); border: none; border-radius: 6px;">
                            <i class="fa-solid fa-paper-plane me-2"></i> Kirim Tanggapan Resmi
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fa-regular fa-comments fa-4x mb-3" style="opacity: 0.2; color: var(--color-primary);"></i>
                <h5 class="fw-bold" style="color: var(--color-text-muted);">Belum Ada Pertanyaan</h5>
                <p class="mb-0">Belum ada peserta vendor yang mengajukan pertanyaan teknis pada fase lelang ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection