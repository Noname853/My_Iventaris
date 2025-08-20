@extends('layouts.ios16')

@section('title', 'Detail Alat - Inventory TKJ')

@section('page-title', 'Detail Alat')

@push('nav-left')
    <button class="ios-btn ios-btn-secondary" onclick="history.back()" style="padding: 8px 12px; margin-right: 12px;">
        <i class="bi bi-chevron-left"></i>
        Kembali
    </button>
@endpush

@push('nav-right')
    @if(auth()->user()->isAdmin())
        <div class="dropdown">
            <button class="ios-btn ios-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 12px;">
                <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('alat.edit', $alat) }}"><i class="bi bi-pencil me-2"></i>Edit Alat</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('alat.destroy', $alat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alat ini?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-trash me-2"></i>Hapus Alat
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @endif
@endpush

@section('content')
<!-- Hero Image Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="ios-card" style="overflow: hidden;">
            @if($alat->foto)
                <div style="height: 250px; background-image: url('{{ asset('storage/' . $alat->foto) }}'); background-size: cover; background-position: center; position: relative; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#fotoModal">
                    <div style="position: absolute; inset: 0; background: linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.05));"></div>
                    <div style="position: absolute; top: 16px; right: 16px;">
                        @if($alat->stok > 0)
                            @if($alat->stok <= 5)
                                <span class="ios-badge ios-badge-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Stok Rendah
                                </span>
                            @else
                                <span class="ios-badge ios-badge-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Tersedia
                                </span>
                            @endif
                        @else
                            <span class="ios-badge ios-badge-danger">
                                <i class="fas fa-times-circle me-1"></i>
                                Habis
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div style="height: 200px; display: flex; align-items: center; justify-content: center; background: var(--ios-gray6);">
                    <div class="text-center text-muted">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                        <div class="mt-2">Tidak ada foto</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title"><i class="bi bi-info-circle me-2"></i>Informasi Alat</h5>
                </div>
                <div class="ios-card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Nama Alat</div>
                        <div class="col-sm-8 fw-semibold">{{ $alat->nama }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Kode Alat</div>
                        <div class="col-sm-8">
                            <span class="ios-badge" style="background: var(--ios-gray6); color: var(--ios-label);">{{ $alat->kode }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Kategori</div>
                        <div class="col-sm-8">
                            <span class="ios-badge" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">{{ $alat->kategori }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Stok</div>
                        <div class="col-sm-8">
                            <span class="ios-badge ios-badge-{{ $alat->stok == 0 ? 'danger' : ($alat->stok <= 5 ? 'warning' : 'success') }}">
                                <i class="bi bi-{{ $alat->stok == 0 ? 'x-circle' : ($alat->stok <= 5 ? 'exclamation-triangle' : 'check-circle') }} me-1"></i>
                                {{ $alat->stok }} unit
                            </span>
                            @if($alat->stok == 0)
                                <small class="text-danger ms-2">Stok habis</small>
                            @elseif($alat->stok <= 5)
                                <small class="text-warning ms-2">Stok rendah</small>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Lokasi</div>
                        <div class="col-sm-8">
                            <i class="bi bi-geo-alt-fill me-2" style="color: var(--ios-red);"></i>
                            {{ $alat->lokasi }}
                        </div>
                    </div>
                    @if($alat->deskripsi)
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">Deskripsi</div>
                        <div class="col-sm-8">{{ $alat->deskripsi }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- EOS/EOL Information -->
            @if($alat->tanggal_eos || $alat->tanggal_eol)
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title"><i class="bi bi-calendar-event me-2"></i>Informasi Lifecycle</h5>
                </div>
                <div class="ios-card-body">
                    @if($alat->tanggal_eos)
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">End of Service</div>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-check" style="color: var(--ios-orange);"></i>
                                <span>{{ $alat->tanggal_eos->format('d/m/Y') }}</span>
                                <span class="ios-badge ios-badge-{{ $alat->status_eos_badge }}">{{ $alat->status_eos_text }}</span>
                            </div>
                            @if($alat->days_to_eos !== null)
                                <small class="text-muted">
                                    {{ abs($alat->days_to_eos) }} hari {{ $alat->days_to_eos >= 0 ? 'lagi' : 'yang lalu' }}
                                </small>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if($alat->tanggal_eol)
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-medium text-secondary">End of Life</div>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-x" style="color: var(--ios-red);"></i>
                                <span>{{ $alat->tanggal_eol->format('d/m/Y') }}</span>
                                <span class="ios-badge ios-badge-{{ $alat->status_eol_badge }}">{{ $alat->status_eol_text }}</span>
                            </div>
                            @if($alat->days_to_eol !== null)
                                <small class="text-muted">
                                    {{ abs($alat->days_to_eol) }} hari {{ $alat->days_to_eol >= 0 ? 'lagi' : 'yang lalu' }}
                                </small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if(auth()->user()->isSiswa() && $alat->stok > 0)
                <div class="ios-card mb-4">
                    <div class="ios-card-header" style="background: var(--ios-green); color: white;">
                        <h5 class="ios-card-title" style="color: white;"><i class="bi bi-hand-thumbs-up me-2"></i>Pinjam Alat Ini</h5>
                    </div>
                    <div class="ios-card-body">
                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="alat_id" value="{{ $alat->id }}">
                            
                            <div class="mb-3">
                                <label for="qty" class="form-label fw-medium">Jumlah yang dipinjam <span class="text-danger">*</span></label>
                                <input type="number" class="ios-form-control @error('qty') is-invalid @enderror" 
                                       id="qty" name="qty" value="{{ old('qty', 1) }}" 
                                       min="1" max="{{ $alat->stok }}" required>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maksimal {{ $alat->stok }} unit</div>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_pinjam" class="form-label fw-medium">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" class="ios-form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                       id="tanggal_pinjam" name="tanggal_pinjam" 
                                       value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label fw-medium">Keterangan</label>
                                <textarea class="ios-form-control @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" name="keterangan" rows="3" 
                                          placeholder="Tujuan peminjaman atau informasi tambahan">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert" style="background: rgba(255, 149, 0, 0.1); border: 1px solid rgba(255, 149, 0, 0.2); border-radius: 12px; color: var(--ios-orange);">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Peminjaman alat memerlukan verifikasi admin. Pastikan untuk mengembalikan alat tepat waktu.
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="ios-btn ios-btn-success" style="padding: 12px;">
                                    <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="ios-card mb-4">
                <div class="ios-card-header" style="background: {{ $alat->stok > 0 ? 'var(--ios-green)' : 'var(--ios-red)' }}; color: white;">
                    <h5 class="ios-card-title" style="color: white;">
                        <i class="bi bi-{{ $alat->stok > 0 ? 'check-circle-fill' : 'x-circle-fill' }} me-2"></i>
                        Status Alat
                    </h5>
                </div>
                <div class="ios-card-body text-center">
                    <div class="mb-3" style="font-size: 4rem;">
                        <i class="bi bi-{{ $alat->stok > 0 ? 'check-circle-fill' : 'x-circle-fill' }}" style="color: {{ $alat->stok > 0 ? 'var(--ios-green)' : 'var(--ios-red)' }};"></i>
                    </div>
                    <h4 style="color: var(--ios-label);">{{ $alat->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}</h4>
                    <p class="mb-0" style="color: var(--ios-secondary-label);">
                        @if($alat->stok > 0)
                            Tersedia {{ $alat->stok }} unit untuk dipinjam
                        @else
                            Stok habis, silahkan cek kembali nanti
                        @endif
                    </p>
                </div>
                @if(auth()->user()->isSiswa() && $alat->stok > 0)
                    <div class="p-3 border-top" style="border-top: 0.5px solid var(--ios-gray5) !important;">
                        <a href="{{ route('peminjaman.create', ['alat_id' => $alat->id]) }}" class="ios-btn ios-btn-success w-100">
                            <i class="bi bi-hand-thumbs-up me-2"></i>Pinjam Sekarang
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- EOS/EOL Warning Cards -->
            @if($alat->is_eos_warning || $alat->is_eol_warning)
                @if($alat->is_eos_warning)
                    <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-orange);">
                        <div class="ios-card-header" style="background: rgba(255, 149, 0, 0.1);">
                            <h6 style="color: var(--ios-orange); margin: 0; font-weight: 600;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan EOS</h6>
                        </div>
                        <div class="ios-card-body">
                            <div class="text-center">
                                <i class="bi bi-calendar-event" style="font-size: 2rem; color: var(--ios-orange); margin-bottom: 12px;"></i>
                                <h6 style="color: var(--ios-label);">End of Service</h6>
                                <p class="mb-0" style="color: var(--ios-label); font-weight: 600;">{{ $alat->tanggal_eos->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $alat->status_eos_text }}</small>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($alat->is_eol_warning)
                    <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-red);">
                        <div class="ios-card-header" style="background: rgba(255, 59, 48, 0.1);">
                            <h6 style="color: var(--ios-red); margin: 0; font-weight: 600;"><i class="bi bi-x-circle-fill me-2"></i>Peringatan EOL</h6>
                        </div>
                        <div class="ios-card-body">
                            <div class="text-center">
                                <i class="bi bi-calendar-x" style="font-size: 2rem; color: var(--ios-red); margin-bottom: 12px;"></i>
                                <h6 style="color: var(--ios-label);">End of Life</h6>
                                <p class="mb-0" style="color: var(--ios-label); font-weight: 600;">{{ $alat->tanggal_eol->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $alat->status_eol_text }}</small>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Admin Actions -->
            @if(auth()->user()->isAdmin())
                <div class="ios-card mb-4">
                    <div class="ios-card-header">
                        <h5 class="ios-card-title"><i class="bi bi-gear me-2"></i>Admin Actions</h5>
                    </div>
                    <div class="ios-card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('alat.edit', $alat) }}" class="ios-btn" style="background: var(--ios-orange); color: white;">
                                <i class="bi bi-pencil me-2"></i>Edit Alat
                            </a>
                            <button type="button" class="ios-btn" style="background: var(--ios-teal); color: white;" data-bs-toggle="modal" data-bs-target="#updateStokModal">
                                <i class="bi bi-boxes me-2"></i>Update Stok
                            </button>
                            <form action="{{ route('alat.destroy', $alat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ios-btn ios-btn-danger w-100">
                                    <i class="bi bi-trash me-2"></i>Hapus Alat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Recent Borrowings -->
                <div class="ios-card">
                    <div class="ios-card-header">
                        <h5 class="ios-card-title"><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</h5>
                    </div>
                    <div class="ios-card-body">
                        @if($recentBorrowings->count() > 0)
                            <div class="list-group list-group-flush" style="border: none;">
                                @foreach($recentBorrowings as $peminjaman)
                                    <div class="list-group-item" style="border: none; border-bottom: 0.5px solid var(--ios-gray5); padding: 12px 0;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong style="color: var(--ios-label);">{{ $peminjaman->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</small>
                                            </div>
                                            <span class="ios-badge ios-badge-{{ $peminjaman->status == 'menunggu_verifikasi' ? 'warning' : ($peminjaman->status == 'dipinjam' ? 'warning' : 'success') }}">
                                                {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">Belum ada peminjaman untuk alat ini.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.ios-form-control {
    padding: 12px 16px;
    border: 1px solid var(--ios-gray4);
    border-radius: 12px;
    font-size: 16px;
    font-family: inherit;
    background: var(--ios-secondary-background);
    color: var(--ios-label);
    transition: all 0.2s;
}

.ios-form-control:focus {
    outline: none;
    border-color: var(--ios-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}
</style>
@endpush

<!-- Update Stok Modal -->
@if(auth()->user()->isAdmin())
<div class="modal fade" id="updateStokModal" tabindex="-1" aria-labelledby="updateStokModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('alat.update', $alat) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_stok_only" value="1">
                <input type="hidden" name="nama" value="{{ $alat->nama }}">
                <input type="hidden" name="kode" value="{{ $alat->kode }}">
                <input type="hidden" name="kategori" value="{{ $alat->kategori }}">
                <input type="hidden" name="lokasi" value="{{ $alat->lokasi }}">
                <input type="hidden" name="deskripsi" value="{{ $alat->deskripsi }}">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStokModalLabel">Update Stok Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="stok" class="form-label">Jumlah Stok Baru</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="{{ $alat->stok }}" min="0" required>
                        <div class="form-text">Masukkan jumlah stok yang tersedia saat ini</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Foto Modal -->
@if($alat->foto)
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto {{ $alat->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto {{ $alat->nama }}" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>
@endif
@endsection