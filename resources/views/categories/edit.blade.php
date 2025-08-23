@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>
            Edit Kategori: {{ $category->name }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info shadow-sm">
                <i class="fas fa-eye me-1"></i>
                Lihat Detail
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags me-2"></i>
                        Form Edit Kategori
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryEditForm">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $category->name) }}"
                                   placeholder="Masukkan nama kategori" required autocomplete="off">
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
                                      placeholder="Masukkan deskripsi kategori">{{ old('description', $category->description) }}</textarea>
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
                                Status Kategori
                            </label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('status', $category->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                </option>
                                <option value="inactive" {{ old('status', $category->status ?? 'active') == 'inactive' ? 'selected' : '' }}>
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
                                <input class="form-check-input" type="checkbox" id="confirmUpdate" required>
                                <label class="form-check-label" for="confirmUpdate">
                                    Saya mengkonfirmasi bahwa perubahan data sudah benar
                                </label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetToOriginal()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset ke Data Asli
                                </button>
                                <button type="submit" class="btn btn-primary" id="updateBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Update Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Data Summary -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Data Saat Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">Nama Kategori:</td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Deskripsi:</td>
                                    <td>{{ $category->description ?: 'Tidak ada deskripsi' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if(($category->status ?? 'active') == 'active')
                                            <span class="status-badge status-active">Aktif</span>
                                        @else
                                            <span class="status-badge status-inactive">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">Jumlah Obat:</td>
                                    <td>
                                        @php
                                            $medicineCount = $category->medicines_count ?? $category->medicines->count();
                                        @endphp
                                        @if($medicineCount > 0)
                                            <span class="status-badge status-active">{{ $medicineCount }} obat</span>
                                        @else
                                            <span class="status-badge status-inactive">0 obat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dibuat:</td>
                                    <td>{{ \App\Helpers\DateHelper::formatDDMMYYYY($category->created_at, true) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Terakhir Update:</td>
                                    <td>{{ \App\Helpers\DateHelper::formatDDMMYYYY($category->updated_at, true) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Perhatian Saat Edit
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-warning">Yang Boleh Diubah:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Nama kategori</li>
                                <li><i class="fas fa-check text-success me-2"></i>Deskripsi kategori</li>
                                <li><i class="fas fa-check text-success me-2"></i>Status kategori</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger">Yang Tidak Boleh Diubah:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-times text-danger me-2"></i>ID kategori</li>
                                <li><i class="fas fa-times text-danger me-2"></i>Tanggal pembuatan</li>
                                <li><i class="fas fa-times text-danger me-2"></i>Relasi dengan obat</li>
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
// Store original form data
const originalFormData = {
    name: '{{ $category->name }}',
    description: '{{ $category->description }}',
    status: '{{ $category->status ?? 'active' }}'
};

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryEditForm');
    const updateBtn = document.getElementById('updateBtn');
    const confirmCheckbox = document.getElementById('confirmUpdate');

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!confirmCheckbox.checked) {
            e.preventDefault();
            if (window.showNotification) {
                window.showNotification('Silakan konfirmasi perubahan terlebih dahulu', 'warning');
            } else {
                alert('Silakan konfirmasi perubahan terlebih dahulu');
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
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Track changes
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            trackChanges();
        });
    });

    // Auto-focus on name field
    document.getElementById('name').focus();
});

// Loading state management
function setFormLoading(loading) {
    const form = document.getElementById('categoryEditForm');
    if (loading) {
        form.classList.add('form-loading');
    } else {
        form.classList.remove('form-loading');
    }
}

function setButtonLoading(loading) {
    const updateBtn = document.getElementById('updateBtn');
    if (loading) {
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengupdate...';
        updateBtn.disabled = true;
    } else {
        updateBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Kategori';
        updateBtn.disabled = false;
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

// Reset to original data
function resetToOriginal() {
    if (confirm('Apakah Anda yakin ingin mengembalikan semua data ke nilai asli? Perubahan yang belum disimpan akan hilang.')) {
        // Reset form to original values
        Object.keys(originalFormData).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = originalFormData[key];
                field.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Remove error messages
        const errorMessages = document.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(msg => msg.remove());

        // Uncheck confirmation
        document.getElementById('confirmUpdate').checked = false;

        // Show notification
        if (window.showNotification) {
            window.showNotification('Form telah dikembalikan ke data asli', 'info');
        }
    }
}

// Track changes
function trackChanges() {
    let hasChanges = false;
    const inputs = document.querySelectorAll('input, textarea');

    inputs.forEach(input => {
        const fieldName = input.name;
        if (originalFormData[fieldName] !== input.value) {
            hasChanges = true;
        }
    });

    // Update button state
    const updateBtn = document.getElementById('updateBtn');
    if (hasChanges) {
        updateBtn.classList.add('btn-warning');
        updateBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Ada Perubahan';
    } else {
        updateBtn.classList.remove('btn-warning');
        updateBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Kategori';
    }
}
</script>
@endpush
