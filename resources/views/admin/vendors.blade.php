@extends('layouts.admin')
@section('title', 'Vendor Management')

@section('content')
    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Vendor[] $vendors */
    @endphp

    {{-- HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Data Rekanan Vendor</h4>
            <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Tinjau pendaftaran vendor baru dan lakukan persetujuan (ACC)</p>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4" role="alert" style="background-color: var(--color-success-bg); color: var(--color-success-text); border-left: 4px solid var(--color-success-border); border-right: none; border-top: none; border-bottom: none;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- TABEL DATA VENDOR --}}
    <div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--color-surface);">
                        <tr>
                            <th style="width: 35%;">Nama Perusahaan / Vendor</th>
                            <th style="width: 25%;">Email Bisnis</th>
                            <th style="width: 15%;">Status</th>
                            <th class="text-center" style="width: 25%;">Aksi (Approval)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors ?? [] as $vendor)
                            <tr>
                                {{-- Kolom Nama --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold text-uppercase" style="width: 40px; height: 40px; background: var(--color-primary); color: var(--color-accent-bright);">
                                            {{ substr($vendor->company_name ?? $vendor->name ?? 'V', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="color: var(--color-text-main);">{{ $vendor->company_name ?? $vendor->name ?? '-' }}</h6>
                                            <span class="small text-muted">ID: VND-{{ str_pad($vendor->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Kolom Email --}}
                                <td style="color: var(--color-text-muted); font-weight: 500;">
                                    {{ $vendor->email }}
                                </td>

                                {{-- Kolom Status --}}
                                <td>
                                    @if($vendor->status === 'approved')
                                        <span class="badge badge-pastel-success rounded-pill px-3 py-2"><i class="fa-solid fa-check me-1"></i> Disetujui</span>
                                    @elseif($vendor->status === 'rejected')
                                        <span class="badge badge-pastel-danger rounded-pill px-3 py-2" style="background: var(--color-danger-bg); color: var(--color-danger-text);"><i class="fa-solid fa-ban me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-pastel-warning rounded-pill px-3 py-2"><i class="fa-solid fa-hourglass-half me-1"></i> Pending</span>
                                    @endif
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="text-center">
                                    @if($vendor->status === 'pending' || $vendor->status === null)
                                        <div class="d-flex justify-content-center gap-2">
                                            
                                            {{-- FORM ACC (SETUJUI) --}}
                                            <form action="{{ route('admin.vendors.update-status', $vendor->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm px-3 py-2 fw-bold shadow-sm" style="background-color: var(--color-success-border); color: white; border-radius: 6px; transition: 0.2s;" onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                                                    <i class="fa-solid fa-check-circle me-1"></i> Terima
                                                </button>
                                            </form>

                                            {{-- FORM TOLAK --}}
                                            <form action="{{ route('admin.vendors.update-status', $vendor->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm px-3 py-2 fw-bold shadow-sm" style="border: 1px solid var(--color-danger-border); color: var(--color-danger-border); background: white; border-radius: 6px; transition: 0.2s;" onmouseover="this.style.backgroundColor='var(--color-danger-bg)';" onmouseout="this.style.backgroundColor='white';">
                                                    Tolak
                                                </button>
                                            </form>
                                            
                                        </div>
                                    @else
                                        {{-- Jika sudah diproses, tampilkan badge kecil --}}
                                        <span class="text-muted small fw-medium"><i class="fa-solid fa-lock me-1"></i> Selesai Diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--color-text-muted);">
                                    <i class="fa-solid fa-building-circle-xmark display-5 mb-3" style="color: var(--color-primary); opacity: 0.3;"></i>
                                    <p class="fw-bold mb-0">Belum ada vendor yang mendaftar atau perlu diproses.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection