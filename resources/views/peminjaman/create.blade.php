@extends('layouts.ios16')

@section('title', 'Ajukan Peminjaman - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1"><i class="bi bi-journal-plus me-2"></i>Ajukan Peminjaman</h1>
            <p class="text-secondary mb-0">Isi form di bawah untuk mengajukan peminjaman alat</p>
        </div>
        <div>
            <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-red); background: rgba(255,59,48,0.05);">
            <div class="ios-card-body">
                <h6 class="fw-semibold mb-2" style="color: var(--ios-red);"><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan:</h6>
                <ul class="mb-0" style="color: var(--ios-red);">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="ios-card mb-4" style="border-left: 4px solid var(--ios-red); background: rgba(255,59,48,0.05);">
            <div class="ios-card-body" style="color: var(--ios-red);">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    @php
        $alatsEmpty = $alats->isEmpty();
    @endphp

    @if($alatsEmpty)
        <div class="ios-card">
            <div class="ios-card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--ios-gray3);"></i>
                <h5 class="mt-3 mb-2">Tidak Ada Alat Tersedia</h5>
                <p class="text-secondary mb-4">Semua alat sedang tidak tersedia untuk dipinjam saat ini.</p>
                <a href="{{ route('alat.index') }}" class="ios-btn ios-btn-primary">
                    <i class="bi bi-tools me-2"></i>Lihat Daftar Alat
                </a>
            </div>
        </div>
    @else
        <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm">
            @csrf

            <!-- Alat Selection -->
            <div class="ios-card mb-4">
                <div class="ios-card-header d-flex justify-content-between align-items-center">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-tools me-2"></i>Pilih Alat</h5>
                    <button type="button" class="ios-btn ios-btn-primary" id="btnTambahAlat" style="padding: 8px 16px; font-size: 14px;">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Alat
                    </button>
                </div>
                <div class="ios-card-body">
                    <div id="items-container">
                        <!-- Items will be added dynamically -->
                    </div>
                    <div id="no-items-msg" class="text-center py-3 text-secondary" style="display: none;">
                        <i class="bi bi-info-circle me-1"></i>Klik "Tambah Alat" untuk memilih alat yang ingin dipinjam.
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-calendar3 me-2"></i>Waktu Peminjaman</h5>
                </div>
                <div class="ios-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium mb-2">Waktu Mulai <span style="color: var(--ios-red);">*</span></label>
                            <input type="datetime-local"
                                   class="ios-form-control @error('tanggal_pinjam') is-invalid @enderror"
                                   name="tanggal_pinjam"
                                   id="tanggal_pinjam"
                                   value="{{ old('tanggal_pinjam', date('Y-m-d\TH:i')) }}"
                                   min="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 13px;">Tanggal dan waktu mulai meminjam</div>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium mb-2">Waktu Selesai <span style="color: var(--ios-red);">*</span></label>
                            <input type="datetime-local"
                                   class="ios-form-control @error('tanggal_selesai') is-invalid @enderror"
                                   name="tanggal_selesai"
                                   id="tanggal_selesai"
                                   value="{{ old('tanggal_selesai') }}"
                                   min="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 13px;">Batas waktu pengembalian alat</div>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keperluan & Catatan -->
            <div class="ios-card mb-4">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-chat-text me-2"></i>Keterangan</h5>
                </div>
                <div class="ios-card-body">
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">Keperluan <span style="color: var(--ios-red);">*</span></label>
                        <textarea class="ios-form-control @error('keperluan') is-invalid @enderror"
                                  name="keperluan"
                                  rows="3"
                                  placeholder="Jelaskan tujuan peminjaman alat ini..."
                                  required>{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label fw-medium mb-2">Catatan Tambahan <span class="text-secondary fw-normal">(opsional)</span></label>
                        <textarea class="ios-form-control @error('catatan') is-invalid @enderror"
                                  name="catatan"
                                  rows="2"
                                  placeholder="Catatan tambahan untuk admin...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="ios-card mb-4" style="background: rgba(0, 122, 255, 0.05); border: 1px solid rgba(0, 122, 255, 0.15);">
                <div class="ios-card-body">
                    <h6 class="fw-semibold mb-2" style="color: var(--ios-blue);"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                    <ul class="mb-0" style="color: var(--ios-secondary-label); font-size: 14px;">
                        <li>Peminjaman memerlukan verifikasi dari admin sebelum dapat diproses</li>
                        <li>Pastikan mengembalikan alat tepat waktu sesuai jadwal</li>
                        <li>Alat harus dikembalikan dalam kondisi baik</li>
                        <li>Anda bertanggung jawab atas kerusakan alat selama peminjaman</li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('peminjaman.index') }}" class="ios-btn ios-btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Batal
                </a>
                <button type="submit" class="ios-btn ios-btn-success" id="btnSubmit">
                    <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                </button>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<script>
