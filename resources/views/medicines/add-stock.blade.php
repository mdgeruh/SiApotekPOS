@extends('layouts.app')

@section('title', 'Tambah Stok Obat')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Stok Obat</h1>
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Obat
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Stok</h6>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('medicines.add-stock.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="medicine_id">Pilih Obat <span class="text-danger">*</span></label>
                            <select name="medicine_id" id="medicine_id" class="form-control @error('medicine_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Obat --</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}"
                                        {{ old('medicine_id', $medicine ? $medicine->id : '') == $med->id ? 'selected' : '' }}>
                                        {{ $med->code }} - {{ $med->name }}
                                        @if($med->category)
                                            ({{ $med->category->name }})
                                        @endif
                                        - Stok: {{ $med->stock }}
                                        @if($med->unit)
                                            {{ $med->unit->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('medicine_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock_to_add">Jumlah Stok yang Ditambahkan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number"
                                       name="stock_to_add"
                                       id="stock_to_add"
                                       class="form-control @error('stock_to_add') is-invalid @enderror"
                                       step="0.01"
                                       min="0.01"
                                       value="{{ old('stock_to_add') }}"
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="unit_display">
                                        @if($medicine && $medicine->unit)
                                            {{ $medicine->unit->name }}
                                        @else
                                            Satuan
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @error('stock_to_add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="expired_date">Tanggal Kadaluarsa Baru</label>
                            <input type="date"
                                   name="expired_date"
                                   id="expired_date"
                                   class="form-control @error('expired_date') is-invalid @enderror"
                                   value="{{ old('expired_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah tanggal kadaluarsa</small>
                            @error('expired_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="purchase_price">Harga Beli Baru</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number"
                                       name="purchase_price"
                                       id="purchase_price"
                                       class="form-control @error('purchase_price') is-invalid @enderror"
                                       step="100"
                                       min="0"
                                       value="{{ old('purchase_price') }}">
                            </div>
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah harga beli</small>
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock_note">Catatan Stok</label>
                            <textarea name="stock_note"
                                      id="stock_note"
                                      class="form-control @error('stock_note') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Catatan tambahan tentang penambahan stok ini...">{{ old('stock_note') }}</textarea>
                            @error('stock_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Stok
                            </button>
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Medicine Info -->
            @if($medicine)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Obat</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Kode:</strong> {{ $medicine->code }}<br>
                        <strong>Nama:</strong> {{ $medicine->name }}<br>
                        <strong>Kategori:</strong> {{ $medicine->category ? $medicine->category->name : '-' }}<br>
                        <strong>Brand:</strong> {{ $medicine->brand ? $medicine->brand->name : '-' }}<br>
                        <strong>Manufacturer:</strong> {{ $medicine->manufacturer ? $medicine->manufacturer->name : '-' }}
                    </div>

                    <div class="mb-3">
                        <strong>Stok Saat Ini:</strong>
                        <span class="badge badge-{{ $medicine->stock > 0 ? 'success' : 'danger' }}">
                            {{ $medicine->stock }} @if($medicine->unit) {{ $medicine->unit->name }} @endif
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Harga Beli:</strong> Rp {{ number_format($medicine->purchase_price, 0, ',', '.') }}<br>
                        <strong>Harga Jual:</strong> Rp {{ number_format($medicine->selling_price, 0, ',', '.') }}<br>
                        <strong>Kadaluarsa:</strong> {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}
                    </div>

                    <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
            @endif

            <!-- Stock Update History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Update Stok</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Fitur ini akan menampilkan riwayat perubahan stok obat.
                        <br><br>
                        <em>Fitur ini sedang dalam pengembangan.</em>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const medicineSelect = document.getElementById('medicine_id');
    const unitDisplay = document.getElementById('unit_display');

    medicineSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const medicineId = this.value;

        if (medicineId) {
            // Fetch medicine details to update unit display
            fetch(`/medicines/${medicineId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.unit) {
                    unitDisplay.textContent = data.unit.name;
                } else {
                    unitDisplay.textContent = 'Satuan';
                }
            })
            .catch(error => {
                console.error('Error fetching medicine details:', error);
                unitDisplay.textContent = 'Satuan';
            });
        } else {
            unitDisplay.textContent = 'Satuan';
        }
    });
});
</script>
@endpush
@endsection
