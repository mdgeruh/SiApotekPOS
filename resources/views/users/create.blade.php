@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
    <!-- Alert Messages -->
    @include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle me-2"></i>
            Tambah User Baru
        </h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Create User Form -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Form Tambah User
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="createUserForm">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required autocomplete="off">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Masukkan nama lengkap user
                                </small>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    <i class="fas fa-at me-1"></i>
                                    Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                       id="username" name="username" value="{{ old('username') }}" required autocomplete="off">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Username harus unik dan tidak boleh sama dengan user lain
                                </small>
                                @error('username')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autocomplete="off">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Email harus valid dan unik
                                </small>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Nomor Telepon
                                </label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" autocomplete="off">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Nomor telepon untuk kontak (opsional)
                                </small>
                                @error('phone')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>
                                    Tanggal Lahir
                                </label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tanggal lahir user (opsional)
                                </small>
                                @error('birth_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">
                                    <i class="fas fa-venus-mars me-1"></i>
                                    Jenis Kelamin
                                </label>
                                <select class="form-control @error('gender') is-invalid @enderror"
                                        id="gender" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Jenis kelamin user (opsional)
                                </small>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Alamat
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" placeholder="Alamat lengkap user">{{ old('address') }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Alamat lengkap user (opsional)
                            </small>
                            @error('address')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="role_id" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>
                                    Role <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Role menentukan hak akses user
                                </small>
                                @error('role_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Status aktif memungkinkan user untuk login
                                </small>
                                @error('status')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password minimal 8 karakter
                                </small>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Konfirmasi Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Masukkan ulang password untuk konfirmasi
                                </small>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="profile_photo" class="form-label">
                                <i class="fas fa-camera me-1"></i>
                                Foto Profil
                            </label>

                            <!-- New Photo Preview -->
                            <div id="newPhotoPreview" class="mb-3 text-center" style="display: none;">
                                <div class="border rounded p-3 bg-light">
                                    <img id="previewImage" src="" alt="Preview Foto" class="img-thumbnail rounded" style="max-width: 200px; height: auto;">
                                    <p class="text-muted small mt-2 mb-0">Preview foto yang dipilih</p>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                       id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearPhotoPreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                            </small>
                            @error('profile_photo')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmData" required>
                                <label class="form-check-label" for="confirmData">
                                    Saya mengkonfirmasi bahwa data yang diisi sudah benar
                                </label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-question-circle me-2"></i>
                        Panduan Pengisian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-info">Tips Pengisian:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Semua field bertanda * wajib diisi</li>
                                <li><i class="fas fa-check text-success me-2"></i>Username harus unik dan tidak boleh sama</li>
                                <li><i class="fas fa-check text-success me-2"></i>Email harus valid dan unik</li>
                                <li><i class="fas fa-check text-success me-2"></i>Password minimal 8 karakter</li>
                                <li><i class="fas fa-check text-success me-2"></i>Foto profil akan otomatis di-resize</li>
                                <li><i class="fas fa-check text-success me-2"></i>Role menentukan hak akses user</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Perhatian:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Setelah user dibuat, mereka dapat login</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Berikan informasi login kepada user</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Password tidak dapat dilihat setelah disimpan</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Role dapat diubah nanti jika diperlukan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmCheckbox = document.getElementById('confirmData');

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!confirmCheckbox.checked) {
            e.preventDefault();
            if (window.showNotification) {
                window.showNotification('Silakan konfirmasi data terlebih dahulu', 'warning');
            } else {
                alert('Silakan konfirmasi data terlebih dahulu');
            }
            return false;
        }

        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password !== passwordConfirmation) {
            e.preventDefault();
            if (window.showNotification) {
                window.showNotification('Konfirmasi password tidak cocok!', 'error');
            } else {
                alert('Konfirmasi password tidak cocok!');
            }
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            if (window.showNotification) {
                window.showNotification('Password minimal 8 karakter!', 'error');
            } else {
                alert('Password minimal 8 karakter!');
            }
            return false;
        }

        // Show loading state
        setFormLoading(true);
        setButtonLoading(true);

        // Form will submit naturally
        // Loading state will be cleared on page reload/redirect
    });

    // Real-time validation feedback
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Auto-focus on name field
    document.getElementById('name').focus();
});

// Loading state management
function setFormLoading(loading) {
    const form = document.getElementById('createUserForm');
    if (loading) {
        form.classList.add('form-loading');
    } else {
        form.classList.remove('form-loading');
    }
}

function setButtonLoading(loading) {
    const submitBtn = document.getElementById('submitBtn');
    if (loading) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
        submitBtn.disabled = true;
    } else {
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan User';
        submitBtn.disabled = false;
    }
}

// Field validation
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';

    // Remove existing validation classes
    field.classList.remove('is-valid', 'is-invalid');

    // Validation rules
    switch (fieldName) {
        case 'name':
            if (value.length < 2) {
                isValid = false;
                errorMessage = 'Nama minimal 2 karakter';
            } else if (value.length > 100) {
                isValid = false;
                errorMessage = 'Nama maksimal 100 karakter';
            }
            break;
        case 'username':
            if (value.length < 3) {
                isValid = false;
                errorMessage = 'Username minimal 3 karakter';
            } else if (value.length > 50) {
                isValid = false;
                errorMessage = 'Username maksimal 50 karakter';
            }
            break;
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
            break;
        case 'phone':
            if (value && value.length < 10) {
                isValid = false;
                errorMessage = 'Nomor telepon minimal 10 digit';
            }
            break;
        case 'password':
            if (value.length < 8) {
                isValid = false;
                errorMessage = 'Password minimal 8 karakter';
            }
            break;
        case 'password_confirmation':
            const password = document.getElementById('password').value;
            if (value !== password) {
                isValid = false;
                errorMessage = 'Konfirmasi password tidak cocok';
            }
            break;
        case 'role_id':
            if (!value) {
                isValid = false;
                errorMessage = 'Role harus dipilih';
            }
            break;
        case 'status':
            if (!value) {
                isValid = false;
                errorMessage = 'Status harus dipilih';
            }
            break;
    }

    // Apply validation result
    if (isValid) {
        field.classList.add('is-valid');
        // Remove error message if exists
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    } else {
        field.classList.add('is-invalid');
        // Add error message if not exists
        if (!field.parentNode.querySelector('.invalid-feedback')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${errorMessage}`;
            field.parentNode.appendChild(errorDiv);
        }
    }
}

// Reset form
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua data yang telah diisi?')) {
        document.getElementById('createUserForm').reset();
        document.getElementById('confirmData').checked = false;

        // Remove all validation classes
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });

        // Remove error messages
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(msg => msg.remove());

        // Clear photo preview
        clearPhotoPreview();

        // Show notification
        if (window.showNotification) {
            window.showNotification('Form telah direset', 'info');
        }
    }
}

// Function untuk preview gambar yang dipilih
function previewImage(input) {
    const preview = document.getElementById('newPhotoPreview');
    const previewImg = document.getElementById('previewImage');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

// Function untuk clear photo preview
function clearPhotoPreview() {
    document.getElementById('profile_photo').value = '';
    document.getElementById('newPhotoPreview').style.display = 'none';
}
</script>
@endpush
@endsection
