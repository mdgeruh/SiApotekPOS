@extends('layouts.app')

@section('title', 'Update Stok Obat')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes me-2"></i>
            Update Stok Obat
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
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-edit me-2"></i>
                        Form Update Stok Obat
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('medicines.stock-update', $medicine->id) }}" method="POST" id="stockUpdateForm">
                        @csrf
                        @method('PATCH')

                        <!-- Medicine Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-pills me-1"></i>
                                    Nama Obat
                                </label>
                                <div class="form-control-plaintext">
                                    <strong class="text-primary">{{ $medicine->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $medicine->code }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-boxes me-1"></i>
                                    Stok Saat Ini
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-info fs-5">{{ $medicine->stock }}</span>
                                    @if($medicine->unit)
                                        <small class="text-muted ms-2">{{ $medicine->unit->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stock Update Information -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="stock_change_type" class="form-label">
                                    <i class="fas fa-plus-minus me-1"></i>
                                    Jenis Perubahan <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('stock_change_type') is-invalid @enderror"
                                        id="stock_change_type" name="stock_change_type" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="add" {{ old('stock_change_type') == 'add' ? 'selected' : '' }}>
                                        Tambah Stok (+)
                                    </option>
                                    <option value="subtract" {{ old('stock_change_type') == 'subtract' ? 'selected' : '' }}>
                                        Kurang Stok (-)
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih jenis perubahan stok
                                </small>
                                @error('stock_change_type')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="stock_change" class="form-label">
                                    <i class="fas fa-calculator me-1"></i>
                                    Jumlah Perubahan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('stock_change') is-invalid @enderror"
                                           id="stock_change" name="stock_change" value="{{ old('stock_change') }}"
                                           step="0.01" min="0.01" placeholder="0.00" required>
                                    @if($medicine->unit)
                                        <span class="input-group-text">{{ $medicine->unit->abbreviation ?? $medicine->unit->name }}</span>
                                    @endif
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Masukkan jumlah perubahan stok
                                </small>
                                @error('stock_change')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="new_stock" class="form-label">
                                    <i class="fas fa-eye me-1"></i>
                                    Stok Setelah Perubahan
                                </label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-success fs-5" id="newStockPreview">-</span>
                                    @if($medicine->unit)
                                        <small class="text-muted ms-2">{{ $medicine->unit->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="expired_date" class="form-label">
                                    <i class="fas fa-calendar-times me-1"></i>
                                    Tanggal Kadaluarsa Baru
                                </label>
                                <input type="date" class="form-control @error('expired_date') is-invalid @enderror"
                                       id="expired_date" name="expired_date"
                                       value="{{ old('expired_date', $medicine->expired_date ? $medicine->expired_date->format('Y-m-d') : '') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Kosongkan jika tidak ada perubahan tanggal kadaluarsa
                                </small>
                                @error('expired_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="purchase_price" class="form-label">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    Harga Beli Baru
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                           id="purchase_price" name="purchase_price"
                                           value="{{ old('purchase_price', $medicine->purchase_price) }}"
                                           step="100" min="0" placeholder="Harga beli baru">
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Kosongkan jika tidak ada perubahan harga beli
                                </small>
                                @error('purchase_price')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="stock_note" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Catatan Perubahan Stok
                            </label>
                            <textarea class="form-control @error('stock_note') is-invalid @enderror"
                                      id="stock_note" name="stock_note" rows="3"
                                      placeholder="Catatan perubahan stok (misal: restock dari supplier, penjualan, dll)">{{ old('stock_note') }}</textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Catatan untuk dokumentasi perubahan stok (opsional)
                            </small>
                            @error('stock_note')
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
                                    Saya mengkonfirmasi bahwa data perubahan stok sudah benar
                                </label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-warning" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Update Stok
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
                        Panduan Update Stok
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-info">Tips Update Stok:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Pilih jenis perubahan (tambah/kurang)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Masukkan jumlah perubahan dengan teliti</li>
                                <li><i class="fas fa-check text-success me-2"></i>Update tanggal kadaluarsa jika ada batch baru</li>
                                <li><i class="fas fa-check text-success me-2"></i>Update harga beli jika ada perubahan harga</li>
                                <li><i class="fas fa-check text-success me-2"></i>Beri catatan untuk dokumentasi</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Perhatian:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Perubahan stok tidak dapat dibatalkan</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Pastikan jumlah perubahan sudah benar</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Tanggal kadaluarsa minimal besok</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Harga beli tidak boleh kosong jika diisi</li>
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
    const form = document.getElementById('stockUpdateForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmCheckbox = document.getElementById('confirmData');
    const stockChangeType = document.getElementById('stock_change_type');
    const stockChange = document.getElementById('stock_change');
    const newStockPreview = document.getElementById('newStockPreview');
    const currentStock = {{ $medicine->stock }};

    // Update preview when values change
    function updateStockPreview() {
        const changeType = stockChangeType.value;
        const changeAmount = parseFloat(stockChange.value) || 0;

        if (changeType && changeAmount > 0) {
            let newStock;
            if (changeType === 'add') {
                newStock = currentStock + changeAmount;
                newStockPreview.textContent = newStock;
                newStockPreview.className = 'badge bg-success fs-5';
            } else if (changeType === 'subtract') {
                newStock = Math.max(0, currentStock - changeAmount);
                newStockPreview.textContent = newStock;
                newStockPreview.className = newStock <= 0 ? 'badge bg-danger fs-5' : 'badge bg-warning fs-5';
            }
        } else {
            newStockPreview.textContent = '-';
            newStockPreview.className = 'badge bg-secondary fs-5';
        }
    }

    // Event listeners for real-time preview
    stockChangeType.addEventListener('change', updateStockPreview);
    stockChange.addEventListener('input', updateStockPreview);

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

    // Auto-focus on stock change type field
    stockChangeType.focus();
});

// Loading state management
function setFormLoading(loading) {
    const form = document.getElementById('stockUpdateForm');
    if (loading) {
        form.classList.add('form-loading');
    } else {
        form.classList.remove('form-loading');
    }
}

function setButtonLoading(loading) {
    const submitBtn = document.getElementById('submitBtn');
    if (loading) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengupdate...';
        submitBtn.disabled = true;
    } else {
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Stok';
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
        case 'stock_change_type':
            if (value === '') {
                isValid = false;
                errorMessage = 'Jenis perubahan harus dipilih';
            }
            break;
        case 'stock_change':
            if (value === '' || parseFloat(value) <= 0) {
                isValid = false;
                errorMessage = 'Jumlah perubahan harus lebih dari 0';
            }
            break;
        case 'expired_date':
            if (value !== '') {
                const selectedDate = new Date(value);
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                if (selectedDate < tomorrow) {
                    isValid = false;
                    errorMessage = 'Tanggal kadaluarsa minimal besok';
                }
            }
            break;
        case 'purchase_price':
            if (value !== '' && parseFloat(value) < 0) {
                isValid = false;
                errorMessage = 'Harga beli tidak boleh negatif';
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
        document.getElementById('stockUpdateForm').reset();
        document.getElementById('confirmData').checked = false;
        document.getElementById('newStockPreview').textContent = '-';
        document.getElementById('newStockPreview').className = 'badge bg-secondary fs-5';

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
</script>
@endpush
@endsection
