<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $alat->nama }} - Inventory TKJ</title>
    <link rel="stylesheet" href="{{ asset('app-ios16-styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#007AFF">
    <style>
        /* Additional styles for detail page */
        .detail-hero {
            position: relative;
            margin-bottom: 24px;
        }

        .detail-image {
            width: 100%;
            height: 280px;
            border-radius: var(--radius-xl);
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
            position: relative;
        }

        .detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--radius-xl);
        }

        .detail-image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            color: var(--ios-text-secondary);
        }

        .detail-image-placeholder i {
            font-size: 64px;
        }

        .detail-status-overlay {
            position: absolute;
            top: 16px;
            right: 16px;
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 8px 16px;
            box-shadow: var(--shadow-medium);
        }

        .detail-info-section {
            margin-bottom: 24px;
        }

        .info-card {
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-large);
            padding: 24px;
            box-shadow: var(--shadow-medium);
            margin-bottom: 16px;
        }

        .info-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--ios-text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-card h3 i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ios-blue);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--ios-separator);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 16px;
            font-weight: 500;
            color: var(--ios-text-secondary);
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--ios-text-primary);
            text-align: right;
            flex: 1;
            margin-left: 16px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-badge.available {
            background: rgba(52, 199, 89, 0.1);
            color: var(--ios-green);
        }

        .status-badge.low-stock {
            background: rgba(255, 149, 0, 0.1);
            color: var(--ios-orange);
        }

        .status-badge.out-of-stock {
            background: rgba(255, 59, 48, 0.1);
            color: var(--ios-accent);
        }

        .status-badge.eos-warning {
            background: rgba(255, 149, 0, 0.1);
            color: var(--ios-orange);
        }

        .status-badge.eol-warning {
            background: rgba(255, 59, 48, 0.1);
            color: var(--ios-accent);
        }

        .action-buttons {
            position: fixed;
            bottom: 100px;
            left: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 998;
        }

        .action-btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: var(--radius-medium);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: var(--shadow-medium);
        }

        .action-btn.primary {
            background: linear-gradient(135deg, var(--ios-blue), #5AC8FA);
            color: white;
        }

        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavy);
        }

        .action-btn.secondary {
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: var(--ios-text-primary);
        }

        .action-btn.secondary:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .action-btn.danger {
            background: linear-gradient(135deg, var(--ios-accent), #FF6B6B);
            color: white;
        }

        .action-btn:active {
            transform: scale(0.98);
        }

        .eos-eol-section {
            margin-bottom: 24px;
        }

        .warning-card {
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-large);
            padding: 20px;
            box-shadow: var(--shadow-medium);
            border-left: 4px solid var(--ios-orange);
            margin-bottom: 12px;
        }

        .warning-card.critical {
            border-left-color: var(--ios-accent);
        }

        .warning-content {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .warning-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .warning-icon.eos {
            background: var(--ios-orange);
        }

        .warning-icon.eol {
            background: var(--ios-accent);
        }

        .warning-text h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--ios-text-primary);
            margin-bottom: 4px;
        }

        .warning-text p {
            font-size: 14px;
            color: var(--ios-text-secondary);
            line-height: 1.4;
        }

        .activity-section {
            margin-bottom: 120px;
        }

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: var(--ios-card-background);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius-large);
            margin: 20px;
            max-width: 400px;
            width: 100%;
            animation: slideUp 0.3s ease;
            box-shadow: var(--shadow-intense);
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--ios-separator);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--ios-text-secondary);
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .modal-close:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .modal-body {
            padding: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .detail-image {
                height: 240px;
            }
        }
    </style>
</head>
<body>
    <!-- Status Bar -->
    <div class="status-bar">
        <div class="status-left">
            <span class="time" id="current-time">09:41</span>
        </div>
        <div class="status-center">
            <div class="notch"></div>
        </div>
        <div class="status-right">
            <i class="fas fa-signal"></i>
            <i class="fas fa-wifi"></i>
            <div class="battery">
                <div class="battery-level"></div>
            </div>
        </div>
    </div>

    <!-- Navigation Header -->
    <nav class="nav-header">
        <div class="nav-content">
            <button class="nav-btn back-btn" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h1 class="page-title">Detail Alat</h1>
            @if(auth()->user()->isAdmin())
            <button class="nav-btn menu-btn" id="menuBtn">
                <i class="fas fa-ellipsis-h"></i>
            </button>
            @else
            <div></div>
            @endif
        </div>
    </nav>

    <!-- Main App Container -->
    <main class="app-container">
        <div class="page" id="detail-page" data-page="detail">
            
            <!-- Hero Image Section -->
            <section class="detail-hero">
                <div class="detail-image" onclick="openImageModal()">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto {{ $alat->nama }}" id="detail-image">
                    @else
                        <div class="detail-image-placeholder">
                            <i class="fas fa-camera"></i>
                            <span>Tidak ada foto</span>
                        </div>
                    @endif
                    
                    <div class="detail-status-overlay">
                        @if($alat->stok > 0)
                            @if($alat->stok <= 5)
                                <span class="status-badge low-stock">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Stok Rendah
                                </span>
                            @else
                                <span class="status-badge available">
                                    <i class="fas fa-check-circle"></i>
                                    Tersedia
                                </span>
                            @endif
                        @else
                            <span class="status-badge out-of-stock">
                                <i class="fas fa-times-circle"></i>
                                Habis
                            </span>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Basic Info Section -->
            <section class="detail-info-section">
                <div class="info-card">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Informasi Dasar
                    </h3>
                    
                    <div class="info-row">
                        <span class="info-label">Nama Alat</span>
                        <span class="info-value">{{ $alat->nama }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Kode Alat</span>
                        <span class="info-value">
                            <span class="status-badge" style="background: rgba(0, 0, 0, 0.1); color: var(--ios-text-primary);">
                                {{ $alat->kode }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Kategori</span>
                        <span class="info-value">
                            <span class="status-badge" style="background: var(--ios-tint-blue); color: var(--ios-blue);">
                                {{ $alat->kategori }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Stok</span>
                        <span class="info-value">
                            <span class="status-badge {{ $alat->stok == 0 ? 'out-of-stock' : ($alat->stok <= 5 ? 'low-stock' : 'available') }}">
                                {{ $alat->stok }} unit
                            </span>
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Lokasi</span>
                        <span class="info-value">
                            <i class="fas fa-map-marker-alt" style="color: var(--ios-accent); margin-right: 6px;"></i>
                            {{ $alat->lokasi }}
                        </span>
                    </div>
                    
                    @if($alat->deskripsi)
                    <div class="info-row">
                        <span class="info-label">Deskripsi</span>
                        <span class="info-value" style="text-align: left; margin-left: 0;">{{ $alat->deskripsi }}</span>
                    </div>
                    @endif
                </div>
            </section>

            <!-- EOS/EOL Warning Section -->
            @if($alat->is_eos_warning || $alat->is_eol_warning)
            <section class="eos-eol-section">
                @if($alat->is_eos_warning)
                <div class="warning-card {{ $alat->is_eos_expired ? 'critical' : '' }}">
                    <div class="warning-content">
                        <div class="warning-icon eos">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="warning-text">
                            <h4>{{ $alat->status_eos_text }}</h4>
                            <p>
                                @if($alat->tanggal_eos)
                                    End of Service: {{ $alat->tanggal_eos->format('d/m/Y') }}
                                    @if($alat->days_to_eos !== null)
                                        <br>{{ abs($alat->days_to_eos) }} hari {{ $alat->days_to_eos >= 0 ? 'lagi' : 'yang lalu' }}
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @if($alat->is_eol_warning)
                <div class="warning-card critical">
                    <div class="warning-content">
                        <div class="warning-icon eol">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="warning-text">
                            <h4>{{ $alat->status_eol_text }}</h4>
                            <p>
                                @if($alat->tanggal_eol)
                                    End of Life: {{ $alat->tanggal_eol->format('d/m/Y') }}
                                    @if($alat->days_to_eol !== null)
                                        <br>{{ abs($alat->days_to_eol) }} hari {{ $alat->days_to_eol >= 0 ? 'lagi' : 'yang lalu' }}
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </section>
            @endif

            <!-- EOS/EOL Details -->
            @if($alat->tanggal_eos || $alat->tanggal_eol)
            <section class="detail-info-section">
                <div class="info-card">
                    <h3>
                        <i class="fas fa-calendar-alt"></i>
                        Lifecycle Information
                    </h3>
                    
                    @if($alat->tanggal_eos)
                    <div class="info-row">
                        <span class="info-label">End of Service</span>
                        <div class="info-value" style="text-align: right;">
                            <div>{{ $alat->tanggal_eos->format('d/m/Y') }}</div>
                            <small class="status-badge {{ $alat->status_eos_badge }}" style="font-size: 12px;">
                                {{ $alat->status_eos_text }}
                            </small>
                        </div>
                    </div>
                    @endif
                    
                    @if($alat->tanggal_eol)
                    <div class="info-row">
                        <span class="info-label">End of Life</span>
                        <div class="info-value" style="text-align: right;">
                            <div>{{ $alat->tanggal_eol->format('d/m/Y') }}</div>
                            <small class="status-badge {{ $alat->status_eol_badge }}" style="font-size: 12px;">
                                {{ $alat->status_eol_text }}
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            <!-- Recent Activity -->
            @if(auth()->user()->isAdmin() && $recentBorrowings->count() > 0)
            <section class="activity-section">
                <div class="info-card">
                    <h3>
                        <i class="fas fa-history"></i>
                        Aktivitas Terbaru
                    </h3>
                    
                    @foreach($recentBorrowings->take(3) as $peminjaman)
                    <div class="info-row">
                        <div>
                            <div style="font-size: 16px; font-weight: 600; color: var(--ios-text-primary);">
                                {{ $peminjaman->user->name }}
                            </div>
                            <small style="color: var(--ios-text-secondary);">
                                {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
                            </small>
                        </div>
                        <span class="status-badge {{ $peminjaman->status == 'menunggu_verifikasi' ? 'low-stock' : ($peminjaman->status == 'dipinjam' ? '' : 'available') }}" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);">
                            {{ $peminjaman->status_text }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </main>

    <!-- Action Buttons -->
    <div class="action-buttons">
        @if(auth()->user()->isSiswa() && $alat->stok > 0 && !$hasPendingBorrowing)
            <a href="{{ route('peminjaman.create', ['alat_id' => $alat->id]) }}" class="action-btn primary">
                <i class="fas fa-hand-holding"></i>
                Pinjam Alat
            </a>
            <button class="action-btn secondary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        @elseif(auth()->user()->isAdmin())
            <a href="{{ route('alat.edit', $alat) }}" class="action-btn primary">
                <i class="fas fa-edit"></i>
                Edit Alat
            </a>
            <button class="action-btn secondary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        @else
            <button class="action-btn primary" onclick="history.back()" style="flex: 1;">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        @endif
    </div>

    <!-- Image Modal -->
    @if($alat->foto)
    <div class="modal-overlay" id="imageModal">
        <div class="modal-content" style="max-width: 90vw; max-height: 80vh;">
            <div class="modal-header">
                <h3>{{ $alat->nama }}</h3>
                <button class="modal-close" onclick="closeImageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <img src="{{ asset('storage/' . $alat->foto) }}" alt="Foto {{ $alat->nama }}" style="width: 100%; height: auto; max-height: 60vh; object-fit: contain;">
            </div>
        </div>
    </div>
    @endif

    <!-- Scripts -->
    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            });
            document.getElementById('current-time').textContent = timeStr;
        }
        
        setInterval(updateTime, 1000);
        updateTime();

        // Image modal functions
        function openImageModal() {
            @if($alat->foto)
            document.getElementById('imageModal').classList.add('show');
            @endif
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('show');
        }

        // Close modal on backdrop click
        document.getElementById('imageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Haptic feedback simulation for iOS-like experience
        function vibrate() {
            if ('vibrate' in navigator) {
                navigator.vibrate(10);
            }
        }

        // Add haptic feedback to buttons
        document.querySelectorAll('.action-btn, .nav-btn').forEach(btn => {
            btn.addEventListener('click', vibrate);
        });

        // iOS-style bounce effect on touch
        document.querySelectorAll('.info-card, .warning-card').forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
