@extends('layouts.ios16')

@section('title', 'Kelola Pengguna - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1"><i class="bi bi-people me-2"></i>Kelola Pengguna</h1>
            <p class="text-secondary mb-0">Manajemen pengguna sistem inventory</p>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="ios-btn ios-btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="ios-card mb-4">
        <div class="ios-card-header">
            <h5 class="ios-card-title mb-0"><i class="bi bi-funnel me-2"></i>Filter & Pencarian</h5>
        </div>
        <div class="ios-card-body">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="ios-form-control" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="ios-form-control" name="role">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="ios-btn ios-btn-primary flex-fill">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('users.index') }}" class="ios-btn ios-btn-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #0056CC);">
                <div class="ios-card-body text-center text-white">
                    <i class="bi bi-shield-check fs-1 mb-3"></i>
                    <h2 class="mb-2">{{ $stats['admin'] }}</h2>
                    <p class="mb-0 opacity-75">Admin</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), #32A8E6);">
                <div class="ios-card-body text-center text-white">
                    <i class="bi bi-person-badge fs-1 mb-3"></i>
                    <h2 class="mb-2">{{ $stats['siswa'] }}</h2>
                    <p class="mb-0 opacity-75">Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #2FB344);">
                <div class="ios-card-body text-center text-white">
                    <i class="bi bi-check-circle fs-1 mb-3"></i>
                    <h2 class="mb-2">{{ $stats['verified'] }}</h2>
                    <p class="mb-0 opacity-75">Terverifikasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-purple), #8E44AD);">
                <div class="ios-card-body text-center text-white">
                    <i class="bi bi-people fs-1 mb-3"></i>
                    <h2 class="mb-2">{{ $stats['total'] }}</h2>
                    <p class="mb-0 opacity-75">Total Pengguna</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="ios-table mb-4">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Kelas</th>
                    <th>Kelompok</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ios-user-avatar" style="width: 32px; height: 32px; font-size: 14px; margin-right: 12px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-medium">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->kelas ?: '-' }}</td>
                    <td>{{ $user->kelompok ?: '-' }}</td>
                    <td>
                        @if($user->role == 'admin')
                            <span class="ios-badge" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">Admin</span>
                        @else
                            <span class="ios-badge" style="background: rgba(142, 142, 147, 0.1); color: var(--ios-gray);">Siswa</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="ios-btn" style="padding: 6px 12px; font-size: 14px; background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Info Box -->
    <div class="ios-card">
        <div class="ios-card-header">
            <h5 class="ios-card-title mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h5>
        </div>
        <div class="ios-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <span class="ios-badge ios-badge-success me-2">Admin</span>
                            <div>
                                <p class="mb-0 text-sm">Dapat mengelola semua data sistem inventory</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="ios-badge" style="background: rgba(142, 142, 147, 0.1); color: var(--ios-gray);" class="me-2">Siswa</span>
                            <div>
                                <p class="mb-0 text-sm">Dapat mengajukan peminjaman alat dan melihat riwayat</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <i class="bi bi-shield-exclamation text-warning me-2 mt-1"></i>
                            <p class="mb-0 text-sm">Pengguna dengan status "Belum Verifikasi" perlu memverifikasi email</p>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle text-danger me-2 mt-1"></i>
                            <p class="mb-0 text-sm">Anda tidak dapat menghapus akun Anda sendiri</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection