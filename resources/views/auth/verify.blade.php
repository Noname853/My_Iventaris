<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Verify Email - Inventory TKJ</title>
    
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
            --ios-green: #34C759;
            --ios-gray5: #E5E5EA;
            --ios-secondary-background: #FFFFFF;
            --ios-label: #000000;
            --ios-secondary-label: #3C3C43;
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
        
        .verify-container {
            background: var(--ios-secondary-background);
            border-radius: 20px;
            box-shadow: var(--ios-shadow-medium);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .verify-icon {
            font-size: 4rem;
            color: var(--ios-blue);
            margin-bottom: 1.5rem;
        }
        
        .verify-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--ios-label);
            margin-bottom: 1rem;
        }
        
        .verify-message {
            color: var(--ios-secondary-label);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .alert-success {
            background: rgba(52, 199, 89, 0.1);
            color: var(--ios-green);
            border: 1px solid rgba(52, 199, 89, 0.2);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .btn-resend {
            background: var(--ios-blue);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: inline-block;
            cursor: pointer;
        }
        
        .btn-resend:hover {
            background: #0056CC;
            transform: translateY(-1px);
            box-shadow: var(--ios-shadow-medium);
            color: white;
        }
        
        .back-link {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--ios-gray5);
        }
        
        .back-link a {
            color: var(--ios-blue);
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link a:hover {
            color: #0056CC;
        }
        
        @media (max-width: 768px) {
            .verify-container {
                padding: 24px;
                margin: 12px;
            }
            
            .verify-icon {
                font-size: 3rem;
            }
            
            .verify-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <i class="bi bi-envelope-check verify-icon"></i>
        <h1 class="verify-title">Verifikasi Email</h1>
        
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif
        
        <p class="verify-message">
            Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
            <br><br>
            Jika Anda tidak menerima email tersebut, klik tombol di bawah ini untuk mengirim ulang.
        </p>
        
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-resend">
                <i class="bi bi-arrow-repeat me-2"></i>
                Kirim Ulang Email Verifikasi
            </button>
        </form>
        
        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Login
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
