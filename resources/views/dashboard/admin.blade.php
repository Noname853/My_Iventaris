@extends('layouts.ios16')

@section('title', 'Dashboard Admin - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1">Dashboard Admin</h1>
            <p class="text-secondary mb-0">Selamat datang di panel administrasi</p>
        </div>
        <div>
            <span class="ios-badge ios-badge-danger">Admin</span>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-people" style="font-size: 2.5rem; color: var(--ios-blue);"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Total User</h5>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--ios-label);">{{ $totalUser }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-tools" style="font-size: 2.5rem; color: var(--ios-green);"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Total Alat</h5>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--ios-label);">{{ $totalAlat }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-journal-text" style="font-size: 2.5rem; color: var(--ios-orange);"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">Total Peminjaman</h5>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--ios-label);">{{ $totalPeminjaman }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EOS/EOL Warning Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-x-circle" style="font-size: 2rem; color: var(--ios-red);"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">EOS Berakhir</h6>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--ios-red);">{{ $eosExpired }}</div>
                    <small class="text-secondary">Alat sudah melewati End of Service</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle" style="font-size: 2rem; color: var(--ios-gray);"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">EOL Berakhir</h6>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--ios-gray);">{{ $eolExpired }}</div>
                    <small class="text-secondary">Alat sudah melewati End of Life</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: var(--ios-orange);"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">EOS dalam 30 hari</h6>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--ios-orange);">{{ $eosWarning }}</div>
                    <small class="text-secondary">Alat mendekati End of Service</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="ios-card h-100">
                <div class="ios-card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-info-circle" style="font-size: 2rem; color: var(--ios-teal);"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">EOL dalam 90 hari</h6>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--ios-teal);">{{ $eolWarning }}</div>
                    <small class="text-secondary">Alat mendekati End of Life</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EOS/EOL Notifications -->
    @if(count($eosEolNotifications) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="ios-card">
                    <div class="ios-card-header">
                        <h5 class="ios-card-title mb-0">
                            <i class="bi bi-bell-fill me-2"></i>Notifikasi EOS/EOL
                        </h5>
                    </div>
                    <div class="ios-card-body">
                        @foreach($eosEolNotifications as $notification)
                            <div class="d-flex align-items-start p-3 mb-3 rounded-3" 
                                 style="background: {{ $notification['type'] == 'danger' ? 'rgba(255, 59, 48, 0.1)' : ($notification['type'] == 'warning' ? 'rgba(255, 149, 0, 0.1)' : 'rgba(0, 122, 255, 0.1)') }}; border: 1px solid {{ $notification['type'] == 'danger' ? 'rgba(255, 59, 48, 0.2)' : ($notification['type'] == 'warning' ? 'rgba(255, 149, 0, 0.2)' : 'rgba(0, 122, 255, 0.2)') }};">
                                <div class="me-3">
                                    <i class="bi bi-{{ $notification['type'] == 'danger' ? 'exclamation-triangle-fill' : ($notification['type'] == 'warning' ? 'exclamation-circle-fill' : 'info-circle-fill') }}" 
                                       style="font-size: 1.5rem; color: var(--ios-{{ $notification['type'] == 'danger' ? 'red' : ($notification['type'] == 'warning' ? 'orange' : 'blue') }});"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">{{ $notification['title'] }}</h6>
                                    <p class="mb-2 text-secondary">{{ $notification['message'] }}</p>
                                    <div class="mb-2">
                                        @foreach($notification['alats'] as $alat)
                                            <span class="ios-badge ios-badge-{{ $notification['type'] == 'danger' ? 'danger' : ($notification['type'] == 'warning' ? 'warning' : 'success') }} me-1">{{ $alat->nama }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-{{ $notification['type'] == 'danger' ? 'danger' : ($notification['type'] == 'warning' ? 'secondary' : 'primary') }}" style="font-size: 14px; padding: 8px 16px;">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('alat.create') }}" class="ios-btn ios-btn-primary w-100 justify-content-start">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Alat
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-secondary w-100 justify-content-start" style="background: var(--ios-orange); color: white;">
                                <i class="bi bi-journal-text me-2"></i>Kelola Peminjaman
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('laporan') }}" class="ios-btn ios-btn-secondary w-100 justify-content-start" style="background: var(--ios-teal); color: white;">
                                <i class="bi bi-graph-up me-2"></i>Lihat Laporan
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="ios-btn ios-btn-secondary w-100 justify-content-start" style="background: var(--ios-green); color: white;">
                                <i class="bi bi-people-fill me-2"></i>Kelola User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kelompok dan Peminjaman Aktif -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-people-fill me-2"></i>Daftar Kelompok Siswa & Peminjaman Aktif
                    </h5>
                </div>
                <div class="ios-card-body p-0">
                    <div class="ios-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Kelompok</th>
                                    <th>Kelas</th>
                                    <th>Ketua</th>
                                    <th>Anggota</th>
                                    <th>Alat Sedang Dipinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $kelompokList = \App\Models\User::whereNotNull('kelompok')->where('role', 'siswa')->get()->groupBy(function($item) {
                                        return $item->kelas.'|'.$item->kelompok;
                                    });
                                @endphp
                                @foreach($kelompokList as $key => $users)
                                    @php
                                        $first = $users->first();
                                        $anggota = $first->anggota_kelompok ? json_decode($first->anggota_kelompok, true) : [];
                                        $alatDipinjam = \App\Models\Peminjaman::with('peminjamanDetails.alat')
                                            ->where('user_id', $first->id)
                                            ->where('status', 'dipinjam')
                                            ->get();
                                    @endphp
                                    <tr>
                                        <td>{{ $first->kelompok }}</td>
                                        <td>{{ $first->kelas }}</td>
                                        <td>{{ $first->name }}</td>
                                        <td>
                                            <ul class="mb-0" style="list-style: none; padding-left: 0;">
                                                <li class="mb-1"><strong>{{ $first->name }} <span class="ios-badge ios-badge-success">Ketua</span></strong></li>
                                                @foreach($anggota as $a)
                                                    <li class="text-secondary">{{ $a }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @if($alatDipinjam->count() > 0)
                                                <ul class="mb-0" style="list-style: none; padding-left: 0;">
                                                @foreach($alatDipinjam as $p)
                                                    @foreach($p->peminjamanDetails as $d)
                                                        <li class="mb-1"><span class="ios-badge ios-badge-warning">{{ $d->alat->nama }} ({{ $d->qty }})</span></li>
                                                    @endforeach
                                                @endforeach
                                                </ul>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
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
    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="ios-card h-100">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru
                    </h5>
                </div>
                <div class="ios-card-body">
                    @if($peminjamanTerbaru->count() > 0)
                        <div class="ios-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Alat</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjamanTerbaru as $peminjaman)
                                    <tr>
                                        <td>{{ $peminjaman->user->name }}</td>
                                        <td>
                                            @if($peminjaman->peminjamanDetails->count() == 1)
                                                {{ $peminjaman->peminjamanDetails->first()->alat->nama }}
                                            @else
                                                {{ $peminjaman->peminjamanDetails->count() }} jenis alat
                                            @endif
                                        </td>
                                        <td>
                                            <span class="ios-badge ios-badge-{{ $peminjaman->status == 'menunggu_verifikasi' ? 'warning' : ($peminjaman->status == 'dipinjam' ? 'success' : 'success') }}">
                                                {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-primary" style="font-size: 14px; padding: 8px 16px;">
                                Lihat Semua Peminjaman
                            </a>
                        </div>
                    @else
                        <p class="text-secondary text-center">Belum ada peminjaman.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="ios-card h-100">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Alat Stok Rendah
                    </h5>
                </div>
                <div class="ios-card-body">
                    @if($alatStokRendah->count() > 0)
                        <div class="ios-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Alat</th>
                                        <th>Stok</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alatStokRendah as $alat)
                                    <tr>
                                        <td>{{ $alat->kode }}</td>
                                        <td>{{ $alat->nama }}</td>
                                        <td>
                                            <span class="ios-badge ios-badge-{{ $alat->stok == 0 ? 'danger' : 'warning' }}">
                                                {{ $alat->stok }}
                                            </span>
                                        </td>
                                        <td>{{ $alat->lokasi }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-secondary" style="background: var(--ios-orange); color: white; font-size: 14px; padding: 8px 16px;">
                                Kelola Stok Alat
                            </a>
                        </div>
                    @else
                        <p class="text-secondary text-center">Semua alat memiliki stok yang cukup.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection