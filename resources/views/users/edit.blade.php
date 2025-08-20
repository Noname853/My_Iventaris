@extends('layouts.ios16')

@section('title', 'Edit Pengguna - Inventory TKJ')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="ios-card-title mb-1"><i class="bi bi-person-gear me-2"></i>Edit Pengguna</h1>
            <p class="text-secondary mb-0">Perbarui informasi pengguna sistem inventory</p>
        </div>
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="ios-btn ios-btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Current User Info -->
    <div class="ios-card mb-4" style="background: linear-gradient(135deg, var(--ios-blue), var(--ios-purple));">
        <div class="ios-card-body">
            <div class="row align-items-center text-white">
                <div class="col-auto">
                    <div class="ios-user-avatar" style="width: 64px; height: 64px; font-size: 24px; background: rgba(255,255,255,0.2);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex align-items-center mb-2">
                        <h5 class="mb-0 me-3">{{ $user->name }}</h5>
                        <span class="ios-badge" style="background: rgba(255,255,255,0.2); color: white;">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->id == auth()->id())
                            <span class="ios-badge ms-2" style="background: rgba(255, 149, 0, 0.3); color: white;">Akun Anda</span>
                        @endif
                    </div>
                    <p class="mb-0 opacity-75">{{ $user->email }}</p>
                    @if($user->kelas)
                        <p class="mb-0 opacity-75 small">{{ $user->kelas }}</p>
                    @endif
                </div>
                <div class="col-auto">
                    <i class="bi bi-pencil-square fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="ios-card">
                <div class="ios-card-header">
                    <h5 class="ios-card-title mb-0"><i class="bi bi-person-gear me-2"></i>Form Edit Pengguna</h5>
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

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium mb-2">Nama Lengkap <span style="color: var(--ios-red);">*</span></label>
                            <input type="text" class="ios-form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required 
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium mb-2">Email <span style="color: var(--ios-red);">*</span></label>
                            <input type="email" class="ios-form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   placeholder="Masukkan alamat email">
                            <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Email akan digunakan untuk login ke sistem</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-medium mb-2">Role <span style="color: var(--ios-red);">*</span></label>
                            <select class="ios-form-control @error('role') is-invalid @enderror" id="role" name="role" required 
                                    {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                            @if($user->id == auth()->id())
                                <div class="form-text mt-1" style="color: var(--ios-orange); font-size: 14px;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Anda tidak dapat mengubah role akun Anda sendiri
                                </div>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @else
                                <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Tentukan hak akses pengguna dalam sistem</div>
                            @endif
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kelas (only for siswa) -->
                        <div class="mb-4" id="kelas-field" style="display: {{ old('role', $user->role) == 'siswa' ? 'block' : 'none' }};">
                            <label for="kelas" class="form-label fw-medium mb-2">Kelas <span style="color: var(--ios-red);">*</span></label>
                            <input type="text" class="ios-form-control @error('kelas') is-invalid @enderror" 
                                   id="kelas" name="kelas" value="{{ old('kelas', $user->kelas) }}" 
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

                        <!-- Email Verification Status -->
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-2">Status Verifikasi Email</label>
                            <div class="ios-card" style="background: var(--ios-gray6);">
                                <div class="ios-card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            @if($user->email_verified_at)
                                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                                <div>
                                                    <div class="fw-medium">Email Terverifikasi</div>
                                                    <small style="color: var(--ios-secondary-label);">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            @else
                                                <i class="bi bi-exclamation-circle-fill text-warning fs-4 me-3"></i>
                                                <div>
                                                    <div class="fw-medium">Belum Terverifikasi</div>
                                                    <small style="color: var(--ios-secondary-label);">Pengguna perlu memverifikasi email</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" 
                                                   {{ $user->email_verified_at ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_verified">
                                                Verifikasi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="ios-card mb-4" style="border: 1px solid rgba(255, 149, 0, 0.2);">
                            <div class="ios-card-header" style="background: rgba(255, 149, 0, 0.1);">
                                <h6 class="mb-0 fw-medium"><i class="bi bi-key me-2" style="color: var(--ios-orange);"></i>Ubah Password (Opsional)</h6>
                            </div>
                            <div class="ios-card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="change_password" onchange="togglePasswordFields()">
                                    <label class="form-check-label fw-medium" for="change_password">
                                        Ubah password pengguna
                                    </label>
                                </div>
                                
                                <div id="password-fields" style="display: none;">
                                    <!-- New Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-medium mb-2">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="ios-form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" 
                                                   placeholder="Masukkan password baru">
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
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label fw-medium mb-2">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="ios-form-control" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Ulangi password baru">
                                            <button type="button" class="ios-btn ios-btn-secondary" style="border-left: none;" onclick="togglePassword('password_confirmation')">
                                                <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                            </button>
                                        </div>
                                        <div class="form-text mt-1" style="color: var(--ios-secondary-label); font-size: 14px;">Masukkan ulang password untuk konfirmasi</div>
                                    </div>

                                    <!-- Auto Generate Password -->
                                    <div class="mb-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_generate" onchange="toggleAutoGenerate()">
                                            <label class="form-check-label fw-medium" for="auto_generate">
                                                Generate password otomatis
                                            </label>
                                        </div>
                                        <div class="form-text" style="color: var(--ios-secondary-label); font-size: 14px;">Centang untuk membuat password acak yang aman</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Activity Info -->
                        @if($user->role == 'siswa')
                        <div class="ios-card mb-4" style="background: var(--ios-gray6);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-graph-up me-2"></i>Aktivitas Pengguna</h6>
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-blue), #0056CC);">
                                            <div class="ios-card-body text-white p-3">
                                                <div class="fs-4 fw-bold">{{ $user->peminjamans->count() }}</div>
                                                <small class="opacity-75">Total</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-orange), #E6930A);">
                                            <div class="ios-card-body text-white p-3">
                                                <div class="fs-4 fw-bold">{{ $user->peminjamans->where('status', 'menunggu_verifikasi')->count() }}</div>
                                                <small class="opacity-75">Menunggu</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-teal), #32A8E6);">
                                            <div class="ios-card-body text-white p-3">
                                                <div class="fs-4 fw-bold">{{ $user->peminjamans->where('status', 'dipinjam')->count() }}</div>
                                                <small class="opacity-75">Dipinjam</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="ios-card" style="background: linear-gradient(135deg, var(--ios-green), #2FB344);">
                                            <div class="ios-card-body text-white p-3">
                                                <div class="fs-4 fw-bold">{{ $user->peminjamans->where('status', 'dikembalikan')->count() }}</div>
                                                <small class="opacity-75">Dikembalikan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Warning for Self Edit -->
                        @if($user->id == auth()->id())
                        <div class="ios-card mb-4" style="background: rgba(255, 149, 0, 0.05); border: 1px solid rgba(255, 149, 0, 0.2);">
                            <div class="ios-card-body">
                                <h6 class="fw-medium mb-3"><i class="bi bi-exclamation-triangle me-2" style="color: var(--ios-orange);"></i>Perhatian</h6>
                                <ul class="mb-0" style="color: var(--ios-secondary-label);">
                                    <li>Anda sedang mengedit akun Anda sendiri</li>
                                    <li>Role tidak dapat diubah untuk keamanan</li>
                                    <li>Pastikan email tetap valid untuk akses login</li>
                                    <li>Jika mengubah password, Anda akan logout otomatis</li>
                                </ul>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.show', $user) }}" class="ios-btn ios-btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="ios-btn ios-btn-secondary me-2">
                                    <i class="bi bi-list me-2"></i>Daftar Pengguna
                                </a>
                                <button type="submit" class="ios-btn ios-btn-success">
                                    <i class="bi bi-check2 me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePasswordFields() {
    const checkbox = document.getElementById('change_password');
    const fields = document.getElementById('password-fields');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    
    if (checkbox.checked) {
        fields.style.display = 'block';
        passwordField.required = true;
        confirmField.required = true;
    } else {
        fields.style.display = 'none';
        passwordField.required = false;
        confirmField.required = false;
        passwordField.value = '';
        confirmField.value = '';
        
        // Reset auto generate
        document.getElementById('auto_generate').checked = false;
        passwordField.readOnly = false;
        confirmField.readOnly = false;
    }
}

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
    
    // Listen for role changes (only if role select is not disabled)
    if (!roleSelect.disabled) {
        roleSelect.addEventListener('change', toggleKelasField);
    }
});
</script>
@endpush
@endsection
