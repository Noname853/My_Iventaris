@extends('layouts.ios16')

@section('title', 'Tambah Pengguna - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1"><i class="bi bi-person-plus me-2"></i>Tambah Pengguna</h1>
            <p class="text-secondary mb-0">Buat akun pengguna baru untuk sistem inventory</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="ios-btn ios-btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-person-plus me-2"></i>Form Pengguna Baru</h5>
                </div>
                <div class="ios-card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium mb-2">Nama Lengkap <span style="color: var(--ios-red);">*</span></label>
                            <input type="text" class="ios-form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium mb-2">Email <span style="color: var(--ios-red);">*</span></label>
                            <input type="email" class="ios-form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="Masukkan alamat email">
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Email akan digunakan untuk login ke sistem</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-medium mb-2">Role <span style="color: var(--ios-red);">*</span></label>
                            <select class="ios-form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Tentukan hak akses pengguna dalam sistem</div>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kelas (only for siswa) -->
                        <div class="mb-4" id="kelas-field" style="display: none;">
                            <label for="kelas" class="form-label fw-medium mb-2">Kelas <span style="color: var(--ios-red);">*</span></label>
                            <input type="text" class="ios-form-control @error('kelas') is-invalid @enderror" 
                                   id="kelas" name="kelas" value="{{ old('kelas') }}" 
                                   placeholder="Contoh: XII TKJ 1">
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Masukkan kelas siswa (contoh: XII TKJ 1, XI RPL 2)</div>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Info -->
                        <div class="ios-card mb-4" style="background: var(--ios-gray6);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Role</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-3">
                                            <span class="ios-badge" style="background: rgba(255, 59, 48, 0.1); color: var(--ios-red);" class="me-2">Admin</span>
                                            <div>
                                                <div class="fw-medium">Administrator</div>
                                                <ul class="small mb-0 mt-1" style="color: var(--ios-secondary-label);">
                                                    <li>Mengelola semua data alat</li>
                                                    <li>Verifikasi peminjaman</li>
                                                    <li>Melihat laporan</li>
                                                    <li>Mengelola pengguna</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-3">
                                            <span class="ios-badge" style="background: rgba(0, 122, 255, 0.1); color: var(--ios-blue);" class="me-2">Siswa</span>
                                            <div>
                                                <div class="fw-medium">Siswa</div>
                                                <ul class="small mb-0 mt-1" style="color: var(--ios-secondary-label);">
                                                    <li>Melihat daftar alat</li>
                                                    <li>Mengajukan peminjaman</li>
                                                    <li>Melihat riwayat peminjaman</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium mb-2">Password <span style="color: var(--ios-red);">*</span></label>
                            <div class="input-group">
                                <input type="password" class="ios-form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required 
                                       placeholder="Masukkan password">
                                <button type="button" class="ios-btn ios-btn-secondary" style="border-left: none;" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                            </div>
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Password minimal 8 karakter</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium mb-2">Konfirmasi Password <span style="color: var(--ios-red);">*</span></label>
                            <div class="input-group">
                                <input type="password" class="ios-form-control" 
                                       id="password_confirmation" name="password_confirmation" required 
                                       placeholder="Ulangi password">
                                <button type="button" class="ios-btn ios-btn-secondary" style="border-left: none;" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Masukkan ulang password untuk konfirmasi</div>
                        </div>

                        <!-- Auto Generate Password -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_generate" onchange="toggleAutoGenerate()">
                                <label class="form-check-label fw-medium" for="auto_generate">
                                    Generate password otomatis
                                </label>
                            </div>
                            <div class="form-text" style="color: var(--ios-secondary-label); font-size: 14px;">Centang untuk membuat password acak yang aman</div>
                        </div>

                        <!-- Email Verification -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="email_verified">
                                    Tandai email sebagai terverifikasi
                                </label>
                            </div>
                            <div class="form-text" style="color: var(--ios-secondary-label); font-size: 14px;">Jika tidak dicentang, pengguna perlu memverifikasi email mereka</div>
                        </div>

                        <!-- Info Box -->
                        <div class="ios-card mb-4" style="background: rgba(0, 122, 255, 0.05); border: 1px solid rgba(0, 122, 255, 0.1);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-info-circle me-2" style="color: var(--ios-blue);"></i>Informasi Penting</h6>
                                <ul class="mb-0" style="color: var(--ios-secondary-label);">
                                    <li>Pengguna akan menerima email dengan detail akun mereka</li>
                                    <li>Pastikan email yang dimasukkan valid dan aktif</li>
                                    <li>Password dapat diubah oleh pengguna setelah login pertama</li>
                                    <li>Role dapat diubah kapan saja melalui menu edit pengguna</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="ios-btn ios-btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="ios-btn ios-btn-success">
                                <i class="bi bi-check2 me-2"></i>Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return password;
}

function toggleAutoGenerate() {
    const checkbox = document.getElementById('auto_generate');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    
    if (checkbox.checked) {
        const newPassword = generatePassword();
        passwordField.value = newPassword;
        confirmField.value = newPassword;
        passwordField.readOnly = true;
        confirmField.readOnly = true;
        
        // Show generated password
        alert('Password yang dibuat: ' + newPassword + '\n\nSilakan catat password ini untuk diberikan kepada pengguna.');
    } else {
        passwordField.value = '';
        confirmField.value = '';
        passwordField.readOnly = false;
        confirmField.readOnly = false;
    }
}

// Show/hide kelas field based on role selection
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const kelasField = document.getElementById('kelas-field');
    const kelasInput = document.getElementById('kelas');
    
    function toggleKelasField() {
        if (roleSelect.value === 'siswa') {
            kelasField.style.display = 'block';
            kelasInput.required = true;
        } else {
            kelasField.style.display = 'none';
            kelasInput.required = false;
            kelasInput.value = '';
        }
    }
    
    // Initial check
    toggleKelasField();
    
    // Listen for role changes
    roleSelect.addEventListener('change', toggleKelasField);
});
</script>
@endpush
@endsection
