@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
@endphp

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--color-text-main); letter-spacing: -0.01em;">Daftar Pengguna</h4>
        <p class="mb-0 fw-medium" style="color: var(--color-text-muted);">Kelola hak akses tingkat Admin dan Super Admin</p>
    </div>

    @if(auth()->check() && auth()->user()->role === 'super_admin')
    <button class="btn btn-primary-action" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i class="fa-solid fa-user-plus me-2" style="color: var(--color-accent);"></i> Tambah Admin
    </button>
    @else
    <button class="btn btn-outline-action" disabled title="Hanya Super Admin yang dapat menambah user">
        <i class="fa-solid fa-lock me-2"></i> Tambah Admin (Locked)
    </button>
    @endif
</div>

{{-- FITUR SEARCH & FILTER (BARU) --}}
<div class="card card-custom mb-4 border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-3">
        <form action="{{ route('admin.users') }}" method="GET">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control auth-input" placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-select auth-input">
                        <option value="">Semua Peran (Roles)</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary-action w-100" style="padding: 12px 24px;">
                        <i class="fa-solid fa-search me-2"></i> Cari User
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
                            @if($user->role === 'super_admin')
                            <span class="badge badge-pastel-warning rounded-pill px-3 py-2">SUPER ADMIN</span>
                            @else
                            <span class="badge badge-pastel-success rounded-pill px-3 py-2">ADMINISTRATOR</span>
                            @endif
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