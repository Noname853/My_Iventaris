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
            <div class="d-flex gap-2 flex-wrap">
                <div class="dropdown">
                    <button class="ios-btn ios-btn-secondary dropdown-toggle" type="button" id="importExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-arrow-down-up me-1 me-md-2"></i><span class="d-none d-md-inline">Import/Export</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="importExportDropdown">
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
                    <i class="bi bi-plus-lg me-1 me-md-2"></i><span class="d-none d-md-inline">Tambah Alat</span><span class="d-md-none">Tambah</span>
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
                    <form action="{{ route('alat.index') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-1">Cari Alat</label>
                                <input type="text" class="ios-form-control w-100" name="search" placeholder="Nama atau kode alat..." value="{{ request('search') }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold mb-1">Kategori</label>
                                <select class="ios-form-control w-100" name="kategori">
                                    <option value="">Semua Kategori</option>
                                    <option value="Jaringan" {{ request('kategori') == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                                    <option value="Hardware" {{ request('kategori') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                    <option value="Software" {{ request('kategori') == 'Software' ? 'selected' : '' }}>Software</option>
                                    <option value="Kabel" {{ request('kategori') == 'Kabel' ? 'selected' : '' }}>Kabel</option>
                                    <option value="Tools" {{ request('kategori') == 'Tools' ? 'selected' : '' }}>Tools</option>
                                    <option value="Networking" {{ request('kategori') == 'Networking' ? 'selected' : '' }}>Networking</option>
                                    <option value="Laptop" {{ request('kategori') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold mb-1">Lokasi</label>
                                <select class="ios-form-control w-100" name="lokasi">
                                    <option value="">Semua Lokasi</option>
                                    <option value="Ruang Kaprog" {{ request('lokasi') == 'Ruang Kaprog' ? 'selected' : '' }}>Ruang Kaprog</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="ios-btn ios-btn-primary flex-grow-1">
                                        <i class="bi bi-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-secondary">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
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

    <!-- ===================== MOBILE: Card View ===================== -->
    <div class="d-block d-md-none mb-4">
        <div class="ios-card">
            <div class="ios-card-header">
                <h5 class="ios-card-title mb-0">Daftar Alat
                    <span class="ios-badge ms-2" style="background: rgba(0,122,255,0.1); color: var(--ios-blue);">
                        {{ $alats->total() }} item
                    </span>
                </h5>
            </div>
            <div class="ios-card-body p-0">
                @forelse($alats as $alat)
                <a href="{{ route('alat.show', $alat->id) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-3 px-4 py-3"
                         style="border-bottom: 0.5px solid var(--ios-gray5); transition: background 0.15s;"
                         onmousedown="this.style.background='var(--ios-gray6)'"
                         onmouseup="this.style.background=''"
                         ontouchstart="this.style.background='var(--ios-gray6)'"
                         ontouchend="this.style.background=''">

                        <!-- Icon / Stok indicator -->
                        <div style="width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
                             background: {{ $alat->stok == 0 ? 'rgba(255,59,48,0.1)' : ($alat->stok <= 5 ? 'rgba(255,149,0,0.1)' : 'rgba(52,199,89,0.1)') }};
                             display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-tools" style="font-size: 20px;
                               color: {{ $alat->stok == 0 ? 'var(--ios-red)' : ($alat->stok <= 5 ? 'var(--ios-orange)' : 'var(--ios-green)') }};"></i>
                        </div>

                        <!-- Info -->
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold" style="color: var(--ios-label); font-size: 15px;
                                      white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;">
                                    {{ $alat->nama }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="ios-badge" style="background: var(--ios-gray6); color: var(--ios-gray); font-size: 11px; font-family: monospace;">
                                    {{ $alat->kode }}
                                </span>
                                <span style="color: var(--ios-secondary-label); font-size: 12px;">
                                    {{ $alat->kategori }}
                                </span>
                            </div>
                        </div>

                        <!-- Stok + chevron -->
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <div class="text-end">
                                <span class="ios-badge ios-badge-{{ $alat->stok == 0 ? 'danger' : ($alat->stok <= 5 ? 'warning' : 'success') }}"
                                      style="font-size: 12px;">
                                    {{ $alat->stok }} unit
                                </span>
                            </div>
                            <i class="bi bi-chevron-right" style="color: var(--ios-gray3); font-size: 13px;"></i>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: var(--ios-gray3);"></i>
                    <p class="text-secondary mt-2 mb-0">Tidak ada alat ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===================== DESKTOP: Table View ===================== -->
    <div class="d-none d-md-block mb-4">
        <div class="ios-card">
            <div class="ios-card-header">
                <h5 class="ios-card-title mb-0">Daftar Alat</h5>
            </div>
            <div class="ios-card-body p-0">
                <div class="ios-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 48px;">No</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alats as $alat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="ios-badge" style="background: rgba(0,0,0,0.06); color: var(--ios-label); font-family: monospace;">
                                        {{ $alat->kode }}
                                    </span>
                                </td>
                                <td class="fw-medium">{{ $alat->nama }}</td>
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
                                    <a href="{{ route('alat.show', $alat->id) }}"
                                       class="ios-btn ios-btn-primary"
                                       style="font-size: 13px; padding: 5px 12px;">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                    Tidak ada alat ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-2 mb-4">
        {{ $alats->appends(request()->query())->links() }}
    </div>

    <!-- Statistics Cards (Admin Only) -->
    @if(auth()->user()->isAdmin() && isset($stats))
    <div class="row g-3 mt-2">
        <div class="col-6 col-lg-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #5856D6);">
                <div class="ios-card-body text-center text-white py-3">
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <p class="mb-0 opacity-75 small">Total Alat</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #30D158);">
                <div class="ios-card-body text-center text-white py-3">
                    <h3 class="fw-bold mb-1">{{ $stats['tersedia'] }}</h3>
                    <p class="mb-0 opacity-75 small">Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #FF6B35);">
                <div class="ios-card-body text-center text-white py-3">
                    <h3 class="fw-bold mb-1">{{ $stats['stok_rendah'] }}</h3>
                    <p class="mb-0 opacity-75 small">Stok Rendah</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-red), #FF3B30);">
                <div class="ios-card-body text-center text-white py-3">
                    <h3 class="fw-bold mb-1">{{ $stats['habis'] }}</h3>
                    <p class="mb-0 opacity-75 small">Stok Habis</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Import Modal -->
    @if(auth()->user()->isAdmin())
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
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
                                <li>Kolom wajib: kode, nama, kategori, stok, lokasi</li>
                                <li>Format tanggal: YYYY-MM-DD</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="importFile" class="form-label fw-semibold">Pilih File Excel/CSV</label>
                            <input type="file" class="ios-form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">File yang didukung: .xlsx, .xls, .csv (maks 5MB)</div>
                        </div>
                        <div id="filePreview" class="d-none">
                            <div class="ios-card">
                                <div class="ios-card-body" id="fileInfo"></div>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('alat.template') }}" class="ios-btn ios-btn-secondary">
                                <i class="bi bi-download me-2"></i>Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
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

@push('styles')
<style>
    /* Ensure form controls are full width on mobile */
    @media (max-width: 767px) {
        .ios-form-control {
            width: 100% !important;
        }
        /* Card list tap effect */
        .alat-card-item:active {
            background: var(--ios-gray6) !important;
        }
    }
</style>
@endpush

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
            if (!file) { filePreview.classList.add('d-none'); return; }

            const validTypes = ['.xlsx', '.xls', '.csv'];
            const ext = '.' + file.name.split('.').pop().toLowerCase();
            if (!validTypes.includes(ext)) {
                alert('Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv');
                fileInput.value = '';
                filePreview.classList.add('d-none');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                fileInput.value = '';
                filePreview.classList.add('d-none');
                return;
            }
            fileInfo.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-file-earmark-spreadsheet" style="font-size: 2rem; color: var(--ios-green);"></i>
                    <div>
                        <div class="fw-semibold">${file.name}</div>
                        <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                    </div>
                </div>`;
            filePreview.classList.remove('d-none');
        });

        importForm && importForm.addEventListener('submit', function() {
            if (importBtn) {
                importBtn.disabled = true;
                importBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengimport...';
                setTimeout(() => {
                    importBtn.disabled = false;
                    importBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Import Data';
                }, 30000);
            }
        });
    }
});
</script>
@endpush
@endsection
