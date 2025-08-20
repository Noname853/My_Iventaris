@extends('layouts.ios16')

@section('title', 'Daftar Peminjaman - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1">Daftar Peminjaman</h1>
            <p class="text-secondary mb-0">
                @if(auth()->user()->isSiswa())
                    Riwayat peminjaman {{ auth()->user()->name }}
                @else
                    Kelola semua peminjaman alat praktikum
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->isSiswa())
                <a href="{{ route('peminjaman.create') }}" class="ios-btn ios-btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Ajukan Peminjaman
                </a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('laporan') }}" class="ios-btn ios-btn-secondary" style="background: var(--ios-teal); color: white;">
                    <i class="bi bi-graph-up me-2"></i>Laporan
                </a>
            @endif
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filter & Pencarian
                    </h5>
                </div>
                <div class="ios-card-body">
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1">Pencarian</label>
                            <input type="text" class="ios-form-control" name="search" placeholder="Peminjam atau alat..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold mb-1">Status</label>
                            <select class="ios-form-control" name="status">
                                <option value="">Semua Status</option>
                                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold mb-1">Tanggal Mulai</label>
                            <input type="date" class="ios-form-control" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold mb-1">Tanggal Akhir</label>
                            <input type="date" class="ios-form-control" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="ios-btn ios-btn-primary flex-grow-1">
                                <i class="bi bi-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-secondary flex-grow-1">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-3 rounded-3" style="background: rgba(52, 199, 89, 0.1); border: 1px solid rgba(52, 199, 89, 0.2); color: var(--ios-green);">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded-3" style="background: rgba(255, 59, 48, 0.1); border: 1px solid rgba(255, 59, 48, 0.2); color: var(--ios-red);">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards (Admin Only) -->
    @if(auth()->user()->isAdmin())
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #5856D6); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['menunggu_verifikasi'] ?? 0 }}</h3>
                    <p class="mb-0 opacity-75">Menunggu Verifikasi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #FF6B35); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['dipinjam'] ?? 0 }}</h3>
                    <p class="mb-0 opacity-75">Sedang Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #30D158); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['dikembalikan'] ?? 0 }}</h3>
                    <p class="mb-0 opacity-75">Dikembalikan</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), #007AFF); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['total'] ?? $peminjamans->total() }}</h3>
                    <p class="mb-0 opacity-75">Total Peminjaman</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Peminjaman Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">Daftar Peminjaman</h5>
                </div>
                <div class="ios-card-body p-0">
                    <div class="ios-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Kelas</th>
                                    <th>Kelompok</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamans as $peminjaman)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $peminjaman->user->name }}</td>
                                    <td>{{ $peminjaman->user->kelas }}</td>
                                    <td>{{ $peminjaman->user->kelompok }}</td>
                                    <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                    <td>
                                        @if($peminjaman->status == 'menunggu_verifikasi')
                                            <span class="ios-badge ios-badge-warning">Menunggu Verifikasi</span>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            <span class="ios-badge ios-badge-success">Dipinjam</span>
                                        @else
                                            <span class="ios-badge ios-badge-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('peminjaman.show', $peminjaman->id) }}" class="ios-btn" style="background: var(--ios-gray6); color: var(--ios-blue); font-size: 12px; padding: 4px 8px;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                                @if($peminjaman->status == 'menunggu_verifikasi')
                                                    <form action="{{ route('peminjaman.verifikasi', $peminjaman) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="ios-btn" style="background: var(--ios-green); color: white; font-size: 12px; padding: 4px 8px;" 
                                                                onclick="return confirm('Verifikasi peminjaman ini?')" title="Verifikasi">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @elseif($peminjaman->status == 'dipinjam')
                                                    <form action="{{ route('peminjaman.kembalikan', $peminjaman) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="ios-btn" style="background: var(--ios-orange); color: white; font-size: 12px; padding: 4px 8px;" 
                                                                onclick="return confirm('Tandai sebagai dikembalikan?')" title="Kembalikan">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h6 class="ios-card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Keterangan Status
                    </h6>
                </div>
                <div class="ios-card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="ios-badge ios-badge-warning me-2">Menunggu Verifikasi</span>
                                <small class="text-secondary">Pengajuan baru, menunggu persetujuan admin</small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="ios-badge ios-badge-success me-2">Dipinjam</span>
                                <small class="text-secondary">Alat sedang dipinjam oleh siswa</small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="ios-badge ios-badge-success me-2">Dikembalikan</span>
                                <small class="text-secondary">Alat telah dikembalikan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection