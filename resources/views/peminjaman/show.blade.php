@extends('layouts.ios16')

@section('title', 'Detail Peminjaman - Inventory TKJ')

@push('nav-left')
    <button class="ios-btn ios-btn-secondary" onclick="history.back()" style="padding: 8px 12px; margin-right: 12px;">
        <i class="bi bi-chevron-left"></i>
        Kembali
    </button>
@endpush

@push('nav-right')
    @if(auth()->user()->isAdmin())
        @if($peminjaman->status == 'menunggu_verifikasi')
            <form action="{{ route('peminjaman.verifikasi', $peminjaman) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="ios-btn ios-btn-success" onclick="return confirm('Verifikasi peminjaman ini?')" style="padding: 8px 12px; margin-right: 8px;">
                    <i class="bi bi-check-circle"></i>
                    Verifikasi
                </button>
            </form>
        @endif
        
        @if($peminjaman->status == 'dipinjam')
            <form action="{{ route('peminjaman.kembalikan', $peminjaman) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="ios-btn" style="background: var(--ios-orange); color: white; padding: 8px 12px;" onclick="return confirm('Tandai sebagai dikembalikan?')">
                    <i class="bi bi-arrow-clockwise"></i>
                    Kembalikan
                </button>
            </form>
        @endif
    @endif
    
    @if(auth()->user()->isSiswa() && $peminjaman->user_id == auth()->id() && $peminjaman->status == 'menunggu_verifikasi')
        <form action="{{ route('peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan peminjaman ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="ios-btn ios-btn-danger" style="padding: 8px 12px;">
                <i class="bi bi-x-circle"></i>
                Batalkan
            </button>
        </form>
    @endif
@endpush

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-green);">
        <div class="ios-card-body" style="background: rgba(52, 199, 89, 0.1);">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2" style="color: var(--ios-green); font-size: 20px;"></i>
                <span style="color: var(--ios-green); font-weight: 500;">{{ session('success') }}</span>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-red);">
        <div class="ios-card-body" style="background: rgba(255, 59, 48, 0.1);">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2" style="color: var(--ios-red); font-size: 20px;"></i>
                <span style="color: var(--ios-red); font-weight: 500;">{{ session('error') }}</span>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <!-- Status Card -->
    <div class="col-12 mb-4">
        <div class="ios-card">
            @php
                $statusConfig = [
                    'menunggu_verifikasi' => [
                        'color' => 'var(--ios-orange)',
                        'bg' => 'rgba(255, 149, 0, 0.1)',
                        'icon' => 'bi-clock',
                        'text' => 'Menunggu Verifikasi'
                    ],
                    'dipinjam' => [
                        'color' => 'var(--ios-blue)',
                        'bg' => 'rgba(0, 122, 255, 0.1)',
                        'icon' => 'bi-hand-thumbs-up',
                        'text' => 'Sedang Dipinjam'
                    ],
                    'dikembalikan' => [
                        'color' => 'var(--ios-green)',
                        'bg' => 'rgba(52, 199, 89, 0.1)',
                        'icon' => 'bi-check-circle',
                        'text' => 'Dikembalikan'
                    ],
                    'dibatalkan' => [
                        'color' => 'var(--ios-red)',
                        'bg' => 'rgba(255, 59, 48, 0.1)',
                        'icon' => 'bi-x-circle',
                        'text' => 'Dibatalkan'
                    ]
                ];
                $config = $statusConfig[$peminjaman->status] ?? $statusConfig['menunggu_verifikasi'];
            @endphp
            
            <div class="ios-card-header text-center" style="background: {{ $config['bg'] }}; border-bottom: 0.5px solid {{ $config['color'] }};">
                <div class="text-center">
                    <i class="bi {{ $config['icon'] }}" style="font-size: 3rem; color: {{ $config['color'] }}; margin-bottom: 16px;"></i>
                    <h4 style="color: var(--ios-label); font-weight: 600; margin: 0;">{{ $config['text'] }}</h4>
                    <span class="ios-badge" style="background: {{ $config['color'] }}; color: white; margin-top: 8px; display: inline-block;">
                        Peminjaman #{{ $peminjaman->id }}
                    </span>
                </div>
            </div>
            
            @if($peminjaman->status != 'dibatalkan')
            <div class="ios-card-body" style="padding: 20px;">
                <!-- Progress Bar iOS Style -->
                @php
                    $progress = $peminjaman->status == 'menunggu_verifikasi' ? 33 : ($peminjaman->status == 'dipinjam' ? 66 : 100);
                @endphp
                <div style="background: var(--ios-gray6); height: 8px; border-radius: 4px; margin-bottom: 24px; overflow: hidden;">
                    <div style="background: {{ $config['color'] }}; height: 100%; width: {{ $progress }}%; border-radius: 4px; transition: width 0.3s ease;"></div>
                </div>
                
                <!-- Timeline iOS Style -->
                <div class="d-flex justify-content-between" style="margin-top: 20px;">
                    <div class="text-center" style="flex: 1;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; background: {{ $peminjaman->status != 'menunggu_verifikasi' ? 'var(--ios-green)' : 'var(--ios-orange)' }};">
                            <i class="bi bi-send" style="color: white; font-size: 14px;"></i>
                        </div>
                        <small style="color: var(--ios-secondary-label); font-weight: 500;">Diajukan</small>
                    </div>
                    <div class="text-center" style="flex: 1;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; background: {{ $peminjaman->status == 'dipinjam' || $peminjaman->status == 'dikembalikan' ? 'var(--ios-green)' : 'var(--ios-gray4)' }};">
                            <i class="bi bi-check-lg" style="color: white; font-size: 14px;"></i>
                        </div>
                        <small style="color: var(--ios-secondary-label); font-weight: 500;">Diverifikasi</small>
                    </div>
                    <div class="text-center" style="flex: 1;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; background: {{ $peminjaman->status == 'dikembalikan' ? 'var(--ios-green)' : 'var(--ios-gray4)' }};">
                            <i class="bi bi-arrow-clockwise" style="color: white; font-size: 14px;"></i>
                        </div>
                        <small style="color: var(--ios-secondary-label); font-weight: 500;">Dikembalikan</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Informasi Peminjaman -->
        <div class="ios-card mb-4">
            <div class="ios-card-header">
                <h5 class="ios-card-title"><i class="bi bi-info-circle me-2"></i>Informasi Peminjaman</h5>
            </div>
            <div class="ios-card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">ID Peminjaman</div>
                    <div class="col-sm-8">
                        <span class="ios-badge" style="background: var(--ios-gray6); color: var(--ios-label);">#{{ $peminjaman->id }}</span>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Peminjam</div>
                    <div class="col-sm-8">
                        <div class="d-flex align-items-center gap-2">
                            <div class="ios-user-avatar" style="width: 32px; height: 32px; font-size: 14px;">
                                {{ strtoupper(substr($peminjaman->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--ios-label);">{{ $peminjaman->user->name }}</div>
                                <small style="color: var(--ios-secondary-label);">{{ $peminjaman->user->email }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Tanggal Pengajuan</div>
                    <div class="col-sm-8">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Tanggal Pinjam</div>
                    <div class="col-sm-8">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</div>
                </div>
                @if($peminjaman->tanggal_batas_kembali)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Tanggal Batas Kembali</div>
                    <div class="col-sm-8">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-check" style="color: var(--ios-orange);"></i>
                            <span>{{ \Carbon\Carbon::parse($peminjaman->tanggal_batas_kembali)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($peminjaman->lama_pinjam)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Durasi Pinjam</div>
                    <div class="col-sm-8">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock" style="color: var(--ios-blue);"></i>
                            <span>
                                @php
                                    $jam = floor($peminjaman->lama_pinjam);
                                    $menit = ($peminjaman->lama_pinjam - $jam) * 60;
                                @endphp
                                @if($jam > 0)
                                    {{ $jam }} jam
                                    @if($menit > 0)
                                        {{ round($menit) }} menit
                                    @endif
                                @else
                                    {{ round($menit) }} menit
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Tanggal Kembali</div>
                    <div class="col-sm-8">
                        @if($peminjaman->tanggal_kembali)
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill" style="color: var(--ios-green);"></i>
                                <span>{{ $peminjaman->tanggal_kembali->format('d/m/Y H:i') }}</span>
                            </div>
                        @else
                            <span style="color: var(--ios-secondary-label);">Belum dikembalikan</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Total Items</div>
                    <div class="col-sm-8">
                        <span class="ios-badge" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">{{ $peminjaman->total_items }} unit</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Keperluan</div>
                    <div class="col-sm-8 fw-semibold">{{ $peminjaman->keperluan }}</div>
                </div>
                @if($peminjaman->catatan)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Catatan</div>
                    <div class="col-sm-8">{{ $peminjaman->catatan }}</div>
                </div>
                @endif
                
                @if($peminjaman->status == 'dikembalikan' && $peminjaman->catatan_pengembalian)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Catatan Pengembalian</div>
                    <div class="col-sm-8">{{ $peminjaman->catatan_pengembalian }}</div>
                </div>
                @endif
                
                @if($peminjaman->status == 'dibatalkan' && $peminjaman->alasan_pembatalan)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Alasan Pembatalan</div>
                    <div class="col-sm-8">{{ $peminjaman->alasan_pembatalan }}</div>
                </div>
                @endif
                
                @if($peminjaman->tanggal_verifikasi)
                <div class="row mb-3">
                    <div class="col-sm-4 fw-medium text-secondary">Tanggal Verifikasi</div>
                    <div class="col-sm-8">{{ $peminjaman->tanggal_verifikasi->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Details -->
    <div class="col-lg-4">
        <div class="ios-card mb-4">
            <div class="ios-card-header">
                <h5 class="ios-card-title"><i class="bi bi-tools me-2"></i>Detail Alat</h5>
            </div>
            <div class="ios-card-body">
                @foreach($peminjaman->peminjamanDetails as $detail)
                <div style="border: 0.5px solid var(--ios-gray5); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1" style="color: var(--ios-label); font-weight: 600;">{{ $detail->alat->nama }}</h6>
                            <small style="color: var(--ios-secondary-label);">{{ $detail->alat->kode }}</small>
                        </div>
                        <span class="ios-badge" style="background: var(--ios-blue); color: white;">{{ $detail->jumlah }} unit</span>
                    </div>
                    
                    <div class="row text-sm">
                        <div class="col-6">
                            <strong style="color: var(--ios-secondary-label); font-size: 13px;">Kategori:</strong><br>
                            <span class="ios-badge" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">{{ $detail->alat->kategori }}</span>
                        </div>
                        <div class="col-6">
                            <strong style="color: var(--ios-secondary-label); font-size: 13px;">Lokasi:</strong><br>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <i class="bi bi-geo-alt-fill" style="color: var(--ios-red); font-size: 12px;"></i>
                                <span style="color: var(--ios-label); font-size: 14px;">{{ $detail->alat->lokasi }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($detail->keterangan)
                    <div class="mt-2">
                        <strong style="color: var(--ios-secondary-label); font-size: 13px;">Keterangan:</strong><br>
                        <span style="color: var(--ios-label); font-size: 14px;">{{ $detail->keterangan }}</span>
                    </div>
                    @endif
                    
                    <div class="mt-2">
                        <a href="{{ route('alat.show', $detail->alat) }}" class="ios-btn" style="background: var(--ios-gray6); color: var(--ios-blue); padding: 6px 12px; font-size: 14px;">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Status Information Cards -->
@if($peminjaman->status == 'menunggu_verifikasi')
<div class="ios-card mt-4" style="border-left: 4px solid var(--ios-orange);">
    <div class="ios-card-body" style="background: rgba(255, 149, 0, 0.1);">
        <div class="d-flex align-items-center">
            <i class="bi bi-clock-fill me-3" style="color: var(--ios-orange); font-size: 24px;"></i>
            <div>
                <h6 style="color: var(--ios-orange); margin: 0; font-weight: 600;">Menunggu Verifikasi</h6>
                <p style="margin: 0; color: var(--ios-label); font-size: 14px;">
                    @if(auth()->user()->isAdmin())
                        Peminjaman ini menunggu verifikasi dari Anda. Silakan periksa detail dan lakukan verifikasi.
                    @else
                        Peminjaman Anda sedang menunggu verifikasi dari admin.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@elseif($peminjaman->status == 'dipinjam')
<div class="ios-card mt-4" style="border-left: 4px solid var(--ios-blue);">
    <div class="ios-card-body" style="background: rgba(0, 122, 255, 0.1);">
        <div class="d-flex align-items-center">
            <i class="bi bi-hand-thumbs-up-fill me-3" style="color: var(--ios-blue); font-size: 24px;"></i>
            <div>
                <h6 style="color: var(--ios-blue); margin: 0; font-weight: 600;">Sedang Dipinjam</h6>
                <p style="margin: 0; color: var(--ios-label); font-size: 14px;">
                    @if(auth()->user()->isAdmin())
                        Alat sedang dipinjam oleh {{ $peminjaman->user->name }}. Tandai sebagai dikembalikan setelah alat dikembalikan.
                    @else
                        Alat sedang Anda pinjam. Pastikan untuk mengembalikan alat dalam kondisi baik.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@elseif($peminjaman->status == 'dikembalikan')
<div class="ios-card mt-4" style="border-left: 4px solid var(--ios-green);">
    <div class="ios-card-body" style="background: rgba(52, 199, 89, 0.1);">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-3" style="color: var(--ios-green); font-size: 24px;"></i>
            <div>
                <h6 style="color: var(--ios-green); margin: 0; font-weight: 600;">Dikembalikan</h6>
                <p style="margin: 0; color: var(--ios-label); font-size: 14px;">
                    Peminjaman telah selesai. Alat telah dikembalikan 
                    @if($peminjaman->tanggal_kembali)
                        pada {{ $peminjaman->tanggal_kembali->format('d/m/Y H:i') }}.
                    @else
                        (waktu tidak tercatat).
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@elseif($peminjaman->status == 'dibatalkan')
<div class="ios-card mt-4" style="border-left: 4px solid var(--ios-red);">
    <div class="ios-card-body" style="background: rgba(255, 59, 48, 0.1);">
        <div class="d-flex align-items-center">
            <i class="bi bi-x-circle-fill me-3" style="color: var(--ios-red); font-size: 24px;"></i>
            <div>
                <h6 style="color: var(--ios-red); margin: 0; font-weight: 600;">Dibatalkan</h6>
                <p style="margin: 0; color: var(--ios-label); font-size: 14px;">
                    Peminjaman telah dibatalkan 
                    @if($peminjaman->tanggal_batal)
                        pada {{ $peminjaman->tanggal_batal->format('d/m/Y H:i') }}.
                    @else
                        (waktu tidak tercatat).
                    @endif
                    @if($peminjaman->alasan_pembatalan)
                        <br><strong>Alasan:</strong> {{ $peminjaman->alasan_pembatalan }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
