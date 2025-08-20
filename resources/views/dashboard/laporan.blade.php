@extends('layouts.ios16')

@section('title', 'Laporan Peminjaman - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1">Laporan Peminjaman</h1>
            <p class="text-secondary mb-0">Analisis dan ringkasan data peminjaman alat</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="ios-btn ios-btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filter Laporan
                    </h5>
                </div>
                <div class="ios-card-body">
                    <form action="{{ route('laporan') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" class="ios-form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai', now()->subDays(30)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" class="ios-form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select class="ios-form-control" id="status" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <button type="submit" class="ios-btn ios-btn-primary">
                                <i class="bi bi-search me-2"></i>Filter
                            </button>
                            <a href="{{ route('laporan') }}" class="ios-btn ios-btn-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </a>
                            <button type="button" class="ios-btn" onclick="window.print()" style="background: var(--ios-green); color: white;">
                                <i class="bi bi-printer me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #5856D6); color: white;">
                <div class="ios-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $totalPeminjaman }}</h3>
                            <p class="mb-0 opacity-75">Total Peminjaman</p>
                        </div>
                        <div>
                            <i class="bi bi-journal-text" style="font-size: 2.5rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #FF6B35); color: white;">
                <div class="ios-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $menungguVerifikasi }}</h3>
                            <p class="mb-0 opacity-75">Menunggu Verifikasi</p>
                        </div>
                        <div>
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), #007AFF); color: white;">
                <div class="ios-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $dipinjam }}</h3>
                            <p class="mb-0 opacity-75">Sedang Dipinjam</p>
                        </div>
                        <div>
                            <i class="bi bi-clock" style="font-size: 2.5rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #30D158); color: white;">
                <div class="ios-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $dikembalikan }}</h3>
                            <p class="mb-0 opacity-75">Dikembalikan</p>
                        </div>
                        <div>
                            <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-1">
                        <i class="bi bi-table me-2"></i>Data Peminjaman
                    </h5>
                    <small class="text-secondary">
                        ({{ request('tanggal_mulai', now()->subDays(30)->format('d/m/Y')) }} - 
                        {{ request('tanggal_akhir', now()->format('d/m/Y')) }})
                    </small>
                </div>
                <div class="ios-card-body p-0">
                    @if($peminjamans->count() > 0)
                        <div class="ios-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Peminjam</th>
                                        <th>Kode Alat</th>
                                        <th>Nama Alat</th>
                                        <th>Qty</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamans as $index => $peminjaman)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $peminjaman->user->name }}</td>
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
                                        <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <span class="ios-badge ios-badge-{{ $peminjaman->status == 'menunggu_verifikasi' ? 'warning' : ($peminjaman->status == 'dipinjam' ? 'success' : 'success') }}">
                                                {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $peminjaman->keterangan ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4 px-3">
                            {{ $peminjamans->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--ios-gray3);"></i>
                            </div>
                            <p class="text-secondary mb-0">Tidak ada data peminjaman untuk periode yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles (hidden in normal view) -->
    <style>
        @media print {
            body * {
                visibility: visible;
            }
            .container {
                width: 100%;
                max-width: 100%;
            }
            .card {
                border: 1px solid #ddd;
            }
            .btn, nav, .no-print {
                display: none !important;
            }
            .card-header {
                background-color: #f8f9fa !important;
                color: #000 !important;
            }
            .badge {
                border: 1px solid #000;
                padding: 5px;
                color: #000 !important;
            }
            .bg-primary, .bg-warning, .bg-info, .bg-success {
                background-color: #fff !important;
                color: #000 !important;
            }
            .text-white {
                color: #000 !important;
            }
            @page {
                size: landscape;
                margin: 1cm;
            }
        }
    </style>
</div>
@endsection