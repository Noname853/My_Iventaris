// iOS 16 Theme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Update time in status bar
    function updateTime() {
        const timeElement = document.querySelector('.time');
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}`;
    }
    
    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);
    
    // Bottom navigation interaction
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            navItems.forEach(navItem => navItem.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Add ripple effect
            createRipple(this, event);
            
            // Simulate page navigation (you can replace this with actual navigation)
            const itemName = this.querySelector('span').textContent;
            console.log(`Navigating to: ${itemName}`);
        });
    });
    
    // Action cards interaction
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add press animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Create ripple effect
            createRipple(this, e);
            
            // Get action type
            const actionTitle = this.querySelector('h3').textContent;
            handleActionClick(actionTitle);
        });
        
        // Add hover effect for desktop
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Activity items interaction
    const activityItems = document.querySelectorAll('.activity-item');
    activityItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add highlight effect
            this.style.background = 'var(--ios-tint-blue)';
            setTimeout(() => {
                this.style.background = '';
            }, 200);
            
            const activityTitle = this.querySelector('h4').textContent;
            console.log(`Activity clicked: ${activityTitle}`);
        });
    });
    
    // Search bar functionality
    const searchInput = document.querySelector('.search-bar input') || document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.boxShadow = '0 8px 30px rgba(0, 122, 255, 0.2)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.style.transform = '';
            this.parentElement.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        });
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (query.length > 0) {
                console.log(`Searching for: ${query}`);
                // Here you would implement actual search functionality
                performSearch(query);
            }
        });
    }
    
    // Navigation buttons
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add press effect
            this.style.transform = 'scale(0.9)';
            this.style.background = 'rgba(255, 255, 255, 0.2)';
            
            setTimeout(() => {
                this.style.transform = '';
                this.style.background = '';
            }, 150);
            
            const icon = this.querySelector('i').className;
            if (icon.includes('bars')) {
                toggleSidebar();
            } else if (icon.includes('user')) {
                showUserProfile();
            }
        });
    });
    
    // Stat cards hover effect
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
        
        card.addEventListener('click', function() {
            const statType = this.querySelector('h3').textContent;
            showStatDetails(statType);
        });
    });
    
    // Battery level animation
    animateBatteryLevel();
    
    // Add scroll animations
    addScrollAnimations();
    
    // Initialize haptic feedback (for mobile devices)
    initializeHapticFeedback();
    
    // Item cards interaction
    const itemCards = document.querySelectorAll('.item-card');
    itemCards.forEach(card => {
        card.addEventListener('click', function() {
            // Add highlight effect
            this.style.background = 'var(--ios-tint-blue)';
            setTimeout(() => {
                this.style.background = '';
            }, 200);
            
            const itemName = this.querySelector('.item-name').textContent;
            console.log(`Item clicked: ${itemName}`);
        });
    });
    
    // Button icons interaction
    const btnIcons = document.querySelectorAll('.btn-icon');
    btnIcons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Add press effect
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            const icon = this.querySelector('i').className;
            if (icon.includes('edit')) {
                console.log('Edit button clicked');
            } else if (icon.includes('eye')) {
                console.log('View button clicked');
            }
        });
    });
    
    // Modal functionality
    const addModal = document.getElementById('addModal');
    const closeModal = document.getElementById('closeModal');
    
    if (closeModal && addModal) {
        closeModal.addEventListener('click', function() {
            addModal.style.display = 'none';
        });
        
        // Close modal on backdrop click
        addModal.addEventListener('click', function(e) {
            if (e.target === addModal) {
                addModal.style.display = 'none';
            }
        });
    }
});

// Create ripple effect function
function createRipple(element, event) {
    const ripple = document.createElement('div');
    ripple.style.position = 'absolute';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'rgba(0, 122, 255, 0.3)';
    ripple.style.transform = 'scale(0)';
    ripple.style.animation = 'ripple 0.6s linear';
    ripple.style.pointerEvents = 'none';
    
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    ripple.addEventListener('animationend', () => {
        ripple.remove();
    });
}

// Handle action card clicks
function handleActionClick(actionTitle) {
    switch(actionTitle) {
        case 'Tambah Barang':
            showAddItemModal();
            break;
        case 'Scan QR Code':
            initQRScanner();
            break;
        case 'Laporan':
            showReportsPage();
            break;
        case 'Pengaturan':
            showSettingsPage();
            break;
        default:
            console.log(`Action: ${actionTitle}`);
    }
}

// Simulate add item modal
function showAddItemModal() {
    // Create modal backdrop
    const backdrop = document.createElement('div');
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    `;
    
    // Create modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 24px;
        margin: 20px;
        max-width: 400px;
        width: 100%;
        animation: slideUp 0.3s ease;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    `;
    
    modal.innerHTML = `
        <h2 style="margin-bottom: 16px; color: #000; font-size: 20px; font-weight: 600;">Tambah Barang Baru</h2>
        <form style="display: flex; flex-direction: column; gap: 16px;">
            <input type="text" placeholder="Nama Barang" style="padding: 12px; border: 1px solid #ddd; border-radius: 12px; font-size: 16px;">
            <input type="text" placeholder="Kode Barang" style="padding: 12px; border: 1px solid #ddd; border-radius: 12px; font-size: 16px;">
            <input type="number" placeholder="Jumlah" style="padding: 12px; border: 1px solid #ddd; border-radius: 12px; font-size: 16px;">
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <button type="button" onclick="this.closest('.backdrop').remove()" style="flex: 1; padding: 12px; background: #f0f0f0; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">Batal</button>
                <button type="submit" style="flex: 1; padding: 12px; background: #007AFF; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">Simpan</button>
            </div>
        </form>
    `;
    
    backdrop.className = 'backdrop';
    backdrop.appendChild(modal);
    document.body.appendChild(backdrop);
    
    // Close on backdrop click
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) {
            backdrop.remove();
        }
    });
}

// Simulate QR scanner
function initQRScanner() {
    alert('QR Scanner akan dibuka\n(Fitur ini memerlukan kamera)');
}

// Show reports page
function showReportsPage() {
    console.log('Navigating to Reports page...');
    // Here you would implement actual page navigation
}

// Show settings page
function showSettingsPage() {
    console.log('Navigating to Settings page...');
    // Here you would implement actual page navigation
}

// Toggle sidebar (hamburger menu)
function toggleSidebar() {
    console.log('Sidebar toggled');
    // Here you would implement sidebar functionality
}

// Show user profile
function showUserProfile() {
    console.log('User profile opened');
    // Here you would implement user profile modal
}

// Show stat details
function showStatDetails(statType) {
    console.log(`Showing details for: ${statType}`);
    // Here you would implement stat details modal or page
}

// Perform search
function performSearch(query) {
    console.log(`Performing search for: ${query}`);
    // Here you would implement actual search functionality
    // You could filter inventory items, show suggestions, etc.
}

// Animate battery level
function animateBatteryLevel() {
    const batteryLevel = document.querySelector('.battery-level');
    let level = 80; // Start at 80%
    
    setInterval(() => {
        // Simulate battery drain (very slow)
        if (Math.random() < 0.1) { // 10% chance every interval
            level = Math.max(20, level - 1);
            batteryLevel.style.width = `${level}%`;
            
            // Change color based on battery level
            if (level < 30) {
                batteryLevel.style.background = '#FF3B30'; // Red
            } else if (level < 50) {
                batteryLevel.style.background = '#FF9500'; // Orange
            } else {
                batteryLevel.style.background = '#34C759'; // Green
            }
        }
    }, 30000); // Check every 30 seconds
}

// Add scroll animations
function addScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Observe elements for animation
    document.querySelectorAll('.stat-card, .action-card, .activity-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Initialize haptic feedback for mobile devices
function initializeHapticFeedback() {
    if ('vibrate' in navigator) {
        // Add vibration to button clicks
        document.querySelectorAll('.action-card, .nav-item, .nav-btn').forEach(element => {
            element.addEventListener('click', () => {
                navigator.vibrate(10); // Light vibration
            });
        });
    }
}

// CSS animations to be injected
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
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
    
    .nav-item {
        transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .action-card {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .search-bar {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
`;
document.head.appendChild(style);

// Export functions for global access if needed
window.iOSTheme = {
    handleActionClick,
    showAddItemModal,
    initQRScanner,
    showReportsPage,
    showSettingsPage,
    performSearch
};
