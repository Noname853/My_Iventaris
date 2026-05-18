@extends('layouts.ios16')

@section('title', 'Edit Alat - Inventory TKJ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit me-2"></i>Edit Alat</h2>
                <div>
                    <a href="{{ route('alat.show', $alat) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>Lihat Detail
                    </a>
                    <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-tools me-2"></i>Form Edit Alat</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('alat.update', $alat) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode Alat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                                           id="kode" name="kode" value="{{ old('kode', $alat->kode) }}" 
                                           placeholder="Contoh: TKJ001" required>
                                    @error('kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kode unik untuk identifikasi alat</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama', $alat->nama) }}" 
                                           placeholder="Contoh: Crimping Tool RJ45" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" 
                                            id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Jaringan" {{ old('kategori', $alat->kategori) == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                        <option value="Hardware" {{ old('kategori', $alat->kategori) == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                        <option value="Software" {{ old('kategori', $alat->kategori) == 'Software' ? 'selected' : '' }}>Software</option>
                                        <option value="Kabel" {{ old('kategori', $alat->kategori) == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                                        <option value="Tools" {{ old('kategori', $alat->kategori) == 'Tools' ? 'selected' : '' }}>Tools</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                           id="stok" name="stok" value="{{ old('stok', $alat->stok) }}" 
                                           min="0" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Jumlah alat yang tersedia</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <select class="form-select @error('lokasi') is-invalid @enderror" 
                                    id="lokasi" name="lokasi" required>
                                <option value="">Pilih Lokasi</option>
                                <option value="Ruang Kaprog" {{ old('lokasi', $alat->lokasi) == 'Ruang Kaprog' ? 'selected' : '' }}>Ruang Kaprog</option>
                            </select>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Deskripsi detail tentang alat (opsional)">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Informasi tambahan tentang alat, spesifikasi, atau catatan khusus</div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Alat</label>
                            @if($alat->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto {{ $alat->nama }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <div class="form-text">Foto saat ini</div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload foto baru (format: JPG, PNG, GIF, maksimal 2MB) - kosongkan jika tidak ingin mengubah</div>
                            <div id="foto-preview" class="mt-2" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <!-- EOS/EOL Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-calendar-times me-2"></i>Informasi End of Service & End of Life</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_eos" class="form-label">Tanggal EOS (End of Service)</label>
                                            <input type="date" class="form-control @error('tanggal_eos') is-invalid @enderror" 
                                                   id="tanggal_eos" name="tanggal_eos" 
                                                   value="{{ old('tanggal_eos', $alat->tanggal_eos ? $alat->tanggal_eos->format('Y-m-d') : '') }}">
                                            @error('tanggal_eos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Tanggal berakhirnya dukungan layanan untuk alat ini</div>
                                            @if($alat->tanggal_eos)
                                                <div class="small text-muted mt-1">
                                                    Status: <span class="badge bg-{{ $alat->status_eos_badge }}">{{ $alat->status_eos_text }}</span>
                                                    @if($alat->days_to_eos !== null)
                                                        ({{ abs($alat->days_to_eos) }} hari {{ $alat->days_to_eos >= 0 ? 'lagi' : 'yang lalu' }})
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="keterangan_eos" class="form-label">Keterangan EOS</label>
                                            <textarea class="form-control @error('keterangan_eos') is-invalid @enderror" 
                                                      id="keterangan_eos" name="keterangan_eos" rows="3" 
                                                      placeholder="Catatan mengenai End of Service (opsional)">{{ old('keterangan_eos', $alat->keterangan_eos) }}</textarea>
                                            @error('keterangan_eos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_eol" class="form-label">Tanggal EOL (End of Life)</label>
                                            <input type="date" class="form-control @error('tanggal_eol') is-invalid @enderror" 
                                                   id="tanggal_eol" name="tanggal_eol" 
                                                   value="{{ old('tanggal_eol', $alat->tanggal_eol ? $alat->tanggal_eol->format('Y-m-d') : '') }}">
                                            @error('tanggal_eol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Tanggal berakhirnya masa hidup alat (tidak lagi diproduksi/didukung)</div>
                                            @if($alat->tanggal_eol)
                                                <div class="small text-muted mt-1">
                                                    Status: <span class="badge bg-{{ $alat->status_eol_badge }}">{{ $alat->status_eol_text }}</span>
                                                    @if($alat->days_to_eol !== null)
                                                        ({{ abs($alat->days_to_eol) }} hari {{ $alat->days_to_eol >= 0 ? 'lagi' : 'yang lalu' }})
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="keterangan_eol" class="form-label">Keterangan EOL</label>
                                            <textarea class="form-control @error('keterangan_eol') is-invalid @enderror" 
                                                      id="keterangan_eol" name="keterangan_eol" rows="3" 
                                                      placeholder="Catatan mengenai End of Life (opsional)">{{ old('keterangan_eol', $alat->keterangan_eol) }}</textarea>
                                            @error('keterangan_eol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Catatan EOS/EOL:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>EOS (End of Service):</strong> Tanggal ketika vendor tidak lagi memberikan dukungan teknis</li>
                                        <li><strong>EOL (End of Life):</strong> Tanggal ketika produk tidak lagi diproduksi atau didukung sama sekali</li>
                                        <li>Informasi ini membantu dalam perencanaan penggantian alat</li>
                                        <li>Sistem akan memberikan notifikasi mendekati tanggal EOS/EOL</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Current Information Display -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Saat Ini:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Kode:</small> <strong>{{ $alat->kode }}</strong><br>
                                            <small class="text-muted">Kategori:</small> <span class="badge bg-secondary">{{ $alat->kategori }}</span><br>
                                            <small class="text-muted">Stok:</small> 
                                            <span class="badge bg-{{ $alat->stok == 0 ? 'danger' : ($alat->stok <= 5 ? 'warning' : 'success') }}">
                                                {{ $alat->stok }} unit
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Lokasi:</small> <strong>{{ $alat->lokasi }}</strong><br>
                                            <small class="text-muted">Dibuat:</small> {{ $alat->created_at->format('d/m/Y H:i') }}<br>
                                            <small class="text-muted">Diupdate:</small> {{ $alat->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning for Active Borrowings -->
                        @if($activeBorrowings > 0)
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Perhatian:</strong> Alat ini sedang dipinjam oleh {{ $activeBorrowings }} orang. 
                                        Pastikan perubahan stok tidak mengganggu peminjaman yang sedang berlangsung.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('alat.show', $alat) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Sidebar -->
    <div class="row mt-4">
        <div class="col-md-8">
            <!-- Empty space -->
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('alat.show', $alat) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#quickStokModal">
                            <i class="fas fa-boxes me-2"></i>Quick Update Stok
                        </button>
                        <a href="{{ route('peminjaman.index') }}?alat={{ $alat->id }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-history me-2"></i>Riwayat Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stok Update Modal -->
<div class="modal fade" id="quickStokModal" tabindex="-1" aria-labelledby="quickStokModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
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
                    <h6 class="modal-title" id="quickStokModalLabel">Quick Update Stok</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quick_stok" class="form-label">Stok Baru</label>
                        <input type="number" class="form-control" id="quick_stok" name="stok" value="{{ $alat->stok }}" min="0" required>
                        <div class="form-text">Stok saat ini: {{ $alat->stok }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('foto-preview');
        const previewImage = document.getElementById('preview-image');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush