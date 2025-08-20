<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory TKJ')</title>
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>
<body class="c-app">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" style="background: #fff; border-right: 1px solid #e5e5e5; min-width:220px; max-width:260px; height:100vh; position:sticky; top:0; z-index:1040;">
        <div class="offcanvas-header d-lg-none">
            <h5 class="offcanvas-title text-primary" id="sidebarLabel">Inventory TKJ</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="d-flex flex-column h-100 p-3" style="height:100vh;">
            <nav class="nav flex-column mb-auto">
                <a class="nav-link mb-2 d-flex align-items-center fw-semibold {{ request()->routeIs('dashboard') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> <span>Dashboard</span>
                </a>
                <a class="nav-link mb-2 d-flex align-items-center fw-semibold {{ request()->routeIs('alat.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('alat.index') }}">
                    <i class="fas fa-tools me-2"></i> <span>Alat</span>
                </a>
                <a class="nav-link mb-2 d-flex align-items-center fw-semibold {{ request()->routeIs('peminjaman.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('peminjaman.index') }}">
                    <i class="fas fa-list me-2"></i> <span>Peminjaman</span>
                </a>
                @if(auth()->user() && auth()->user()->isAdmin())
                <a class="nav-link mb-2 d-flex align-items-center fw-semibold {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i> <span>User</span>
                </a>
                <a class="nav-link mb-2 d-flex align-items-center fw-semibold {{ request()->routeIs('laporan') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('laporan') }}">
                    <i class="fas fa-chart-bar me-2"></i> <span>Laporan</span>
                </a>
                @endif
            </nav>
            <div class="border-top my-3"></div>
            <div class="text-center small text-muted mb-2">
                <i class="fas fa-user me-1"></i>Administrator
            </div>
        </div>
        <style>
            .c-sidebar {
                box-shadow: 0 2px 16px 0 rgba(0,0,0,0.06);
                border-radius: 0 1.25rem 1.25rem 0;
                background: #f8f9fa !important;
            }
            .c-sidebar .nav-link {
                border-radius: 0.5rem;
                transition: background 0.2s, color 0.2s;
                font-size: 1rem;
                padding: 0.75rem 1.1rem;
                font-weight: 500;
                color: #495057 !important;
            }
            .c-sidebar .nav-link.active,
            .c-sidebar .nav-link:hover {
                background: linear-gradient(90deg, #0d6efd 80%, #5a8dee 100%);
                color: #fff !important;
                box-shadow: 0 2px 8px rgba(13,110,253,0.08);
            }
            .c-sidebar .nav-link.text-dark {
                color: #495057 !important;
            }
            .c-sidebar .nav-link i {
                min-width: 22px;
                text-align: center;
                font-size: 1.15rem;
            }
            .c-sidebar .nav-link span {
                margin-left: 2px;
            }
            .c-sidebar .border-top {
                border-color: #e5e5e5 !important;
            }
        </style>
    </div>
    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed shadow-sm bg-white px-3 px-lg-4 py-2 d-flex align-items-center justify-content-between" style="min-height:64px; position:fixed; top:0; left:0; right:0; width:100vw; z-index:1100; box-shadow: 0 2px 12px rgba(13,110,253,0.06);">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" alt="Logo" style="width:32px;height:32px;" class="me-2">
                <span class="fw-bold text-primary fs-5 d-none d-md-inline" style="letter-spacing:0.5px;">Inventory TKJ</span>
            </div>
            <ul class="c-header-nav mb-0">
                <li class="c-header-nav-item dropdown">
                    <a class="c-header-nav-link" href="#" role="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-weight:500; color:#495057;">
                        <i class="fas fa-user-circle fa-lg me-1"></i> {{ auth()->user()->name ?? 'User' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </header>
        <div class="c-body" style="background:#f4f6fb;min-height:100vh;">
            <main class="c-main" style="margin-left:220px; padding: 2.2rem 2.5rem 2.5rem 2.5rem; min-height:calc(100vh - 64px); margin-top:64px; overflow-y:auto; height:calc(100vh - 64px); border-radius:1.25rem; background:#fff; box-shadow:0 2px 16px 0 rgba(0,0,0,0.04);">
                @yield('content')
            </main>
        </div>
        <style>
            @media (max-width: 991.98px) {
                #sidebar {
                    position: fixed !important;
                    min-width: 0 !important;
                    max-width: 100vw !important;
                    width: 80vw !important;
                    height: 100vh !important;
                    z-index: 1050 !important;
                    border-radius: 0 !important;
                }
                .c-main {
                    margin-left: 0 !important;
                    padding-top: 1rem !important;
                    border-radius: 0 !important;
                }
            }
            @media (max-width: 767.98px) {
                .c-main {
                    padding: 1rem !important;
                }
            }
        </style>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
