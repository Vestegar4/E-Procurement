@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
@php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
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

<div class="card card-custom border-0 shadow-sm" style="background: var(--color-white); border-radius: var(--radius-card);">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: var(--color-surface);">
                    <tr>
                        <th style="width: 35%;">Nama</th>
                        <th style="width: 40%;">Email</th>
                        <th style="width: 25%;">Role Access</th>
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
                            <span class="badge badge-pastel-warning rounded-pill">SUPER ADMIN</span>
                            @else
                            <span class="badge badge-pastel-success rounded-pill">ADMINISTRATOR</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5" style="color: var(--color-text-muted);">
                            <i class="fa-solid fa-users-slash display-5 mb-3" style="color: var(--color-primary); opacity: 0.5;"></i>
                            <p class="fw-bold mb-0">Belum ada data admin terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection