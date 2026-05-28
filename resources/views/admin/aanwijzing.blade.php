@extends('layouts.admin')
@section('title', 'Forum Aanwijzing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main);">Forum Aanwijzing (Tanya Jawab)</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Tender: {{ $tender->title }}</p>
    </div>
    <a href="{{ route('admin.procurement') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
@endif

<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-header border-bottom pt-4 pb-3" style="background-color: var(--color-surface);">
        <h6 class="fw-bold mb-0"><i class="fa-solid fa-comments me-2 text-primary"></i>Daftar Pertanyaan Peserta</h6>
    </div>
    
    <div class="card-body p-4">
        @forelse($tender->aanwijzings as $tanya)
            <div class="border rounded p-4 mb-4 shadow-sm">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                    <strong class="text-primary fs-5">{{ $tanya->vendor->company_name ?? $tanya->vendor->name }}</strong>
                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ $tanya->created_at->format('d M Y, H:i') }}</small>
                </div>
                
                <p class="mb-3 fs-6"><strong>Pertanyaan:</strong> <br> {{ $tanya->question }}</p>

                @if($tanya->answer)
                    <div class="p-3 rounded" style="background-color: #f8f9fa; border-left: 4px solid #198754;">
                        <strong class="text-success"><i class="fa-solid fa-check-circle me-1"></i> Jawaban Panitia:</strong> <br>
                        <span class="fs-6">{{ $tanya->answer }}</span>
                    </div>
                @else
                    <form action="{{ route('admin.aanwijzing.jawab', $tanya->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Tulis Jawaban Anda:</label>
                            <textarea name="answer" class="form-control" rows="3" placeholder="Ketik penjelasan resmi panitia di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary-action">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Jawaban ke Vendor
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fa-regular fa-comments fa-4x mb-3" style="opacity: 0.2;"></i>
                <h5 class="fw-bold">Belum Ada Pertanyaan</h5>
                <p class="mb-0">Vendor belum mengajukan pertanyaan untuk tender ini pada fase Aanwijzing.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection