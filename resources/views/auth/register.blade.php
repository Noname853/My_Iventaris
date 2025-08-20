<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Register - Inventory TKJ</title>
    
    <!-- iOS 16 Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --ios-blue: #007AFF;
            --ios-gray: #8E8E93;
            --ios-gray5: #E5E5EA;
            --ios-gray6: #F2F2F7;
            --ios-background: #F2F2F7;
            --ios-secondary-background: #FFFFFF;
            --ios-label: #000000;
            --ios-secondary-label: #3C3C43;
            --ios-shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --ios-shadow-medium: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--ios-blue) 0%, #5AC8FA 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .register-container {
            background: var(--ios-secondary-background);
            border-radius: 20px;
            box-shadow: var(--ios-shadow-medium);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(10px);
        }
        
        .register-header {
            padding: 40px 40px 20px 40px;
            text-align: center;
        }
        
        .register-logo {
            font-size: 3rem;
            color: var(--ios-blue);
            margin-bottom: 1rem;
        }
        
        .register-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--ios-label);
            margin-bottom: 0.5rem;
        }
        
        .register-subtitle {
            color: var(--ios-secondary-label);
            font-size: 0.95rem;
        }
        
        .register-body {
            padding: 20px 40px 40px 40px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--ios-label);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 1px solid var(--ios-gray5);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: var(--ios-secondary-background);
        }
        
        .form-control:focus {
            border-color: var(--ios-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 122, 255, 0.15);
            outline: none;
        }
        
        .btn-register {
            background: var(--ios-blue);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            margin-bottom: 1rem;
        }
        
        .btn-register:hover {
            background: #0056CC;
            transform: translateY(-1px);
            box-shadow: var(--ios-shadow-medium);
        }
        
        .form-text {
            font-size: 0.85rem;
            color: var(--ios-secondary-label);
            margin-top: 0.25rem;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
            color: #FF3B30;
        }
        
        .login-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--ios-gray5);
        }
        
        .login-link a {
            color: var(--ios-blue);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #0056CC;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--ios-secondary-label);
            cursor: pointer;
            padding: 0;
        }
        
        .position-relative {
            position: relative;
        }
        
        @media (max-width: 768px) {
            .register-header,
            .register-body {
                padding-left: 24px;
                padding-right: 24px;
            }
            
            body {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <i class="bi bi-person-plus register-logo"></i>
            <h2 class="register-title">Buat Akun</h2>
            <p class="register-subtitle">Daftar untuk akses sistem inventory</p>
        </div>
        <div class="register-body">
            <form method="POST" action="{{ route('register') }}" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="bi bi-person me-2"></i>Nama Lengkap
                </label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama lengkap">
                @error('name')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-2"></i>Email
                </label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email aktif">
                @error('email')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>Password
                </label>
                <div class="position-relative">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="password-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password-confirm" class="form-label">
                    <i class="bi bi-lock me-2"></i>Konfirmasi Password
                </label>
                <div class="position-relative">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                        <i class="bi bi-eye" id="password-confirm-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="kelas" class="form-label">
                    <i class="bi bi-mortarboard me-2"></i>Kelas
                </label>
                <input id="kelas" type="text" class="form-control @error('kelas') is-invalid @enderror" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: XII TKJ A">
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>Masukkan kelas Anda (contoh: X TKJ A, XI RPL B)
                </div>
                @error('kelas')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="kelompok" class="form-label">
                    <i class="bi bi-people me-2"></i>Kelompok
                </label>
                <input id="kelompok" type="text" class="form-control @error('kelompok') is-invalid @enderror" name="kelompok" value="{{ old('kelompok') }}" required placeholder="Contoh: Kelompok 1">
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>Masukkan nama kelompok Anda
                </div>
                @error('kelompok')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus me-2"></i>Daftar
            </button>
            
            <div class="login-link">
                <p class="mb-0">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const eye = document.getElementById(id + '-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('bi-eye');
        eye.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('bi-eye-slash');
        eye.classList.add('bi-eye');
    }
}
</script>
</body>
</html>
