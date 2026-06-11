@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
@endphp

{{-- FITUR SEARCH (Tanpa Filter Role) --}}
<div class="card card-custom mb-4 border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-3">
        <form action="{{ route('admin.users') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 auth-input px-0" placeholder="Ketik nama atau email admin lalu tekan enter..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: var(--color-primary); color: var(--color-white); border-radius: 8px; font-size: 1.05rem;">
                            <i class="fa-solid fa-search me-1"></i> Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABEL DATA USER --}}
<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: var(--color-surface);">
                    <tr>
                        <th style="width: 40%;">Pengguna</th>
                        <th style="width: 35%;">Email</th>
                        <th style="width: 25%;">Peran (Role)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 36px; height: 36px; background: var(--color-primary); color: var(--color-accent);">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="fw-bold" style="color: var(--color-text-main);">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color: var(--color-text-muted); font-weight: 500;">{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-pastel-success rounded-pill px-3 py-2 fw-bold">ADMINISTRATOR</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5" style="color: var(--color-text-muted);">
                            <i class="fa-solid fa-users-slash display-5 mb-3" style="color: var(--color-primary); opacity: 0.5;"></i>
                            <p class="fw-bold mb-0">Belum ada data user yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection