@extends('layouts.app')

@section('title', 'Ajukan Peminjaman - Inventory TKJ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-plus-circle me-2"></i>Ajukan Peminjaman Alat</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        
                        <!-- Items Selection -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Alat yang Dipinjam <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-success" onclick="addItem()">
                                    <i class="fas fa-plus me-1"></i>Tambah Alat
                                </button>
                            </div>
                            
                            <div id="items-container">
                                <!-- Items will be added here dynamically -->
                            </div>
                            
                            @error('items')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @error('items.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Waktu Pinjam & Waktu Selesai -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_pinjam" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" 
                                       id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d\TH:i')) }}" 
                                       min="{{ date('Y-m-d\TH:i') }}" required>
                                <div class="form-text">Waktu mulai peminjaman</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" 
                                       id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d\TH:i') }}" required>
                                <div class="form-text">Waktu alat harus dikembalikan</div>
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="mb-3">
                            <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keperluan') is-invalid @enderror" 
                                      id="keperluan" name="keperluan" rows="3" required 
                                      placeholder="Jelaskan untuk keperluan apa alat ini dipinjam...">{{ old('keperluan') }}</textarea>
                            <div class="form-text">Jelaskan tujuan peminjaman alat</div>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="2" 
                                      placeholder="Catatan tambahan (opsional)...">{{ old('catatan') }}</textarea>
                            <div class="form-text">Catatan tambahan untuk admin (opsional)</div>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Informasi Penting:</h6>
                            <ul class="mb-0">
                                <li>Pengajuan peminjaman akan diverifikasi oleh admin</li>
                                <li>Pastikan data yang diisi sudah benar</li>
                                <li>Anda akan mendapat notifikasi status peminjaman</li>
                                <li>Alat harus dikembalikan sesuai kondisi semula</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let itemIndex = 0;
const alats = @json($alats);
// Group alat by kategori
const groupedAlat = {};
alats.forEach(alat => {
    if (!groupedAlat[alat.kategori]) groupedAlat[alat.kategori] = [];
    groupedAlat[alat.kategori].push(alat);
});

function addItem() {
    const container = document.getElementById('items-container');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'card mb-3';
    itemDiv.id = `item-${itemIndex}`;
    
    itemDiv.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Alat #${itemIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${itemIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[${itemIndex}][alat_id]" onchange="updateItemInfo(${itemIndex})" required>
                        <option value="">-- Pilih Alat --</option>
                        ${Object.keys(groupedAlat).map(kategori => `
                            <optgroup label="${kategori}">
                                ${groupedAlat[kategori].map(alat => `
                                    <option value="${alat.id}"
                                            data-stok="${alat.stok}"
                                            data-nama="${alat.nama}"
                                            data-kode="${alat.kode}"
                                            data-kategori="${alat.kategori}"
                                            data-lokasi="${alat.lokasi}">
                                        ${alat.nama} (${alat.kode}) - Stok: ${alat.stok}
                                    </option>
                                `).join('')}
                            </optgroup>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="items[${itemIndex}][jumlah]" 
                           min="1" max="" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stok Tersedia</label>
                    <div class="form-control-plaintext">
                        <span class="badge bg-info" id="stok-${itemIndex}">-</span>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="form-label">Keterangan (Opsional)</label>
                    <textarea class="form-control" name="items[${itemIndex}][keterangan]" rows="2" 
                              placeholder="Keterangan khusus untuk alat ini..."></textarea>
                </div>
            </div>
            
            <div id="item-info-${itemIndex}" class="mt-3" style="display: none;">
                <div class="alert alert-info mb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <small><strong>Kategori:</strong> <span id="info-kategori-${itemIndex}"></span></small><br>
                            <small><strong>Lokasi:</strong> <span id="info-lokasi-${itemIndex}"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(itemDiv);
    itemIndex++;
}

function removeItem(index) {
    const itemDiv = document.getElementById(`item-${index}`);
    if (itemDiv) {
        itemDiv.remove();
    }
}

function updateItemInfo(index) {
    const select = document.querySelector(`select[name="items[${index}][alat_id]"]`);
    const selectedOption = select.options[select.selectedIndex];
    const qtyInput = document.querySelector(`input[name="items[${index}][jumlah]"]`);
    const stokBadge = document.getElementById(`stok-${index}`);
    const infoDiv = document.getElementById(`item-info-${index}`);
    
    if (selectedOption.value) {
        // Update stock info
        stokBadge.textContent = selectedOption.dataset.stok;
        
        // Update quantity max
        qtyInput.max = selectedOption.dataset.stok;
        
        // Reset quantity if exceeds max
        if (parseInt(qtyInput.value) > parseInt(selectedOption.dataset.stok)) {
            qtyInput.value = selectedOption.dataset.stok;
        }
        
        // Show item info
        document.getElementById(`info-kategori-${index}`).textContent = selectedOption.dataset.kategori;
        document.getElementById(`info-lokasi-${index}`).textContent = selectedOption.dataset.lokasi;
        infoDiv.style.display = 'block';
    } else {
        stokBadge.textContent = '-';
        qtyInput.max = '';
        infoDiv.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (alats.filter(alat => alat.stok > 0).length === 0) {
        const container = document.getElementById('items-container');
        container.innerHTML = '<div class="alert alert-warning">Semua alat sedang tidak tersedia untuk dipinjam.</div>';
    } else {
        addItem();
    }
});
</script>
@endsection