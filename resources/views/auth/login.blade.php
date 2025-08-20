<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Inventaris TKJ - Login untuk mengakses sistem inventaris sekolah">
    <meta name="theme-color" content="#6366F1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">
    
    <title>Login - Inventory TKJ</title>
    
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366F1;
            --primary-dark: #4F46E5;
            --secondary-color: #F59E0B;
            --accent-color: #EC4899;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --dark-color: #1F2937;
            --light-gray: #F9FAFB;
            --medium-gray: #6B7280;
            --border-color: #E5E7EB;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 15px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx=".5" cy=".5" r=".5"><stop offset="0%" stop-color="%23ffffff" stop-opacity=".1"/><stop offset="100%" stop-color="%23000000" stop-opacity=".05"/></radialGradient></defs><rect width="1000" height="1000" fill="url(%23a)"/></svg>') center/cover no-repeat;
            pointer-events: none;
            z-index: -1;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--accent-color) 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .login-logo {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            color: #FFFFFF;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: pulse 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        
        .login-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            letter-spacing: -1px;
        }
        
        .login-subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 2;
            font-weight: 300;
            line-height: 1.6;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            position: relative;
            z-index: 2;
            margin-top: 1rem;
        }
        
        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 400;
            opacity: 0.9;
            transform: translateX(20px);
            animation: slideIn 0.6s ease forwards;
        }
        
        .feature-list li:nth-child(1) { animation-delay: 0.2s; }
        .feature-list li:nth-child(2) { animation-delay: 0.4s; }
        .feature-list li:nth-child(3) { animation-delay: 0.6s; }
        .feature-list li:nth-child(4) { animation-delay: 0.8s; }
        
        @keyframes slideIn {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .feature-list i {
            margin-right: 1rem;
            color: #FFFFFF;
            width: 24px;
            font-size: 1.1rem;
        }
        
        .login-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            text-align: center;
            letter-spacing: -0.5px;
        }
        
        .form-subtitle {
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        .form-floating {
            margin-bottom: 2rem;
            position: relative;
        }
        
        .form-floating > .form-control {
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 1.25rem 1rem;
            font-size: 1.05rem;
            font-weight: 500;
            height: 60px;
            background: var(--light-gray);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), var(--shadow-md);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-floating > .form-control:not(:placeholder-shown) {
            background: white;
        }
        
        .form-floating > label {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-color);
            transform: scale(0.85) translateY(-0.5rem);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 16px;
            padding: 1.25rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent-color) 100%);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .form-check {
            margin-bottom: 2rem;
            padding-left: 2rem;
        }
        
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 6px;
            border: 2px solid var(--border-color);
            margin-left: -2rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .form-check-label {
            font-weight: 500;
            color: var(--text-secondary);
            margin-left: 0.75rem;
        }
        
        .btn-register {
            border: 2px solid var(--primary-color);
            border-radius: 16px;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            background: transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-register:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .alert {
            border: none;
            border-radius: 16px;
            margin-bottom: 2rem;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .login-container {
                max-width: 500px;
                min-height: auto;
                margin: 20px;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            body {
                padding: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .login-container {
                border-radius: 20px;
                margin: 10px;
            }
            
            .login-right {
                padding: 30px 25px;
            }
            
            .login-title {
                font-size: 2.2rem;
                margin-bottom: 0.8rem;
            }
            
            .form-title {
                font-size: 1.8rem;
                margin-bottom: 0.6rem;
            }
            
            .form-subtitle {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            
            .form-floating {
                margin-bottom: 1.5rem;
            }
            
            .form-floating > .form-control {
                height: 56px;
                font-size: 1rem;
            }
            
            .btn-login {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
            
            .btn-register {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px 5px;
            }
            
            .login-container {
                border-radius: 16px;
                margin: 5px;
            }
            
            .login-right {
                padding: 25px 20px;
            }
            
            .form-title {
                font-size: 1.6rem;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
            
            .form-floating > .form-control {
                height: 52px;
                padding: 1rem 0.875rem;
            }
            
            .btn-login {
                padding: 0.875rem 1.25rem;
            }
            
            .feature-list li {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 360px) {
            .form-title {
                font-size: 1.4rem;
            }
            
            .form-subtitle {
                font-size: 0.9rem;
            }
            
            .form-floating > .form-control {
                height: 50px;
                font-size: 0.95rem;
            }
            
            .btn-login {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0 h-100">
            <!-- Left Side - Branding -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="login-left h-100">
                    <div>
                        <i class="bi bi-box-seam login-logo"></i>
                        <h1 class="login-title">Inventory TKJ</h1>
                        <p class="login-subtitle">Sistem Manajemen Inventaris Teknik Komputer dan Jaringan</p>
                        
                        <ul class="feature-list">
                            <li><i class="bi bi-check-circle"></i> Kelola alat dan perangkat TKJ</li>
                            <li><i class="bi bi-check-circle"></i> Sistem peminjaman terintegrasi</li>
                            <li><i class="bi bi-check-circle"></i> Laporan dan monitoring real-time</li>
                            <li><i class="bi bi-check-circle"></i> Interface yang user-friendly</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="col-lg-6">
                <div class="login-right h-100">
                    <div class="d-lg-none text-center mb-4">
                        <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--ios-blue);"></i>
                        <h2 class="mt-2" style="color: var(--ios-label);">Inventory TKJ</h2>
                    </div>
                    
                    <h2 class="form-title">Selamat Datang</h2>
                    <p class="form-subtitle">Silakan masuk ke akun Anda</p>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="form-floating">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com" 
                                   required 
                                   autocomplete="email" 
                                   autofocus>
                            <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Password" 
                                   required 
                                   autocomplete="current-password">
                            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>

                        <div class="mt-3 text-center">
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-register">
                                <i class="bi bi-person-plus me-2"></i>Daftar Akun Baru
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
