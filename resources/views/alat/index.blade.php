@extends('layouts.ios16')

@section('title', 'Daftar Alat - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1">Daftar Alat</h1>
            <p class="text-secondary mb-0">Kelola dan lihat semua alat praktikum TKJ</p>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="d-flex gap-2">
                <!-- Import/Export Buttons -->
                <div class="dropdown">
                    <button class="ios-btn ios-btn-secondary dropdown-toggle" type="button" id="importExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-arrow-down-up me-2"></i>Import/Export
                    </button>
                    <ul class="dropdown-menu ios-dropdown-menu" aria-labelledby="importExportDropdown">
                        <li><a class="dropdown-item" href="{{ route('alat.export') }}">
                            <i class="bi bi-download me-2"></i>Export Data Alat
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('alat.template') }}">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Download Template
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-upload me-2"></i>Import Data Alat
                        </a></li>
                    </ul>
                </div>
                
                <a href="{{ route('alat.create') }}" class="ios-btn ios-btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Alat
                </a>
            </div>
        @endif
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filter & Pencarian
                    </h5>
                </div>
                <div class="ios-card-body">
                    <form action="{{ route('alat.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-1">Cari Alat</label>
                            <input type="text" class="ios-form-control" name="search" placeholder="Nama atau kode alat..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1">Kategori</label>
                            <select class="ios-form-control" name="kategori">
                                <option value="">Semua Kategori</option>
                                <option value="Jaringan" {{ request('kategori') == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                <option value="Hardware" {{ request('kategori') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ request('kategori') == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Kabel" {{ request('kategori') == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                                <option value="Tools" {{ request('kategori') == 'Tools' ? 'selected' : '' }}>Tools</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1">Lokasi</label>
                            <select class="ios-form-control" name="lokasi">
                                <option value="">Semua Lokasi</option>
                                <option value="Ruang Kaprog" {{ request('lokasi') == 'Ruang Kaprog' ? 'selected' : '' }}>Ruang Kaprog</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="ios-btn ios-btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
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

    <!-- Alat Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0">Daftar Alat</h5>
                </div>
                <div class="ios-card-body p-0">
                    <div class="ios-table">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Alat</th>
                                    <th>Nama Alat</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alats as $alat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="ios-badge" style="background: rgba(0, 0, 0, 0.1); color: var(--ios-label); font-family: monospace;">{{ $alat->kode }}</span>
                                    </td>
                                    <td>{{ $alat->nama }}</td>
                                    <td>{{ $alat->kategori }}</td>
                                    <td>{{ $alat->stok }}</td>
                                    <td>
                                        @if($alat->stok > 0)
                                            <span class="ios-badge ios-badge-success">Tersedia</span>
                                        @else
                                            <span class="ios-badge ios-badge-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('alat.show', $alat->id) }}" class="ios-btn ios-btn-primary" style="font-size: 14px; padding: 6px 12px;">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $alats->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(auth()->user()->isAdmin() && isset($stats))
    <div class="row g-4 mt-4">
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #5856D6); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <p class="mb-0 opacity-75">Total Alat</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #30D158); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['tersedia'] }}</h3>
                    <p class="mb-0 opacity-75">Alat Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #FF6B35); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['stok_rendah'] }}</h3>
                    <p class="mb-0 opacity-75">Stok Rendah</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-red), #FF3B30); color: white;">
                <div class="ios-card-body text-center">
                    <h3 class="fw-bold mb-1">{{ $stats['habis'] }}</h3>
                    <p class="mb-0 opacity-75">Stok Habis</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Import Modal -->
    @if(auth()->user()->isAdmin())
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ios-modal-content">
                <div class="modal-header ios-modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="bi bi-upload me-2"></i>Import Data Alat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('alat.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Panduan Import:</strong>
                            <ul class="mb-0 mt-2">
                                <li>File harus berformat Excel (.xlsx, .xls) atau CSV</li>
                                <li>Ukuran file maksimal 5MB</li>
                                <li>Download template untuk melihat format yang benar</li>
                                <li>Kolom yang wajib diisi: kode, nama, kategori, stok, lokasi</li>
                                <li>Format tanggal: YYYY-MM-DD (contoh: 2024-12-31)</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <label for="importFile" class="form-label fw-semibold">Pilih File Excel/CSV</label>
                            <input type="file" class="ios-form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">File yang didukung: .xlsx, .xls, .csv (maksimal 5MB)</div>
                        </div>
                        
                        <div id="filePreview" class="d-none">
                            <div class="ios-card">
                                <div class="ios-card-body">
                                    <h6 class="fw-semibold mb-2">
                                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Preview File
                                    </h6>
                                    <div id="fileInfo"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="{{ route('alat.template') }}" class="ios-btn ios-btn-outline-secondary">
                                <i class="bi bi-download me-2"></i>Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer ios-modal-footer">
                        <button type="button" class="ios-btn ios-btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ios-btn ios-btn-primary" id="importBtn">
                            <i class="bi bi-upload me-2"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('importFile');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const importBtn = document.getElementById('importBtn');
    const importForm = document.getElementById('importForm');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const validTypes = ['.xlsx', '.xls', '.csv'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                
                if (!validTypes.includes(fileExtension)) {
                    alert('Format file tidak didukung. Gunakan file .xlsx, .xls, atau .csv');
                    fileInput.value = '';
                    filePreview.classList.add('d-none');
                    return;
                }
                
                // Validate file size (5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    fileInput.value = '';
                    filePreview.classList.add('d-none');
                    return;
                }
                
                // Show file preview
                fileInfo.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-file-earmark-spreadsheet" style="font-size: 2rem; color: var(--ios-green);"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">${file.name}</div>
                            <div class="text-muted small">
                                Ukuran: ${(file.size / 1024).toFixed(2)} KB | 
                                Tipe: ${file.type || fileExtension}
                            </div>
                        </div>
                    </div>
                `;
                filePreview.classList.remove('d-none');
            } else {
                filePreview.classList.add('d-none');
            }
        });
        
        // Handle form submission
        importForm.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (!file) {
                e.preventDefault();
                alert('Silakan pilih file terlebih dahulu.');
                return;
            }
            
            // Show loading state
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Mengimport...';
            
            // Re-enable button after timeout (in case of errors)
            setTimeout(() => {
                importBtn.disabled = false;
                importBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Import Data';
            }, 30000); // 30 seconds
        });
    }
});
</script>
@endpush