(function() {
    // Data alat dari server
    const alats = @json($alats->values());

    // Group by kategori
    const groupedAlat = {};
    alats.forEach(function(alat) {
        if (!groupedAlat[alat.kategori]) {
            groupedAlat[alat.kategori] = [];
        }
        groupedAlat[alat.kategori].push(alat);
    });

    let itemCount = 0;
    const container = document.getElementById('items-container');
    const noItemsMsg = document.getElementById('no-items-msg');
    const btnTambah = document.getElementById('btnTambahAlat');

    function buildOptions() {
        let html = '<option value="">-- Pilih Alat --</option>';
        Object.keys(groupedAlat).sort().forEach(function(kat) {
            html += '<optgroup label="' + escHtml(kat) + '">';
            groupedAlat[kat].forEach(function(alat) {
                html += '<option value="' + alat.id + '" '
                      + 'data-stok="' + alat.stok + '" '
                      + 'data-nama="' + escHtml(alat.nama) + '" '
                      + 'data-kode="' + escHtml(alat.kode) + '" '
                      + 'data-lokasi="' + escHtml(alat.lokasi) + '">'
                      + escHtml(alat.nama) + ' (' + escHtml(alat.kode) + ') — Stok: ' + alat.stok
                      + '</option>';
            });
            html += '</optgroup>';
        });
        return html;
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function updateNoItemsMsg() {
        if (container.children.length === 0) {
            noItemsMsg.style.display = 'block';
        } else {
            noItemsMsg.style.display = 'none';
        }
    }

    function addItem() {
        const idx = itemCount++;
        const div = document.createElement('div');
        div.id = 'item-row-' + idx;
        div.className = 'ios-card mb-3';
        div.style.cssText = 'border: 1px solid var(--ios-gray5); border-radius: 12px;';

        div.innerHTML =
            '<div class="ios-card-body">' +
                '<div class="d-flex justify-content-between align-items-center mb-3">' +
                    '<h6 class="fw-semibold mb-0"><i class="bi bi-tools me-2" style="color: var(--ios-blue);"></i>Alat #' + (container.children.length + 1) + '</h6>' +
                    '<button type="button" class="ios-btn" style="background: rgba(255,59,48,0.1); color: var(--ios-red); padding: 4px 10px; font-size: 13px;" onclick="removeItem(' + idx + ')">' +
                        '<i class="bi bi-trash me-1"></i>Hapus' +
                    '</button>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-7">' +
                        '<label class="form-label fw-medium mb-1">Pilih Alat <span style="color: var(--ios-red);">*</span></label>' +
                        '<select class="ios-form-control" name="items[' + idx + '][alat_id]" onchange="onAlatChange(' + idx + ')" required style="width:100%;">' +
                            buildOptions() +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label fw-medium mb-1">Jumlah <span style="color: var(--ios-red);">*</span></label>' +
                        '<input type="number" class="ios-form-control" name="items[' + idx + '][jumlah]" id="jumlah-' + idx + '" min="1" value="1" required>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label fw-medium mb-1">Stok</label>' +
                        '<div class="ios-form-control" style="background: var(--ios-gray6); text-align: center; font-weight: 600;" id="stok-badge-' + idx + '">—</div>' +
                    '</div>' +
                '</div>' +
                '<div class="mt-3">' +
                    '<label class="form-label fw-medium mb-1">Keterangan <span class="text-secondary fw-normal">(opsional)</span></label>' +
                    '<input type="text" class="ios-form-control" name="items[' + idx + '][keterangan]" placeholder="Keterangan khusus untuk alat ini...">' +
                '</div>' +
                '<div id="alat-info-' + idx + '" class="mt-3" style="display:none;">' +
                    '<div class="p-3 rounded-3" style="background: rgba(0,122,255,0.05); border: 1px solid rgba(0,122,255,0.15); font-size: 14px;">' +
                        '<span id="info-lokasi-' + idx + '"></span>' +
                    '</div>' +
                '</div>' +
            '</div>';

        container.appendChild(div);
        updateNoItemsMsg();
    }

    window.removeItem = function(idx) {
        const el = document.getElementById('item-row-' + idx);
        if (el) {
            el.remove();
            updateNoItemsMsg();
        }
    };

    window.onAlatChange = function(idx) {
        const sel = document.querySelector('[name="items[' + idx + '][alat_id]"]');
        const opt = sel ? sel.options[sel.selectedIndex] : null;
        const stokBadge = document.getElementById('stok-badge-' + idx);
        const jumlahInput = document.getElementById('jumlah-' + idx);
        const infoDiv = document.getElementById('alat-info-' + idx);
        const infoLokasi = document.getElementById('info-lokasi-' + idx);

        if (opt && opt.value) {
            const stok = parseInt(opt.dataset.stok, 10);
            stokBadge.textContent = stok;
            stokBadge.style.color = stok > 5 ? 'var(--ios-green)' : stok > 0 ? 'var(--ios-orange)' : 'var(--ios-red)';
            jumlahInput.max = stok;
            if (parseInt(jumlahInput.value, 10) > stok) {
                jumlahInput.value = stok;
            }
            infoLokasi.innerHTML = '<i class="bi bi-geo-alt-fill me-1" style="color:var(--ios-red);"></i><strong>Lokasi:</strong> ' + escHtml(opt.dataset.lokasi || '—');
            infoDiv.style.display = 'block';
        } else {
            stokBadge.textContent = '—';
            stokBadge.style.color = '';
            jumlahInput.max = '';
            infoDiv.style.display = 'none';
        }
    };

    // Tambah alat button
    if (btnTambah) {
        btnTambah.addEventListener('click', addItem);
    }

    // Auto-add first item on load
    addItem();
    updateNoItemsMsg();

    // Form submit validation
    const form = document.getElementById('peminjamanForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (container.children.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal satu alat yang ingin dipinjam.');
                return;
            }
            const btnSubmit = document.getElementById('btnSubmit');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mengajukan...';
            }
        });
    }

    // Set min for tanggal_selesai based on tanggal_pinjam
    const tPinjam = document.getElementById('tanggal_pinjam');
    const tSelesai = document.getElementById('tanggal_selesai');
    if (tPinjam && tSelesai) {
        tPinjam.addEventListener('change', function() {
            tSelesai.min = this.value;
            if (tSelesai.value && tSelesai.value <= this.value) {
                tSelesai.value = '';
            }
        });
    }
})();
</script>
@endpush
@endsection
