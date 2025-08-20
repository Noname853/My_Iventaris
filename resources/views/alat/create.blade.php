@extends('layouts.ios16')

@section('title', 'Tambah Alat - Inventory TKJ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-plus-circle me-2"></i>Tambah Alat Baru</h2>
                <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title"><i class="bi bi-tools me-2"></i>Form Tambah Alat</h5>
                </div>
                <div class="ios-card-body">
                    <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode Alat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                                           id="kode" name="kode" value="{{ old('kode') }}" 
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
                                           id="nama" name="nama" value="{{ old('nama') }}" 
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
                                        <option value="Jaringan" {{ old('kategori') == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                        <option value="Hardware" {{ old('kategori') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                        <option value="Software" {{ old('kategori') == 'Software' ? 'selected' : '' }}>Software</option>
                                        <option value="Kabel" {{ old('kategori') == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                                        <option value="Tools" {{ old('kategori') == 'Tools' ? 'selected' : '' }}>Tools</option>
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
                                           id="stok" name="stok" value="{{ old('stok', 1) }}" 
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
                                <option value="Ruang Kaprog" {{ old('lokasi') == 'Ruang Kaprog' ? 'selected' : '' }}>Ruang Kaprog</option>
                            </select>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" 
                                      placeholder="Deskripsi detail tentang alat (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Informasi tambahan tentang alat, spesifikasi, atau catatan khusus</div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Alat</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload foto alat (format: JPG, PNG, GIF, maksimal 2MB)</div>
                            <div id="foto-preview" class="mt-2" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <!-- EOS/EOL Section -->
                        <div class="ios-card mb-3">
                            <div class="ios-card-header" style="background: linear-gradient(135deg, #5AC8FA, #007AFF); color: white;">
                                <h6 class="mb-0"><i class="bi bi-calendar-x me-2"></i>Informasi End of Service & End of Life</h6>
                            </div>
                            <div class="ios-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_eos" class="form-label">Tanggal EOS (End of Service)</label>
                                            <input type="date" class="form-control @error('tanggal_eos') is-invalid @enderror" 
                                                   id="tanggal_eos" name="tanggal_eos" value="{{ old('tanggal_eos') }}">
                                            @error('tanggal_eos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Tanggal berakhirnya dukungan layanan untuk alat ini</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="keterangan_eos" class="form-label">Keterangan EOS</label>
                                            <textarea class="form-control @error('keterangan_eos') is-invalid @enderror" 
                                                      id="keterangan_eos" name="keterangan_eos" rows="3" 
                                                      placeholder="Catatan mengenai End of Service (opsional)">{{ old('keterangan_eos') }}</textarea>
                                            @error('keterangan_eos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_eol" class="form-label">Tanggal EOL (End of Life)</label>
                                            <input type="date" class="form-control @error('tanggal_eol') is-invalid @enderror" 
                                                   id="tanggal_eol" name="tanggal_eol" value="{{ old('tanggal_eol') }}">
                                            @error('tanggal_eol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Tanggal berakhirnya masa hidup alat (tidak lagi diproduksi/didukung)</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="keterangan_eol" class="form-label">Keterangan EOL</label>
                                            <textarea class="form-control @error('keterangan_eol') is-invalid @enderror" 
                                                      id="keterangan_eol" name="keterangan_eol" rows="3" 
                                                      placeholder="Catatan mengenai End of Life (opsional)">{{ old('keterangan_eol') }}</textarea>
                                            @error('keterangan_eol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="alert" style="background: rgba(255, 149, 0, 0.1); color: #FF9500; border: 1px solid rgba(255, 149, 0, 0.2); border-radius: 12px;">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
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

                        <div class="row">
                            <div class="col-12">
                                <div class="alert" style="background: rgba(0, 122, 255, 0.1); color: #007AFF; border: 1px solid rgba(0, 122, 255, 0.2); border-radius: 12px;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Catatan:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Pastikan kode alat unik dan mudah diingat</li>
                                        <li>Pilih kategori yang sesuai untuk memudahkan pencarian</li>
                                        <li>Stok dapat diubah kemudian sesuai kebutuhan</li>
                                        <li>Lokasi membantu dalam pelacakan fisik alat</li>
                                        <li>Tanggal EOS/EOL opsional, namun sangat membantu untuk perencanaan maintenance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-secondary">
                                <i class="bi bi-x me-2"></i>Batal
                            </a>
                            <button type="submit" class="ios-btn ios-btn-primary">
                                <i class="bi bi-check me-2"></i>Simpan Alat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto generate kode based on kategori
document.getElementById('kategori').addEventListener('change', function() {
    const kategori = this.value;
    const kodeInput = document.getElementById('kode');
    
    if (kategori && !kodeInput.value) {
        let prefix = '';
        switch(kategori) {
            case 'Jaringan': prefix = 'NET'; break;
            case 'Hardware': prefix = 'HW'; break;
            case 'Software': prefix = 'SW'; break;
            case 'Kabel': prefix = 'CBL'; break;
            case 'Tools': prefix = 'TLS'; break;
            default: prefix = 'TKJ';
        }
        
        // Generate random number
        const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        kodeInput.value = prefix + randomNum;
    }
});

// Photo preview
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
@endsection