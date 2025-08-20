@extends('layouts.ios16')

@section('title', 'Dashboard Siswa - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1">Dashboard Siswa</h1>
            <p class="text-secondary mb-0">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div>
            <span class="ios-badge" style="background: var(--ios-blue); color: white;">Siswa</span>
        </div>
    </div>
    
    <!-- Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), var(--ios-blue)); color: white;">
                <div class="ios-card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}!</h5>
                            <p class="mb-0 opacity-75">Gunakan sistem ini untuk meminjam alat-alat praktikum TKJ. Pastikan untuk mengembalikan alat tepat waktu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Kelompok -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-people me-2"></i>Anggota Kelompok Anda
                    </h5>
                </div>
                <div class="ios-card-body">
                    <div class="ios-table mb-3">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $anggotaKelompok = auth()->user()->anggota_kelompok ? json_decode(auth()->user()->anggota_kelompok, true) : [];
                                @endphp
                                <tr>
                                    <td><strong>{{ auth()->user()->name }} <span class="ios-badge ios-badge-success">Ketua</span></strong></td>
                                </tr>
                                @foreach($anggotaKelompok as $anggota)
                                    <tr>
                                        <td>{{ $anggota }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <form action="{{ route('kelompok.tambah') }}" method="POST" class="d-flex gap-2 align-items-end">
                        @csrf
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold mb-1">Tambah Anggota Baru</label>
                            <input type="text" name="name" class="ios-form-control" placeholder="Nama siswa baru" required>
                        </div>
                        <div>
                            <button type="submit" class="ios-btn ios-btn-primary">
                                <i class="bi bi-person-plus me-1"></i> Tambah Anggota
                            </button>
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="mt-3 p-3 rounded-3" style="background: rgba(52, 199, 89, 0.1); border: 1px solid rgba(52, 199, 89, 0.2); color: var(--ios-green);">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-list fa-2x text-primary mb-2"></i>
                    <h5 class="fw-bold mb-1">Peminjaman Saya</h5>
                    <div class="fs-3 fw-semibold text-dark">{{ isset($totalPeminjaman) ? $totalPeminjaman : 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-tools fa-2x text-success mb-2"></i>
                    <h5 class="fw-bold mb-1">Alat Dipinjam</h5>
                    <div class="fs-3 fw-semibold text-dark">{{ isset($totalAlatDipinjam) ? $totalAlatDipinjam : 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-lightning-fill me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="ios-card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-primary w-100 justify-content-start">
                                <i class="bi bi-search me-2"></i>Lihat Alat Tersedia
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('peminjaman.create') }}" class="ios-btn w-100 justify-content-start" style="background: var(--ios-green); color: white;">
                                <i class="bi bi-plus-lg me-2"></i>Ajukan Peminjaman
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('peminjaman.index') }}" class="ios-btn w-100 justify-content-start" style="background: var(--ios-teal); color: white;">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Borrowings -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-clock me-2"></i>Peminjaman Aktif
                    </h5>
                </div>
                <div class="ios-card-body">
                    @if($peminjamanAktifList->count() > 0)
                        <div class="ios-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Alat</th>
                                        <th>Nama Alat</th>
                                        <th>Qty</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamanAktifList as $peminjaman)
                                    <tr>
                                        <td>
                                            @if($peminjaman->peminjamanDetails->count() == 1)
                                                {{ $peminjaman->peminjamanDetails->first()->alat->kode }}
                                            @else
                                                {{ $peminjaman->peminjamanDetails->count() }} item
                                            @endif
                                        </td>
                                        <td>
                                            @if($peminjaman->peminjamanDetails->count() == 1)
                                                {{ $peminjaman->peminjamanDetails->first()->alat->nama }}
                                            @else
                                                {{ $peminjaman->peminjamanDetails->count() }} jenis alat
                                            @endif
                                        </td>
                                        <td>{{ $peminjaman->total_items }}</td>
                                        <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="ios-badge ios-badge-{{ $peminjaman->status == 'menunggu_verifikasi' ? 'warning' : 'success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('peminjaman.show', $peminjaman) }}" class="ios-btn ios-btn-primary" style="font-size: 14px; padding: 6px 12px;">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                                @if($peminjaman->status == 'menunggu_verifikasi')
                                                    <form action="{{ route('peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan peminjaman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ios-btn ios-btn-danger" style="font-size: 14px; padding: 6px 12px;">
                                                            <i class="bi bi-x"></i> Batal
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--ios-gray3);"></i>
                            </div>
                            <p class="text-secondary mb-3">Anda belum memiliki peminjaman aktif.</p>
                            <a href="{{ route('peminjaman.create') }}" class="ios-btn ios-btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Ajukan Peminjaman Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent History -->
    <div class="row">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Terbaru
                    </h5>
                </div>
                <div class="ios-card-body">
                    @if($recentPeminjaman->count() > 0)
                        <div class="ios-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Alat</th>
                                        <th>Nama Alat</th>
                                        <th>Qty</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPeminjaman as $peminjaman)
                                    <tr>
                                        <td>
                                            @if($peminjaman->peminjamanDetails->count() == 1)
                                                {{ $peminjaman->peminjamanDetails->first()->alat->kode }}
                                            @else
                                                {{ $peminjaman->peminjamanDetails->count() }} item
                                            @endif
                                        </td>
                                        <td>
                                            @if($peminjaman->peminjamanDetails->count() == 1)
                                                {{ $peminjaman->peminjamanDetails->first()->alat->nama }}
                                            @else
                                                {{ $peminjaman->peminjamanDetails->count() }} jenis alat
                                            @endif
                                        </td>
                                        <td>{{ $peminjaman->total_items }}</td>
                                        <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            <span class="ios-badge ios-badge-{{ $peminjaman->status == 'dikembalikan' ? 'success' : ($peminjaman->status == 'dipinjam' ? 'success' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-primary" style="font-size: 14px; padding: 8px 16px;">
                                Lihat Semua Riwayat
                            </a>
                        </div>
                    @else
                        <p class="text-secondary text-center">Belum ada riwayat peminjaman.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection