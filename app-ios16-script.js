// iOS 16 Complete App JavaScript
class InventoryApp {
    constructor() {
        this.currentPage = 'dashboard';
        this.inventoryData = this.getInventoryData();
        this.stats = this.calculateStats();
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateTime();
        this.setupTimeInterval();
        this.animateBatteryLevel();
        this.loadInventoryItems();
        this.setupScrollAnimations();
        this.setupHapticFeedback();
        this.showToast('Aplikasi siap digunakan!', 'success');
    }

    // Event Listeners Setup
    setupEventListeners() {
        // Bottom Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const page = item.dataset.page;
                if (page) {
                    this.navigateToPage(page);
                    this.createRipple(item, e);
                }
            });
        });

        // Action Cards
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const action = card.dataset.action;
                this.handleActionClick(action);
                this.createRipple(card, e);
            });
        });

        // Navigation Buttons
        document.getElementById('backBtn')?.addEventListener('click', () => {
            this.goBack();
        });

        document.getElementById('menuBtn')?.addEventListener('click', () => {
            this.showMenu();
        });

        // Search functionality
        document.getElementById('inventory-search')?.addEventListener('input', (e) => {
            this.searchInventory(e.target.value);
        });

        document.getElementById('clear-search')?.addEventListener('click', () => {
            this.clearSearch();
        });

        // Category filters
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.addEventListener('click', (e) => {
                this.filterByCategory(chip.dataset.category);
                this.updateFilterChips(chip);
            });
        });

        // View toggle
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.toggleView(btn.dataset.view);
                this.updateViewToggle(btn);
            });
        });

        // Form handling
        document.getElementById('add-item-form')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleAddItemForm(e.target);
        });

        document.getElementById('cancel-add')?.addEventListener('click', () => {
            this.navigateToPage('dashboard');
        });

        // Image upload
        document.getElementById('image-preview')?.addEventListener('click', () => {
            document.getElementById('item-image').click();
        });

        document.getElementById('item-image')?.addEventListener('change', (e) => {
            this.handleImageUpload(e.target.files[0]);
        });

        // Settings toggles
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                this.handleSettingToggle(e.target.id, e.target.checked);
            });
        });

        // Export buttons
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.exportData(btn.dataset.format);
            });
        });

        // Profile edit
        document.querySelector('.profile-edit')?.addEventListener('click', () => {
            this.editProfile();
        });

        // Activity items
        document.querySelectorAll('.activity-item').forEach(item => {
            item.addEventListener('click', () => {
                this.showActivityDetails(item);
            });
        });

        // Stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', () => {
                this.showStatDetails(card.dataset.stat);
            });
        });
    }

    // Page Navigation
    navigateToPage(pageName) {
        if (this.currentPage === pageName) return;

        const currentPageEl = document.getElementById(`${this.currentPage}-page`);
        const targetPageEl = document.getElementById(`${pageName}-page`);

        if (!targetPageEl) return;

        // Show loading
        this.showLoading();

        // Update navigation
        this.updateNavigation(pageName);
        
        // Update page title
        this.updatePageTitle(pageName);

        // Hide current page
        if (currentPageEl) {
            currentPageEl.style.display = 'none';
        }

        // Show target page with animation
        setTimeout(() => {
            targetPageEl.style.display = 'block';
            targetPageEl.scrollTop = 0;
            
            // Update back button visibility
            const backBtn = document.getElementById('backBtn');
            if (pageName === 'dashboard') {
                backBtn.style.display = 'none';
            } else {
                backBtn.style.display = 'flex';
            }

            this.currentPage = pageName;
            this.hideLoading();

            // Page-specific initialization
            this.initializePage(pageName);

        }, 300);
    }

    updateNavigation(pageName) {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.page === pageName) {
                item.classList.add('active');
            }
        });
    }

    updatePageTitle(pageName) {
        const titles = {
            dashboard: 'Inventory TKJ',
            inventory: 'Daftar Inventaris',
            'add-item': 'Tambah Barang',
            reports: 'Laporan & Analisis',
            settings: 'Pengaturan'
        };
        
        document.querySelector('.page-title').textContent = titles[pageName] || 'Inventory TKJ';
    }

    initializePage(pageName) {
        switch(pageName) {
            case 'dashboard':
                this.updateDashboardData();
                break;
            case 'inventory':
                this.loadInventoryItems();
                break;
            case 'reports':
                this.loadReportsData();
                break;
            case 'add-item':
                this.resetAddForm();
                break;
        }
    }

    goBack() {
        if (this.currentPage !== 'dashboard') {
            this.navigateToPage('dashboard');
        }
    }

    // Action Handlers
    handleActionClick(action) {
        switch(action) {
            case 'add-item':
                this.navigateToPage('add-item');
                break;
            case 'scan-qr':
                this.initQRScanner();
                break;
            case 'quick-report':
                this.navigateToPage('reports');
                break;
            case 'export-data':
                this.showExportOptions();
                break;
        }
    }

    // Inventory Management
    getInventoryData() {
        // Sample data - replace with actual API calls
        return [
            {
                id: 1,
                name: 'Laptop ASUS ROG',
                code: 'TKJ-001',
                category: 'komputer',
                quantity: 1,
                condition: 'baik',
                location: 'Lab Komputer 1',
                status: 'tersedia',
                description: 'Laptop gaming untuk praktikum multimedia',
                image: null,
                dateAdded: '2025-08-13',
                lastUpdated: '2025-08-15'
            },
            {
                id: 2,
                name: 'Mouse Logitech MX',
                code: 'TKJ-002',
                category: 'aksesoris',
                quantity: 5,
                condition: 'baik',
                location: 'Gudang TKJ',
                status: 'tersedia',
                description: 'Mouse wireless untuk praktikum',
                image: null,
                dateAdded: '2025-08-12',
                lastUpdated: '2025-08-15'
            },
            {
                id: 3,
                name: 'Keyboard Mechanical',
                code: 'TKJ-003',
                category: 'aksesoris',
                quantity: 3,
                condition: 'rusak-ringan',
                location: 'Lab Komputer 2',
                status: 'maintenance',
                description: 'Keyboard gaming untuk praktikum',
                image: null,
                dateAdded: '2025-08-10',
                lastUpdated: '2025-08-14'
            },
            {
                id: 4,
                name: 'Switch Cisco 24 Port',
                code: 'TKJ-004',
                category: 'jaringan',
                quantity: 2,
                condition: 'baik',
                location: 'Lab Jaringan',
                status: 'digunakan',
                description: 'Switch untuk praktikum jaringan',
                image: null,
                dateAdded: '2025-08-08',
                lastUpdated: '2025-08-15'
            },
            {
                id: 5,
                name: 'Microsoft Office 365',
                code: 'TKJ-005',
                category: 'software',
                quantity: 50,
                condition: 'baik',
                location: 'Server TKJ',
                status: 'tersedia',
                description: 'Lisensi Office untuk pembelajaran',
                image: null,
                dateAdded: '2025-08-01',
                lastUpdated: '2025-08-15'
            }
        ];
    }

    calculateStats() {
        const total = this.inventoryData.length;
        const available = this.inventoryData.filter(item => item.status === 'tersedia').length;
        const inUse = this.inventoryData.filter(item => item.status === 'digunakan').length;
        const maintenance = this.inventoryData.filter(item => item.status === 'maintenance').length;

        return { total, available, inUse, maintenance };
    }

    loadInventoryItems(filter = '') {
        const container = document.getElementById('inventory-list');
        if (!container) return;

        let filteredData = this.inventoryData;

        // Apply search filter
        if (filter) {
            filteredData = filteredData.filter(item => 
                item.name.toLowerCase().includes(filter.toLowerCase()) ||
                item.code.toLowerCase().includes(filter.toLowerCase()) ||
                item.description.toLowerCase().includes(filter.toLowerCase())
            );
        }

        // Apply category filter
        const activeCategory = document.querySelector('.filter-chip.active')?.dataset.category;
        if (activeCategory && activeCategory !== 'all') {
            filteredData = filteredData.filter(item => item.category === activeCategory);
        }

        container.innerHTML = '';

        if (filteredData.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada data yang ditemukan</h3>
                    <p>Coba gunakan kata kunci lain atau ubah filter</p>
                </div>
            `;
            return;
        }

        filteredData.forEach(item => {
            const itemEl = this.createInventoryItemElement(item);
            container.appendChild(itemEl);
        });

        // Apply scroll animations
        this.applyScrollAnimations(container.querySelectorAll('.inventory-item'));
    }

    createInventoryItemElement(item) {
        const div = document.createElement('div');
        div.className = 'inventory-item';
        div.innerHTML = `
            <div class="item-image">
                <i class="fas fa-${this.getItemIcon(item.category)}"></i>
            </div>
            <div class="item-info">
                <h4 class="item-name">${item.name}</h4>
                <p class="item-code">${item.code}</p>
                <p class="item-location">${item.location}</p>
                <span class="item-status ${item.status}">${this.getStatusText(item.status)}</span>
            </div>
            <div class="item-actions">
                <button class="btn-icon" onclick="inventoryApp.editItem(${item.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon" onclick="inventoryApp.viewItem(${item.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-icon danger" onclick="inventoryApp.deleteItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        div.addEventListener('click', (e) => {
            if (!e.target.closest('.item-actions')) {
                this.viewItem(item.id);
            }
        });

        return div;
    }

    getItemIcon(category) {
        const icons = {
            komputer: 'laptop',
            aksesoris: 'mouse',
            jaringan: 'network-wired',
            software: 'code',
            lainnya: 'box'
        };
        return icons[category] || 'box';
    }

    getStatusText(status) {
        const statusTexts = {
            tersedia: 'Tersedia',
            digunakan: 'Sedang Digunakan',
            maintenance: 'Maintenance'
        };
        return statusTexts[status] || status;
    }

    // Search and Filter
    searchInventory(query) {
        const clearBtn = document.getElementById('clear-search');
        if (query.length > 0) {
            clearBtn.style.display = 'block';
        } else {
            clearBtn.style.display = 'none';
        }

        this.loadInventoryItems(query);
    }

    clearSearch() {
        const searchInput = document.getElementById('inventory-search');
        const clearBtn = document.getElementById('clear-search');
        
        searchInput.value = '';
        clearBtn.style.display = 'none';
        this.loadInventoryItems();
    }

    filterByCategory(category) {
        this.loadInventoryItems();
    }

    updateFilterChips(activeChip) {
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
        });
        activeChip.classList.add('active');
    }

    toggleView(view) {
        const container = document.getElementById('inventory-list');
        if (view === 'grid') {
            container.classList.add('grid-view');
        } else {
            container.classList.remove('grid-view');
        }
    }

    updateViewToggle(activeBtn) {
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        activeBtn.classList.add('active');
    }

    // Item Management
    editItem(id) {
        const item = this.inventoryData.find(item => item.id === id);
        if (item) {
            this.showEditModal(item);
        }
    }

    viewItem(id) {
        const item = this.inventoryData.find(item => item.id === id);
        if (item) {
            this.showItemDetails(item);
        }
    }

    deleteItem(id) {
        this.showConfirmDialog(
            'Hapus Barang',
            'Apakah Anda yakin ingin menghapus barang ini?',
            () => {
                this.inventoryData = this.inventoryData.filter(item => item.id !== id);
                this.loadInventoryItems();
                this.updateDashboardData();
                this.showToast('Barang berhasil dihapus', 'success');
            }
        );
    }

    // Form Handling
    handleAddItemForm(form) {
        const formData = new FormData(form);
        const newItem = {
            id: Date.now(), // Simple ID generation
            name: formData.get('name'),
            code: formData.get('code'),
            category: formData.get('category'),
            quantity: parseInt(formData.get('quantity')),
            condition: formData.get('condition'),
            location: formData.get('location') || '',
            status: 'tersedia',
            description: formData.get('description') || '',
            image: null, // Handle image upload separately
            dateAdded: new Date().toISOString().split('T')[0],
            lastUpdated: new Date().toISOString().split('T')[0]
        };

        // Validate required fields
        if (!newItem.name || !newItem.code || !newItem.category) {
            this.showToast('Harap isi semua field yang wajib', 'error');
            return;
        }

        // Check for duplicate code
        if (this.inventoryData.some(item => item.code === newItem.code)) {
            this.showToast('Kode barang sudah digunakan', 'error');
            return;
        }

        this.inventoryData.push(newItem);
        this.updateDashboardData();
        this.showToast('Barang berhasil ditambahkan!', 'success');
        this.navigateToPage('inventory');
    }

    resetAddForm() {
        const form = document.getElementById('add-item-form');
        if (form) {
            form.reset();
            this.resetImagePreview();
        }
    }

    handleImageUpload(file) {
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('image-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">`;
            };
            reader.readAsDataURL(file);
        }
    }

    resetImagePreview() {
        const preview = document.getElementById('image-preview');
        if (preview) {
            preview.innerHTML = `
                <i class="fas fa-camera"></i>
                <span>Tambah foto</span>
            `;
        }
    }

    // Dashboard Updates
    updateDashboardData() {
        this.stats = this.calculateStats();
        
        // Update stat numbers
        document.getElementById('total-items').textContent = this.stats.total.toLocaleString();
        document.getElementById('available-items').textContent = this.stats.available.toLocaleString();
        document.getElementById('in-use-items').textContent = this.stats.inUse.toLocaleString();
        document.getElementById('maintenance-items').textContent = this.stats.maintenance.toLocaleString();

        // Update current date
        document.getElementById('current-date').textContent = this.formatCurrentDate();
    }

    formatCurrentDate() {
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return new Date().toLocaleDateString('id-ID', options);
    }

    // Reports and Analytics
    loadReportsData() {
        // Simulate chart data loading
        this.showLoading();
        
        setTimeout(() => {
            this.hideLoading();
            this.renderChart();
        }, 1000);
    }

    renderChart() {
        const canvas = document.getElementById('categoryChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Simple pie chart simulation
        const data = this.getCategoryDistribution();
        const colors = ['#007AFF', '#34C759', '#FF9500', '#AF52DE', '#FF3B30'];
        
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Draw pie chart
        let currentAngle = 0;
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 20;

        data.forEach((segment, index) => {
            const sliceAngle = (segment.value / this.stats.total) * 2 * Math.PI;
            
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.lineTo(centerX, centerY);
            ctx.fillStyle = colors[index % colors.length];
            ctx.fill();
            
            currentAngle += sliceAngle;
        });
    }

    getCategoryDistribution() {
        const distribution = {};
        this.inventoryData.forEach(item => {
            distribution[item.category] = (distribution[item.category] || 0) + 1;
        });

        return Object.entries(distribution).map(([category, count]) => ({
            category,
            value: count
        }));
    }

    exportData(format) {
        this.showLoading();
        
        setTimeout(() => {
            this.hideLoading();
            
            switch(format) {
                case 'pdf':
                    this.exportToPDF();
                    break;
                case 'excel':
                    this.exportToExcel();
                    break;
                case 'csv':
                    this.exportToCSV();
                    break;
            }
            
            this.showToast(`Data berhasil diexport ke ${format.toUpperCase()}`, 'success');
        }, 1500);
    }

    exportToPDF() {
        // Simulate PDF export
        console.log('Exporting to PDF...');
    }

    exportToExcel() {
        // Simulate Excel export
        console.log('Exporting to Excel...');
    }

    exportToCSV() {
        // Simple CSV export
        const csvContent = this.inventoryData.map(item => 
            `${item.code},${item.name},${item.category},${item.status},${item.location}`
        ).join('\n');
        
        const blob = new Blob([`Kode,Nama,Kategori,Status,Lokasi\n${csvContent}`], 
                             { type: 'text/csv' });
        
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'inventory-data.csv';
        a.click();
        URL.revokeObjectURL(url);
    }

    // Settings Management
    handleSettingToggle(settingId, enabled) {
        const settings = {
            'dark-mode': enabled,
            'notifications': enabled,
            'auto-backup': enabled
        };

        // Apply setting
        switch(settingId) {
            case 'dark-mode':
                this.toggleDarkMode(enabled);
                break;
            case 'notifications':
                this.toggleNotifications(enabled);
                break;
            case 'auto-backup':
                this.toggleAutoBackup(enabled);
                break;
        }

        // Save to localStorage
        localStorage.setItem('app-settings', JSON.stringify(settings));
        
        this.showToast(
            `${this.getSettingName(settingId)} ${enabled ? 'diaktifkan' : 'dinonaktifkan'}`, 
            'info'
        );
    }

    getSettingName(settingId) {
        const names = {
            'dark-mode': 'Mode Gelap',
            'notifications': 'Notifikasi',
            'auto-backup': 'Auto Backup'
        };
        return names[settingId] || settingId;
    }

    toggleDarkMode(enabled) {
        // Dark mode implementation
        document.body.classList.toggle('dark-mode', enabled);
    }

    toggleNotifications(enabled) {
        // Notification handling
        if (enabled && 'Notification' in window) {
            Notification.requestPermission();
        }
    }

    toggleAutoBackup(enabled) {
        // Auto backup functionality
        console.log(`Auto backup ${enabled ? 'enabled' : 'disabled'}`);
    }

    editProfile() {
        this.showEditProfileModal();
    }

    // UI Utilities
    createRipple(element, event) {
        const ripple = document.createElement('div');
        ripple.className = 'ripple';
        
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        ripple.addEventListener('animationend', () => {
            ripple.remove();
        });
    }

    showLoading() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    hideLoading() {
        document.getElementById('loading-overlay').style.display = 'none';
    }

    showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-50px)';
            setTimeout(() => {
                container.removeChild(toast);
            }, 300);
        }, 3000);
    }

    showConfirmDialog(title, message, onConfirm) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.innerHTML = `
            <div class="confirm-dialog">
                <h3>${title}</h3>
                <p>${message}</p>
                <div class="dialog-actions">
                    <button class="btn-secondary" onclick="this.closest('.modal-backdrop').remove()">Batal</button>
                    <button class="btn-danger" id="confirm-btn">Hapus</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(backdrop);
        
        document.getElementById('confirm-btn').addEventListener('click', () => {
            onConfirm();
            backdrop.remove();
        });
        
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.remove();
            }
        });
    }

    // Time and Battery
    updateTime() {
        const timeElement = document.querySelector('.time');
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}`;
    }

    setupTimeInterval() {
        setInterval(() => {
            this.updateTime();
        }, 60000);
    }

    animateBatteryLevel() {
        const batteryLevel = document.querySelector('.battery-level');
        let level = 85;
        
        setInterval(() => {
            if (Math.random() < 0.1) {
                level = Math.max(20, level - 1);
                batteryLevel.style.width = `${level}%`;
                
                if (level < 30) {
                    batteryLevel.style.background = '#FF3B30';
                } else if (level < 50) {
                    batteryLevel.style.background = '#FF9500';
                } else {
                    batteryLevel.style.background = '#34C759';
                }
            }
        }, 30000);
    }

    // Scroll Animations
    setupScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        // Observe initial elements
        document.querySelectorAll('.stat-card, .action-card, .activity-item').forEach(el => {
            this.prepareForAnimation(el);
            observer.observe(el);
        });

        this.scrollObserver = observer;
    }

    applyScrollAnimations(elements) {
        elements.forEach((el, index) => {
            this.prepareForAnimation(el);
            el.style.animationDelay = `${index * 0.1}s`;
            this.scrollObserver.observe(el);
        });
    }

    prepareForAnimation(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    }

    // Haptic Feedback
    setupHapticFeedback() {
        if ('vibrate' in navigator) {
            document.addEventListener('click', (e) => {
                if (e.target.closest('.nav-item, .action-card, .btn-primary, .btn-secondary')) {
                    navigator.vibrate(10);
                }
            });
        }
    }

    // Additional Features
    initQRScanner() {
        this.showToast('QR Scanner akan dibuka...', 'info');
        // QR Scanner implementation would go here
    }

    showMenu() {
        // Menu implementation
        this.showToast('Menu contextual', 'info');
    }

    showActivityDetails(item) {
        // Activity details modal
        const title = item.querySelector('h4').textContent;
        this.showToast(`Detail: ${title}`, 'info');
    }

    showStatDetails(statType) {
        // Statistical details
        const statNames = {
            total: 'Total Barang',
            available: 'Barang Tersedia',
            'in-use': 'Barang Digunakan',
            maintenance: 'Barang Maintenance'
        };
        
        this.showToast(`Detail ${statNames[statType]}`, 'info');
    }

    showItemDetails(item) {
        // Item details modal implementation
        this.showToast(`Detail: ${item.name}`, 'info');
    }

    showEditModal(item) {
        // Edit modal implementation
        this.showToast(`Edit: ${item.name}`, 'info');
    }

    showEditProfileModal() {
        // Profile edit modal
        this.showToast('Edit Profil', 'info');
    }

    showExportOptions() {
        this.navigateToPage('reports');
        setTimeout(() => {
            document.querySelector('.export-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }, 500);
    }
}

// Additional CSS for animations
const additionalStyles = `
@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--ios-text-secondary);
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 20px;
    margin-bottom: 8px;
    color: var(--ios-text-primary);
}

.inventory-item {
    background: var(--ios-card-background);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: var(--radius-large);
    padding: 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-light);
}

.inventory-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.inventory-item .item-image {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--ios-blue), var(--ios-purple));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.inventory-item .item-info {
    flex: 1;
}

.inventory-item .item-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--ios-text-primary);
}

.inventory-item .item-code {
    font-size: 14px;
    color: var(--ios-blue);
    margin-bottom: 2px;
}

.inventory-item .item-location {
    font-size: 12px;
    color: var(--ios-text-secondary);
    margin-bottom: 8px;
}

.inventory-item .item-status {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
}

.inventory-item .item-status.tersedia {
    background: rgba(52, 199, 89, 0.1);
    color: var(--ios-green);
}

.inventory-item .item-status.digunakan {
    background: rgba(255, 149, 0, 0.1);
    color: var(--ios-orange);
}

.inventory-item .item-status.maintenance {
    background: rgba(255, 59, 48, 0.1);
    color: var(--ios-accent);
}

.inventory-item .item-actions {
    display: flex;
    gap: 8px;
}

.btn-icon.danger {
    background: rgba(255, 59, 48, 0.1);
    color: var(--ios-accent);
}

.btn-icon.danger:hover {
    background: rgba(255, 59, 48, 0.2);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.confirm-dialog {
    background: var(--ios-card-background);
    backdrop-filter: blur(20px);
    border-radius: var(--radius-large);
    padding: 24px;
    margin: 20px;
    max-width: 400px;
    width: 100%;
    text-align: center;
}

.confirm-dialog h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--ios-text-primary);
}

.confirm-dialog p {
    font-size: 14px;
    color: var(--ios-text-secondary);
    margin-bottom: 24px;
    line-height: 1.4;
}

.dialog-actions {
    display: flex;
    gap: 12px;
}

.btn-danger {
    background: var(--ios-accent);
    color: white;
    border: none;
    border-radius: var(--radius-medium);
    padding: 12px 24px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
}

.btn-danger:hover {
    background: #E0342E;
    transform: translateY(-1px);
}

.inventory-list.grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.inventory-list.grid-view .inventory-item {
    flex-direction: column;
    text-align: center;
    margin-bottom: 0;
}
`;

// Inject additional styles
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// Initialize app when DOM is loaded
let inventoryApp;
document.addEventListener('DOMContentLoaded', () => {
    inventoryApp = new InventoryApp();
});

// Export for global access
window.inventoryApp = inventoryApp;
