@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
@endphp

{{-- HEADER HALAMAN --}}
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Manajemen Data Pengguna</h4>
    <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Pantau hak akses, peran sistem, dan seluruh akun pengguna terdaftar</p>
</div>

{{-- KOTAK SEARCH & FILTER ROLE --}}
<div class="card card-custom mb-4 border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-3">
        <form action="{{ route('admin.users') }}" method="GET">
            <div class="row g-2 align-items-center">
                
                {{-- Input Pencarian Teks --}}
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik nama atau email pengguna lalu tekan enter..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Dropdown Filter Peran (Dinamis Auto-Submit) --}}
                <div class="col-md-4">
                    <select name="role" class="form-select auth-input" onchange="this.form.submit()" style="cursor: pointer;">
                        <option value="">Semua Hak Akses / Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                    </select>
                </div>
                
                {{-- Tombol Eksekusi Manual --}}
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                        <i class="fa-solid fa-filter me-1"></i> Terapkan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL DATA USER UTAMA --}}
<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: var(--color-surface); color: var(--color-text-main); font-weight: 700;">
                    <tr>
                        <th class="px-4 py-3" style="width: 40%;">Nama Pengguna</th>
                        <th class="py-3" style="width: 35%;">Alamat Email</th>
                        <th class="py-3" style="width: 25%;">Peran / Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="px-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase shadow-sm" 
                                     style="width: 38px; height: 38px; background: #e2e8f0; color: var(--color-primary); font-size: 1.05rem; border: 1px solid #cbd5e1;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="fw-bold" style="color: var(--color-text-main);">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color: var(--color-text-muted); font-weight: 500;">{{ $user->email }}</td>
                        <td>
                            {{-- LOGIKA BADGE ROLE DINAMIS DARI DATABASE --}}
                            @if(strtolower($user->role) === 'admin')
                                <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-accent-bright); font-size: 0.8rem;">
                                    <i class="fa-solid fa-user-shield me-1"></i> ADMINISTRATOR
                                </span>
                            @elseif(strtolower($user->role) === 'vendor')
                                <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm" style="background-color: var(--color-success-bg); color: var(--color-success-border); font-size: 0.8rem;">
                                    <i class="fa-solid fa-building me-1"></i> VENDOR
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 fw-bold text-uppercase shadow-sm bg-secondary text-white" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-user me-1"></i> {{ $user->role ?? 'USER' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5" style="color: var(--color-text-muted);">
                            <i class="fa-solid fa-users-slash display-5 mb-3 text-muted opacity-50"></i>
                            <p class="fw-bold mb-0">Belum ada data user terdaftar yang sesuai filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINASI HALAMAN --}}
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
            <div class="d-flex justify-content-center mt-4 pt-3 border-top pb-4">
                {{ $users->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection