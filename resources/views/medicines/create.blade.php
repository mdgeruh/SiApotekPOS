@extends('layouts.app')

@section('title', 'Tambah Obat Baru')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle me-2"></i>
            Tambah Obat Baru
        </h1>
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary shadow-sm">
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
                        <i class="fas fa-pills me-2"></i>
                        Form Tambah Obat
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('medicines.store') }}" method="POST" id="medicineForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fas fa-pills me-1"></i>
                                    Nama Obat <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Masukkan nama obat" required autocomplete="off">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Gunakan nama obat yang lengkap dan jelas
                                </small>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="code" class="form-label">
                                    <i class="fas fa-barcode me-1"></i>
                                    Kode Obat <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ $code }}"
                                           placeholder="Kode obat" required readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                        <i class="fas fa-magic me-1"></i> Generate
                                    </button>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Kode akan digenerate otomatis
                                </small>
                                @error('code')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category and Brand -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">
                                    <i class="fas fa-tags me-1"></i>
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih kategori yang sesuai dengan jenis obat
                                </small>
                                @error('category_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand_id" class="form-label">
                                    <i class="fas fa-trademark me-1"></i>
                                    Merk
                                </label>
                                <select class="form-control @error('brand_id') is-invalid @enderror"
                                        id="brand_id" name="brand_id">
                                    <option value="">Pilih Merk (Opsional)</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Merk obat (opsional)
                                </small>
                                @error('brand_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="purchase_price" class="form-label">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    Harga Beli <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                           id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}"
                                           step="100" min="0" max="999999999" placeholder="0" required>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Harga pembelian dari supplier
                                </small>
                                @error('purchase_price')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selling_price" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    Harga Jual <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                                           id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
                                           step="100" min="0" max="999999999" placeholder="0" required>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Harga yang dijual ke pelanggan
                                </small>
                                @error('selling_price')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Information -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="stock" class="form-label">
                                    <i class="fas fa-boxes me-1"></i>
                                    Stok Awal <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                       id="stock" name="stock" value="{{ old('stock', 0) }}"
                                       min="0" placeholder="0" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Jumlah stok awal yang tersedia
                                </small>
                                @error('stock')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="min_stock" class="form-label">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Stok Minimum <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                       id="min_stock" name="min_stock" value="{{ old('min_stock', 10) }}"
                                       min="0" placeholder="10" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Akan muncul peringatan jika stok di bawah ini
                                </small>
                                @error('min_stock')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="unit_id" class="form-label">
                                    <i class="fas fa-ruler me-1"></i>
                                    Satuan <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('unit_id') is-invalid @enderror"
                                        id="unit_id" name="unit_id" required>
                                    <option value="">Pilih Satuan</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }} @if($unit->abbreviation) ({{ $unit->abbreviation }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih satuan pengukuran obat
                                </small>
                                @error('unit_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="expired_date" class="form-label">
                                    <i class="fas fa-calendar-times me-1"></i>
                                    Tanggal Kadaluarsa <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('expired_date') is-invalid @enderror"
                                       id="expired_date" name="expired_date" value="{{ old('expired_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tanggal kadaluarsa minimal besok
                                </small>
                                @error('expired_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="manufacturer_id" class="form-label">
                                    <i class="fas fa-industry me-1"></i>
                                    Produsen
                                </label>
                                <select class="form-control @error('manufacturer_id') is-invalid @enderror"
                                        id="manufacturer_id" name="manufacturer_id">
                                    <option value="">Pilih Produsen (Opsional)</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}"
                                                {{ old('manufacturer_id') == $manufacturer->id ? 'selected' : '' }}>
                                            {{ $manufacturer->name }}
                                            @if($manufacturer->country) - {{ $manufacturer->country }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih perusahaan produsen obat (opsional)
                                </small>
                                @error('manufacturer_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Deskripsi
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Deskripsi obat (opsional)">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Deskripsi singkat tentang obat (opsional)
                            </small>
                            @error('description')
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
                                    Simpan Obat
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
                                <li><i class="fas fa-check text-success me-2"></i>Kode obat akan digenerate otomatis</li>
                                <li><i class="fas fa-check text-success me-2"></i>Harga beli adalah harga dari supplier</li>
                                <li><i class="fas fa-check text-success me-2"></i>Harga jual adalah harga ke pelanggan</li>
                                <li><i class="fas fa-check text-success me-2"></i>Stok minimum untuk peringatan</li>
                                <li><i class="fas fa-check text-success me-2"></i>Tanggal kadaluarsa minimal besok</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Perhatian:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Data obat yang sudah disimpan tidak dapat diubah dengan mudah</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pastikan harga jual lebih tinggi dari harga beli</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Stok awal harus sesuai dengan ketersediaan</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Periksa tanggal kadaluarsa dengan teliti</li>
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
    const form = document.getElementById('medicineForm');
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
    const form = document.getElementById('medicineForm');
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
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Obat';
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
                errorMessage = 'Nama obat minimal 2 karakter';
            } else if (value.length > 100) {
                isValid = false;
                errorMessage = 'Nama obat maksimal 100 karakter';
            }
            break;
        case 'purchase_price':
            if (value < 0) {
                isValid = false;
                errorMessage = 'Harga beli tidak boleh negatif';
            } else if (value > 999999999) {
                isValid = false;
                errorMessage = 'Jumlah maksimal adalah 999.999.999';
            }
            break;
        case 'selling_price':
            if (value < 0) {
                isValid = false;
                errorMessage = 'Harga jual tidak boleh negatif';
            } else if (value > 999999999) {
                isValid = false;
                errorMessage = 'Jumlah maksimal adalah 999.999.999';
            } else {
                // Check if selling price is higher than purchase price
                const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
                if (parseFloat(value) <= purchasePrice) {
                    isValid = false;
                    errorMessage = 'Harga jual harus lebih tinggi dari harga beli';
                }
            }
            break;
        case 'stock':
            if (value < 0) {
                isValid = false;
                errorMessage = 'Stok tidak boleh negatif';
            } else if (value > 999999) {
                isValid = false;
                errorMessage = 'Stok maksimal adalah 999.999';
            }
            break;
        case 'min_stock':
            if (value < 0) {
                isValid = false;
                errorMessage = 'Stok minimum tidak boleh negatif';
            } else if (value > 999999) {
                isValid = false;
                errorMessage = 'Stok minimum maksimal adalah 999.999';
            }
            break;
        case 'unit_id': // Changed from 'unit' to 'unit_id'
            if (value === '') { // Check if unit is selected
                isValid = false;
                errorMessage = 'Satuan harus dipilih';
            }
            break;
        case 'expired_date':
            const selectedDate = new Date(value);
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            if (selectedDate < tomorrow) {
                isValid = false;
                errorMessage = 'Tanggal kadaluarsa minimal besok';
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
        document.getElementById('medicineForm').reset();
        document.getElementById('confirmData').checked = false;

        // Remove all validation classes
        const inputs = document.querySelectorAll('input, textarea, select');
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

function generateCode() {
    // Generate code with format OBT + YYMM + random number (consistent with seeder)
    const now = new Date();
    const year = now.getFullYear().toString().slice(-2); // Get last 2 digits of year
    const month = (now.getMonth() + 1).toString().padStart(2, '0'); // Get month with leading zero
    const timestamp = year + month;
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const code = 'OBT' + timestamp + random;
    document.getElementById('code').value = code;
}

// Auto-calculate selling price based on purchase price (optional)
document.getElementById('purchase_price').addEventListener('input', function() {
    const purchasePrice = parseFloat(this.value) || 0;
    const sellingPrice = purchasePrice * 1.3; // 30% markup
    document.getElementById('selling_price').value = Math.round(sellingPrice);
});
</script>
@endpush
@endsection
