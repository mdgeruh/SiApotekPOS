@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle me-2"></i>
            Tambah Kategori Baru
        </h1>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags me-2"></i>
                        Form Tambah Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                        @csrf

                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Antibiotik, Analgesik, Vitamin"
                                   autocomplete="off">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Gunakan nama yang jelas dan mudah diingat
                            </small>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Deskripsi
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Deskripsi singkat tentang kategori ini (opsional)">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Deskripsi membantu mengidentifikasi jenis obat dalam kategori ini
                            </small>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>
                                Status Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                </option>
                                <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle me-1"></i> Tidak Aktif
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Status aktif memungkinkan kategori digunakan untuk obat baru
                            </small>
                            @error('status')
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
                                    Simpan Kategori
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
                                <li><i class="fas fa-check text-success me-2"></i>Nama kategori harus unik</li>
                                <li><i class="fas fa-check text-success me-2"></i>Gunakan nama yang deskriptif</li>
                                <li><i class="fas fa-check text-success me-2"></i>Deskripsi membantu identifikasi</li>
                                <li><i class="fas fa-check text-success me-2"></i>Pilih status yang sesuai</li>
                                <li><i class="fas fa-check text-success me-2"></i>Kategori dapat diedit nanti</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Perhatian:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Nama kategori tidak boleh kosong</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Status kategori harus dipilih</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Kategori yang sudah digunakan tidak bisa dihapus</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pastikan nama tidak duplikat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
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
    const form = document.getElementById('categoryForm');
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
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Kategori';
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
                errorMessage = 'Nama kategori minimal 2 karakter';
            } else if (value.length > 50) {
                isValid = false;
                errorMessage = 'Nama kategori maksimal 50 karakter';
            }
            break;
        case 'description':
            if (value && value.length > 200) {
                isValid = false;
                errorMessage = 'Deskripsi maksimal 200 karakter';
            }
            break;
        case 'status':
            if (!value) {
                isValid = false;
                errorMessage = 'Status kategori harus dipilih';
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
        document.getElementById('categoryForm').reset();
        document.getElementById('confirmData').checked = false;

        // Remove all validation classes
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });

        // Remove error messages
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(msg => msg.remove());

        // Show notification
        if (window.showNotification) {
            window.showNotification('Form telah direset', 'info');
        }
    }
}
</script>
@endpush
