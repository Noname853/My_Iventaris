@extends('layouts.ios16')

@section('title', 'Detail Pengguna - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1"><i class="bi bi-person-circle me-2"></i>Detail Pengguna</h1>
            <p class="text-secondary mb-0">Informasi lengkap pengguna sistem inventory</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="ios-btn ios-btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-md-4">
            <div class="ios-card mb-4">
                <div class="ios-card-body text-center">
                    <div class="ios-user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 40px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="mb-2">{{ $user->name }}</h4>
                    <p class="text-secondary mb-3">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="ios-badge" style="background: rgba({{ $user->role == 'admin' ? '255, 59, 48' : '0, 122, 255' }}, 0.1); color: var(--ios-{{ $user->role == 'admin' ? 'red' : 'blue' }});">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->id == auth()->id())
                            <span class="ios-badge" style="background: rgba(255, 149, 0, 0.1); color: var(--ios-orange);">Akun Anda</span>
                        @endif
                    </div>
                    @if($user->kelas)
                        <div class="ios-card" style="background: var(--ios-gray6);">
                            <div class="ios-card-body">
                                <small class="text-secondary">Kelas</small>
                                <div class="fw-medium">{{ $user->kelas }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="ios-card">
                <div class="ios-card-header">
                    <h6 class="ios-card-title mb-0"><i class="bi bi-shield-check me-2"></i>Status Akun</h6>
                </div>
                <div class="ios-card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            @if($user->email_verified_at)
                                <div class="ios-card" style="background: rgba(52, 199, 89, 0.1);">
                                    <div class="ios-card-body p-3">
                                        <i class="bi bi-check-circle-fill text-success fs-2 mb-2"></i>
                                        <div class="small fw-medium">Email Terverifikasi</div>
                                        <div class="small text-secondary">{{ $user->email_verified_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="ios-card" style="background: rgba(255, 149, 0, 0.1);">
                                    <div class="ios-card-body p-3">
                                        <i class="bi bi-exclamation-circle-fill text-warning fs-2 mb-2"></i>
                                        <div class="small fw-medium">Belum Verifikasi</div>
                                        <div class="small text-secondary">Email belum diverifikasi</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-6 text-center">
                            <div class="ios-card" style="background: rgba(0, 122, 255, 0.1);">
                                <div class="ios-card-body p-3">
                                    <i class="bi bi-person-check-fill text-primary fs-2 mb-2"></i>
                                    <div class="small fw-medium">Status Aktif</div>
                                    <div class="small text-secondary">Pengguna aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-md-8">
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Pengguna</h5>
                </div>
                <div class="ios-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">ID Pengguna</small>
                                    <div class="fw-medium">#{{ $user->id }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Nama Lengkap</small>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Email</small>
                                    <div class="fw-medium">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Role</small>
                                    <div>
                                        <span class="ios-badge" style="background: rgba({{ $user->role == 'admin' ? '255, 59, 48' : '0, 122, 255' }}, 0.1); color: var(--ios-{{ $user->role == 'admin' ? 'red' : 'blue' }});">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($user->kelas)
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Kelas</small>
                                    <div class="fw-medium">{{ $user->kelas }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($user->kelompok)
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Kelompok</small>
                                    <div class="fw-medium">{{ $user->kelompok }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Status Email</small>
                                    <div>
                                        @if($user->email_verified_at)
                                            <span class="ios-badge ios-badge-success">Terverifikasi</span>
                                            <small class="text-secondary ms-2">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="ios-badge ios-badge-warning">Belum Verifikasi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <small class="text-secondary">Bergabung</small>
                                    <div class="fw-medium">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions -->
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-key me-2"></i>Hak Akses</h5>
                </div>
                <div class="ios-card-body">
                    @if($user->role == 'admin')
                        <div class="ios-card" style="background: rgba(255, 59, 48, 0.05); border: 1px solid rgba(255, 59, 48, 0.1);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-shield-fill me-2" style="color: var(--ios-red);"></i>Administrator</h6>
                                <p class="mb-2" style="color: var(--ios-secondary-label);">Pengguna ini memiliki akses penuh ke sistem:</p>
                                <ul class="mb-0" style="color: var(--ios-secondary-label);">
                                    <li>Mengelola semua data alat</li>
                                    <li>Verifikasi dan mengelola peminjaman</li>
                                    <li>Melihat dan mengunduh laporan</li>
                                    <li>Mengelola pengguna sistem</li>
                                    <li>Mengakses dashboard admin</li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="ios-card" style="background: rgba(0, 122, 255, 0.05); border: 1px solid rgba(0, 122, 255, 0.1);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-person-badge me-2" style="color: var(--ios-blue);"></i>Siswa</h6>
                                <p class="mb-2" style="color: var(--ios-secondary-label);">Pengguna ini memiliki akses terbatas:</p>
                                <ul class="mb-0" style="color: var(--ios-secondary-label);">
                                    <li>Melihat daftar alat yang tersedia</li>
                                    <li>Mengajukan peminjaman alat</li>
                                    <li>Melihat status dan riwayat peminjaman</li>
                                    <li>Mengakses dashboard siswa</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    @if($user->role == 'siswa')
    <div class="ios-card mb-4">
        <div class="ios-card-header">
            <h5 class="ios-card-title mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Aktivitas</h5>
        </div>
        <div class="ios-card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #0056CC);">
                        <div class="ios-card-body text-center text-white">
                            <i class="bi bi-list-ul fs-1 mb-3"></i>
                            <h3 class="mb-2">{{ $user->peminjamans->count() }}</h3>
                            <p class="mb-0 opacity-75">Total Peminjaman</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #E6930A);">
                        <div class="ios-card-body text-center text-white">
                            <i class="bi bi-clock-history fs-1 mb-3"></i>
                            <h3 class="mb-2">{{ $user->peminjamans->where('status', 'menunggu_verifikasi')->count() }}</h3>
                            <p class="mb-0 opacity-75">Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), #32A8E6);">
                        <div class="ios-card-body text-center text-white">
                            <i class="bi bi-arrow-up-circle fs-1 mb-3"></i>
                            <h3 class="mb-2">{{ $user->peminjamans->where('status', 'dipinjam')->count() }}</h3>
                            <p class="mb-0 opacity-75">Sedang Dipinjam</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #2FB344);">
                        <div class="ios-card-body text-center text-white">
                            <i class="bi bi-check-circle fs-1 mb-3"></i>
                            <h3 class="mb-2">{{ $user->peminjamans->where('status', 'dikembalikan')->count() }}</h3>
                            <p class="mb-0 opacity-75">Dikembalikan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="ios-card mb-4">
        <div class="ios-card-header">
            <h5 class="ios-card-title mb-0"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h5>
        </div>
        <div class="ios-card-body">
            @if($user->peminjamans->count() > 0)
                <div class="ios-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Alat</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->peminjamans->take(5) as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($peminjaman->peminjamanDetails->count() == 1)
                                        {{ $peminjaman->peminjamanDetails->first()->alat->nama }}
                                    @else
                                        {{ $peminjaman->peminjamanDetails->count() }} jenis alat
                                    @endif
                                </td>
                                <td>
                                    <span class="ios-badge" style="background: rgba(142, 142, 147, 0.1); color: var(--ios-gray);">
                                        {{ $peminjaman->total_items }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'menunggu_verifikasi' => ['bg' => 'rgba(255, 149, 0, 0.1)', 'color' => 'var(--ios-orange)'],
                                            'dipinjam' => ['bg' => 'rgba(90, 200, 250, 0.1)', 'color' => 'var(--ios-teal)'],
                                            'dikembalikan' => ['bg' => 'rgba(52, 199, 89, 0.1)', 'color' => 'var(--ios-green)']
                                        ];
                                        $colors = $statusColors[$peminjaman->status] ?? ['bg' => 'rgba(142, 142, 147, 0.1)', 'color' => 'var(--ios-gray)'];
                                    @endphp
                                    <span class="ios-badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['color'] }};">
                                        {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('peminjaman.show', $peminjaman) }}" class="ios-btn" style="padding: 4px 8px; font-size: 12px; background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($user->peminjamans->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('peminjaman.index') }}?user={{ $user->id }}" class="ios-btn ios-btn-secondary">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Semua Peminjaman
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-secondary mb-3"></i>
                    <p class="text-secondary mb-0">Belum ada aktivitas peminjaman</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="ios-card">
        <div class="ios-card-header">
            <h5 class="ios-card-title mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
        </div>
        <div class="ios-card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="ios-btn ios-btn-secondary w-100">
                        <i class="bi bi-pencil me-2"></i>Edit Pengguna
                    </a>
                </div>
                @if($user->role == 'siswa')
                <div class="col-md-3 mb-2">
                    <a href="{{ route('peminjaman.index') }}?user={{ $user->id }}" class="ios-btn ios-btn-secondary w-100">
                        <i class="bi bi-list me-2"></i>Lihat Peminjaman
                    </a>
                </div>
                @endif
                @if(!$user->email_verified_at)
                <div class="col-md-3 mb-2">
                    <button class="ios-btn ios-btn-success w-100" onclick="verifyEmail({{ $user->id }})">
                        <i class="bi bi-check me-2"></i>Verifikasi Email
                    </button>
                </div>
                @endif
                @if($user->id != auth()->id())
                <div class="col-md-3 mb-2">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-100" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ios-btn ios-btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Pengguna
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyEmail(userId) {
    if (confirm('Tandai email pengguna ini sebagai terverifikasi?')) {
        // Here you would typically make an AJAX call to verify the email
        // For now, we'll just show a message
        alert('Fitur verifikasi email akan diimplementasikan.');
    }
}
</script>
@endpush
@endsection
