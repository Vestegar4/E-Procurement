@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #4a4a4a;">Daftar Pengguna</h4>
        <p class="text-muted mb-0">Kelola hak akses tingkat Admin dan Super Admin</p>
    </div>

    @if(auth()->user() && auth()->user()->role === 'super_admin')
        <button class="btn text-white px-4 py-2" style="background-color: #fe81d4; border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="fa-solid fa-user-plus me-2"></i>Tambah Admin
        </button>
    @else
        <button class="btn btn-secondary px-4 py-2" style="border-radius: 10px;" disabled title="Hanya Super Admin yang dapat menambah user">
            <i class="fa-solid fa-lock me-2"></i>Tambah Admin (Locked)
        </button>
    @endif
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #fffaf5;">
                    <tr>
                        <th class="text-muted border-0">Nama</th>
                        <th class="text-muted border-0">Email</th>
                        <th class="text-muted border-0">Role Access</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $user->role === 'super_admin' ? 'bg-danger' : 'bg-primary' }} px-3 py-2">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada data admin terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection