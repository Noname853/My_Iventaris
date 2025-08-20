<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory TKJ')</title>
    
    <!-- iOS 16 Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SF Symbols Icons (Alternative) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- iOS 16 Custom Styles -->
    <style>
        :root {
            /* iOS 16 System Colors */
            --ios-blue: #007AFF;
            --ios-green: #34C759;
            --ios-orange: #FF9500;
            --ios-red: #FF3B30;
            --ios-purple: #AF52DE;
            --ios-pink: #FF2D92;
            --ios-teal: #5AC8FA;
            --ios-yellow: #FFCC00;
            
            /* iOS 16 Gray Scale */
            --ios-gray: #8E8E93;
            --ios-gray2: #AEAEB2;
            --ios-gray3: #C7C7CC;
            --ios-gray4: #D1D1D6;
            --ios-gray5: #E5E5EA;
            --ios-gray6: #F2F2F7;
            
            /* iOS 16 Background Colors */
            --ios-background: #F2F2F7;
            --ios-secondary-background: #FFFFFF;
            --ios-tertiary-background: #FFFFFF;
            --ios-grouped-background: #F2F2F7;
            --ios-secondary-grouped-background: #FFFFFF;
            
            /* iOS 16 Text Colors */
            --ios-label: #000000;
            --ios-secondary-label: #3C3C43;
            --ios-tertiary-label: #3C3C43;
            --ios-quaternary-label: #3C3C43;
            
            /* iOS 16 Shadows & Effects */
            --ios-shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --ios-shadow-medium: 0 4px 12px rgba(0, 0, 0, 0.15);
            --ios-shadow-heavy: 0 8px 25px rgba(0, 0, 0, 0.15);
            
            /* iOS 16 Blur Effects */
            --ios-blur: blur(20px);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--ios-background);
            color: var(--ios-label);
            font-weight: 400;
            line-height: 1.5;
            overflow-x: hidden;
        }
        
        /* iOS 16 Navigation Bar */
        .ios-navbar {
            background: rgba(248, 248, 248, 0.95);
            backdrop-filter: var(--ios-blur);
            -webkit-backdrop-filter: var(--ios-blur);
            border-bottom: 0.5px solid var(--ios-gray5);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 12px 0;
            height: 70px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
        }
        
        /* Navbar adjustments for desktop with sidebar */
        @media (min-width: 1025px) {
            .ios-navbar {
                left: 280px;
                transition: left 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            
            .ios-navbar.sidebar-hidden {
                left: 0;
            }
        }
        
        .ios-navbar-brand {
            font-size: 22px;
            font-weight: 600;
            color: var(--ios-label);
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        .ios-navbar-toggler {
            border: none;
            background: rgba(0, 122, 255, 0.1);
            color: var(--ios-blue);
            font-size: 20px;
            padding: 10px;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .ios-navbar-toggler:hover {
            background: rgba(0, 122, 255, 0.2);
            transform: scale(0.95);
        }
        
        .ios-navbar-toggler:active {
            transform: scale(0.9);
        }
        
        /* iOS 16 Sidebar */
        .ios-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--ios-secondary-background);
            border-right: 0.5px solid var(--ios-gray5);
            z-index: 1020;
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .ios-sidebar-hidden {
            transform: translateX(-100%);
        }
        
        /* Desktop sidebar toggle button */
        .desktop-sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1040;
            background: rgba(248, 248, 248, 0.95);
            backdrop-filter: var(--ios-blur);
            border: none;
            color: var(--ios-blue);
            font-size: 18px;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            width: 44px;
            height: 44px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .desktop-sidebar-toggle:hover {
            background: rgba(0, 122, 255, 0.1);
            transform: scale(0.95);
        }
        
        @media (min-width: 1025px) {
            .desktop-sidebar-toggle {
                display: flex;
            }
        }
        
        .ios-sidebar-header {
            padding: 24px;
            border-bottom: 0.5px solid var(--ios-gray5);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 90px;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.05), rgba(175, 82, 222, 0.05));
            position: relative;
        }
        
        .ios-sidebar-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--ios-gray);
            font-size: 20px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
            display: none;
        }
        
        .ios-sidebar-close:hover {
            background: var(--ios-gray6);
            color: var(--ios-red);
        }
        
        @media (max-width: 1024px) {
            .ios-sidebar-close {
                display: block;
            }
        }
        
        .ios-sidebar-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--ios-blue), var(--ios-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
        }
        
        .ios-sidebar-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--ios-label);
            letter-spacing: -0.3px;
        }
        
        .ios-sidebar-nav {
            flex: 1;
            padding: 12px;
            overflow-y: auto;
        }
        
        .ios-nav-section {
            margin-bottom: 24px;
        }
        
        .ios-nav-section-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--ios-secondary-label);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 12px 8px 12px;
        }
        
        .ios-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin: 2px 0;
            border-radius: 12px;
            color: var(--ios-label);
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }
        
        .ios-nav-link:hover {
            background: var(--ios-gray6);
            color: var(--ios-label);
            transform: scale(0.98);
        }
        
        .ios-nav-link.active {
            background: var(--ios-blue);
            color: white;
            box-shadow: var(--ios-shadow-medium);
        }
        
        .ios-nav-link.active:hover {
            background: var(--ios-blue);
            color: white;
        }
        
        .ios-nav-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        
        /* iOS 16 Main Content */
        .ios-main-container {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .ios-main-container.sidebar-hidden {
            margin-left: 0;
        }
        
        .ios-main-content {
            padding: 24px;
        }
        
        /* iOS 16 Cards */
        .ios-card {
            background: var(--ios-secondary-background);
            border-radius: 16px;
            box-shadow: var(--ios-shadow-light);
            border: 0.5px solid var(--ios-gray5);
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .ios-card:hover {
            box-shadow: var(--ios-shadow-medium);
            transform: translateY(-2px);
        }
        
        .ios-card-header {
            padding: 20px 24px;
            border-bottom: 0.5px solid var(--ios-gray5);
            background: var(--ios-gray6);
        }
        
        .ios-card-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--ios-label);
            margin: 0;
            letter-spacing: -0.3px;
        }
        
        .ios-card-body {
            padding: 24px;
        }
        
        /* iOS 16 Buttons */
        .ios-btn {
            padding: 12px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .ios-btn-primary {
            background: var(--ios-blue);
            color: white;
        }
        
        .ios-btn-primary:hover {
            background: #0056CC;
            transform: scale(0.98);
            color: white;
        }
        
        .ios-btn-secondary {
            background: var(--ios-gray6);
            color: var(--ios-label);
        }
        
        .ios-btn-secondary:hover {
            background: var(--ios-gray5);
            color: var(--ios-label);
        }
        
        .ios-btn-success {
            background: var(--ios-green);
            color: white;
        }
        
        .ios-btn-danger {
            background: var(--ios-red);
            color: white;
        }
        
        /* iOS 16 Form Elements */
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
        
        /* iOS 16 Tables */
        .ios-table {
            background: var(--ios-secondary-background);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--ios-shadow-light);
            border: 0.5px solid var(--ios-gray5);
        }
        
        .ios-table table {
            width: 100%;
            margin: 0;
        }
        
        .ios-table th {
            background: var(--ios-gray6);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
            color: var(--ios-secondary-label);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 0.5px solid var(--ios-gray5);
        }
        
        .ios-table td {
            padding: 16px 20px;
            border-bottom: 0.5px solid var(--ios-gray5);
            font-size: 16px;
        }
        
        .ios-table tr:last-child td {
            border-bottom: none;
        }
        
        /* iOS 16 User Profile */
        .ios-user-profile {
            padding: 20px 24px;
            border-top: 0.5px solid var(--ios-gray5);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: auto;
        }
        
        .ios-user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--ios-blue), var(--ios-purple));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .ios-user-info {
            flex: 1;
        }
        
        .ios-user-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--ios-label);
            margin: 0;
        }
        
        .ios-user-role {
            font-size: 14px;
            color: var(--ios-secondary-label);
            margin: 0;
        }
        
        .ios-logout-btn {
            color: var(--ios-red);
            font-size: 20px;
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }
        
        .ios-logout-btn:hover {
            background: rgba(255, 59, 48, 0.1);
            color: var(--ios-red);
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .ios-navbar {
                left: 0 !important;
                height: 60px;
                padding: 8px 0;
            }
            
            .ios-sidebar {
                transform: translateX(-100%);
                z-index: 1050;
                box-shadow: 5px 0 25px rgba(0, 0, 0, 0.15);
            }
            
            .ios-sidebar.show {
                transform: translateX(0);
            }
            
            .ios-main-container {
                margin-left: 0;
                padding-top: 60px;
            }
            
            .ios-main-content {
                padding: 16px;
            }
            
            .ios-navbar-brand {
                font-size: 18px;
            }
            
            .desktop-sidebar-toggle {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            .ios-navbar {
                height: 56px;
                padding: 6px 0;
            }
            
            .ios-main-container {
                padding-top: 56px;
            }
            
            .ios-main-content {
                padding: 12px;
            }
            
            .ios-card {
                margin-bottom: 16px;
                border-radius: 12px;
            }
            
            .ios-card-body {
                padding: 16px;
            }
            
            .ios-navbar-brand {
                font-size: 16px;
            }
            
            .ios-sidebar {
                width: 100vw;
            }
            
            .ios-sidebar-header {
                padding: 20px;
                min-height: 70px;
            }
            
            .ios-sidebar-nav {
                padding: 8px;
            }
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        @media (min-width: 1025px) {
            .sidebar-overlay {
                display: none;
            }
        }
        
        /* Smooth Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--ios-gray3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--ios-gray2);
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* iOS 16 Status Badge */
        .ios-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .ios-badge-success {
            background: rgba(52, 199, 89, 0.1);
            color: var(--ios-green);
        }
        
        .ios-badge-warning {
            background: rgba(255, 149, 0, 0.1);
            color: var(--ios-orange);
        }
        
        .ios-badge-danger {
            background: rgba(255, 59, 48, 0.1);
            color: var(--ios-red);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Desktop Sidebar Toggle -->
    <button class="desktop-sidebar-toggle" id="desktopSidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- iOS 16 Navigation Bar -->
    <nav class="ios-navbar" id="navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="ios-navbar-toggler d-lg-none me-3" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <a href="{{ route('dashboard') }}" class="ios-navbar-brand">
                        <i class="bi bi-box-seam me-2 d-none d-md-inline"></i>
                        Inventory TKJ
                    </a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <i class="bi bi-calendar3 text-secondary"></i>
                        <span class="text-secondary">{{ now()->format('M j, Y') }}</span>
                    </div>
                    <div class="d-none d-lg-flex align-items-center gap-2">
                        <div class="ios-user-avatar" style="width: 32px; height: 32px; font-size: 14px;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="d-flex flex-column">
                            <small class="fw-medium mb-0">{{ auth()->user()->name ?? 'User' }}</small>
                            <small class="text-secondary">{{ ucfirst(auth()->user()->role ?? 'user') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- iOS 16 Sidebar -->
    <aside class="ios-sidebar" id="sidebar">
        <div class="ios-sidebar-header">
            <div class="ios-sidebar-logo">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="ios-sidebar-title">Inventory TKJ</div>
            </div>
            <button class="ios-sidebar-close" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <nav class="ios-sidebar-nav">
            <div class="ios-nav-section">
                <div class="ios-nav-section-title">Main</div>
                <a href="{{ route('dashboard') }}" 
                   class="ios-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house ios-nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="ios-nav-section">
                <div class="ios-nav-section-title">Inventory</div>
                <a href="{{ route('alat.index') }}" 
                   class="ios-nav-link {{ request()->routeIs('alat.*') ? 'active' : '' }}">
                    <i class="bi bi-tools ios-nav-icon"></i>
                    <span>Alat</span>
                </a>
                <a href="{{ route('peminjaman.index') }}" 
                   class="ios-nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text ios-nav-icon"></i>
                    <span>Peminjaman</span>
                </a>
            </div>
            
            @if(auth()->user() && auth()->user()->isAdmin())
            <div class="ios-nav-section">
                <div class="ios-nav-section-title">Admin</div>
                <a href="{{ route('admin.users.index') }}" 
                   class="ios-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people ios-nav-icon"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('laporan') }}" 
                   class="ios-nav-link {{ request()->routeIs('laporan') ? 'active' : '' }}">
                    <i class="bi bi-graph-up ios-nav-icon"></i>
                    <span>Laporan</span>
                </a>
            </div>
            @endif
        </nav>
        
        <div class="ios-user-profile">
            <div class="ios-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="ios-user-info">
                <div class="ios-user-name">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="ios-user-role">{{ ucfirst(auth()->user()->role ?? 'user') }}</div>
            </div>
            <a href="#" class="ios-logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="ios-main-container" id="mainContainer">
        <div class="ios-main-content fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- iOS 16 Custom JavaScript -->
    <script>
        // Enhanced Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContainer = document.getElementById('mainContainer');
            const navbar = document.getElementById('navbar');
            
            let sidebarHidden = false;
            
            function toggleSidebar() {
                if (window.innerWidth <= 1024) {
                    // Mobile behavior
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                } else {
                    // Desktop behavior
                    sidebarHidden = !sidebarHidden;
                    sidebar.classList.toggle('ios-sidebar-hidden', sidebarHidden);
                    mainContainer.classList.toggle('sidebar-hidden', sidebarHidden);
                    navbar.classList.toggle('sidebar-hidden', sidebarHidden);
                    
                    // Store preference
                    localStorage.setItem('sidebarHidden', sidebarHidden);
                }
            }
            
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            function openSidebar() {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
            }
            
            // Initialize sidebar state from localStorage
            const savedState = localStorage.getItem('sidebarHidden');
            if (savedState === 'true' && window.innerWidth > 1024) {
                sidebarHidden = true;
                sidebar.classList.add('ios-sidebar-hidden');
                mainContainer.classList.add('sidebar-hidden');
                navbar.classList.add('sidebar-hidden');
            }
            
            // Mobile sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            // Desktop sidebar toggle
            if (desktopSidebarToggle) {
                desktopSidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            // Sidebar close button
            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    if (!sidebar.contains(e.target) && 
                        !sidebarToggle?.contains(e.target) &&
                        !desktopSidebarToggle?.contains(e.target)) {
                        closeSidebar();
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                    // Restore desktop sidebar state
                    const savedState = localStorage.getItem('sidebarHidden');
                    if (savedState === 'true') {
                        sidebar.classList.add('ios-sidebar-hidden');
                        mainContainer.classList.add('sidebar-hidden');
                        navbar.classList.add('sidebar-hidden');
                        sidebarHidden = true;
                    }
                } else {
                    // Reset desktop sidebar classes on mobile
                    sidebar.classList.remove('ios-sidebar-hidden');
                    mainContainer.classList.remove('sidebar-hidden');
                    navbar.classList.remove('sidebar-hidden');
                }
            });
            
            // Close sidebar when navigation link is clicked on mobile
            document.querySelectorAll('.ios-nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        setTimeout(() => closeSidebar(), 100);
                    }
                });
            });
            
            // Add swipe gestures for mobile
            let startX = null;
            let currentX = null;
            
            document.addEventListener('touchstart', function(e) {
                if (window.innerWidth <= 1024) {
                    startX = e.touches[0].clientX;
                }
            });
            
            document.addEventListener('touchmove', function(e) {
                if (window.innerWidth <= 1024 && startX !== null) {
                    currentX = e.touches[0].clientX;
                    const diffX = startX - currentX;
                    
                    // Swipe left to close sidebar (when sidebar is open)
                    if (diffX > 50 && sidebar.classList.contains('show')) {
                        closeSidebar();
                        startX = null;
                        currentX = null;
                    }
                    // Swipe right to open sidebar (when sidebar is closed)
                    else if (diffX < -50 && !sidebar.classList.contains('show') && startX < 50) {
                        openSidebar();
                        startX = null;
                        currentX = null;
                    }
                }
            });
            
            document.addEventListener('touchend', function() {
                startX = null;
                currentX = null;
            });
            
            // Smooth scrolling for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add loading animation to buttons
            document.querySelectorAll('.ios-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('loading')) {
                        this.classList.add('loading');
                        setTimeout(() => {
                            this.classList.remove('loading');
                        }, 2000);
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
