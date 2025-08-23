<div>
    <!-- Livewire Event Listeners -->
    <script>
        // Gunakan event listener yang kompatibel dengan semua versi Livewire
        document.addEventListener('livewire:load', function () {
            console.log('=== Livewire Load Event ===');

            // Pastikan Livewire tersedia
            if (typeof Livewire !== 'undefined') {
                console.log('Livewire tersedia');

                // Coba gunakan Livewire.on jika tersedia
                if (typeof Livewire.on === 'function') {
                    console.log('Livewire.on tersedia');
                    Livewire.on('searchCleared', () => {
                        console.log('Search cleared event triggered');
                        window.dispatchEvent(new CustomEvent('searchCleared'));
                    });
                } else {
                    console.log('Livewire.on tidak tersedia, menggunakan window events');
                }
            } else {
                console.log('Livewire tidak tersedia, menggunakan window events');
            }
        });

        // Event listeners untuk semua kasus
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM Content Loaded (Livewire Component) ===');

            // Listen for search cleared event
            window.addEventListener('searchCleared', function() {
                console.log('Search cleared');
                const url = new URL(window.location);
                url.searchParams.delete('search');
                window.history.replaceState({}, '', url);
            });

            // Debug: Log semua perubahan search
            const searchInputs = document.querySelectorAll('input[wire\\:model="search"]');
            searchInputs.forEach(input => {
                input.addEventListener('change', function() {
                    console.log('Search changed:', this.name || this.id, 'Value:', this.value);
                });
            });
        });

        // Function to delete medicine
        function deleteMedicine(url, medicineName) {
            console.log('=== deleteMedicine called ===');
            console.log('URL:', url);
            console.log('Medicine Name:', medicineName);

            // Check if showDeleteConfirmation function exists
            if (typeof showDeleteConfirmation === 'function') {
                console.log('showDeleteConfirmation function available');
                showDeleteConfirmation(url, medicineName, `Yakin ingin menghapus obat "${medicineName}"?\n\nData obat yang dihapus tidak dapat dikembalikan.`);
            } else {
                console.log('showDeleteConfirmation function not available, using fallback');
                // Fallback to basic confirm
                if (confirm(`Yakin ingin menghapus obat "${medicineName}"?\n\nData obat yang dihapus tidak dapat dikembalikan.`)) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add method override for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-{{ session('messageType', 'info') }} alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-search me-2"></i>Pencarian Obat
            </h6>
            <div class="d-flex gap-2">
                <a href="{{ route('medicines.log') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-archive me-1"></i> Log Obat
                </a>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Obat
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label fw-bold text-gray-600">Cari Obat</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" wire:model.debounce.300ms="search"
                                   placeholder="Nama, kode, atau deskripsi obat..." />
                            @if($search)
                                <button class="btn btn-outline-secondary" type="button" wire:click="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-1">
                            @if($search)
                                <button type="button" class="btn btn-secondary flex-fill" wire:click="clearSearch">
                                    <i class="fas fa-times me-1"></i> Clear Search
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Search Display -->
    @if($search)
        <div class="card shadow mb-3">
            <div class="card-body py-2">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <small class="text-muted me-2">Pencarian Aktif:</small>
                    <span class="badge bg-info">
                        <i class="fas fa-search me-1"></i>Pencarian: "{{ $search }}"
                        <button type="button" class="btn-close btn-close-white ms-2" wire:click="clearSearch" style="font-size: 0.5rem;"></button>
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- Medicines Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Daftar Obat</h6>
            <span class="badge bg-primary">{{ $medicines->count() }} Total Obat</span>
        </div>
        <div class="card-body">
            @if($medicines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-barcode me-1"></i>Kode
                                </th>
                                <th>
                                    <i class="fas fa-pills me-1"></i>Nama Obat
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-tags me-1"></i>Kategori
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-tag me-1"></i>Merk
                                </th>
                                <th class="text-center" style="width: 100px;">
                                    <i class="fas fa-boxes me-1"></i>Stok
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-shopping-cart me-1"></i>Harga Beli
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-tag me-1"></i>Harga Jual
                                </th>
                                <th class="text-center" style="width: 80px;">
                                    <i class="fas fa-ruler me-1"></i>Satuan
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-industry me-1"></i>Produsen
                                </th>
                                <th class="text-center" style="width: 120px;">
                                    <i class="fas fa-calendar-times me-1"></i>Kadaluarsa
                                </th>
                                <th class="text-center" style="width: 180px;">
                                    <i class="fas fa-cogs me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $medicine)
                                <tr class="{{ $medicine->stock <= ($medicine->min_stock ?? 10) ? 'table-warning' : '' }} {{ $medicine->stock <= 0 ? 'table-danger' : '' }} {{ $medicine->expired_date < now() ? 'table-danger' : '' }}">
                                    <td class="text-center">
                                        <span class="badge bg-info fs-6">{{ $medicine->code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong class="text-primary">{{ $medicine->name }}</strong>
                                            @if($medicine->description)
                                                <small class="text-muted">{{ Str::limit($medicine->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary fs-6">{{ $medicine->category->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark fs-6">{{ $medicine->brand->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            @if($medicine->stock <= 0)
                                                <span class="badge bg-danger fs-6">Habis</span>
                                            @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                                                <span class="badge bg-warning fs-6">{{ $medicine->stock }}</span>
                                            @else
                                                <span class="badge bg-success fs-6">{{ $medicine->stock }}</span>
                                            @endif
                                            <small class="text-muted mt-1">Min: {{ $medicine->min_stock ?? 10 }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->purchase_price)
                                            <span class="text-success fw-bold">Rp {{ number_format($medicine->purchase_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-primary fw-bold">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->unit)
                                            <span class="badge bg-info fs-6">{{ $medicine->unit->name }} @if($medicine->unit->abbreviation) ({{ $medicine->unit->abbreviation }}) @endif</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->manufacturer)
                                            <span class="badge bg-warning fs-6">{{ $medicine->manufacturer->name }}</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->expired_date < now())
                                            <span class="badge bg-danger fs-6">Kadaluarsa</span>
                                        @elseif($medicine->expired_date < now()->addDays(30))
                                            <span class="badge bg-warning fs-6">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                                        @else
                                            <span class="badge bg-success fs-6">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('medicines.show', $medicine) }}"
                                               class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('medicines.edit', $medicine) }}"
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('medicines.stock-update', $medicine->id) }}"
                                               class="btn btn-outline-success btn-sm" title="Update Stok">
                                                <i class="fas fa-boxes"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Hapus"
                                                    onclick="deleteMedicine('{{ route('medicines.destroy', $medicine->id) }}', '{{ $medicine->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-pills fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Tidak ada obat ditemukan</h4>
                    <p class="text-muted mb-4">Mulai dengan menambahkan obat baru atau ubah filter pencarian Anda.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i> Tambah Obat Pertama
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-lg" wire:click="clearSearch">
                            <i class="fas fa-times me-2"></i> Reset Search
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center p-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="text-primary mb-0">Memuat data...</h6>
            </div>
        </div>
    </div>

</div>

<style>
.badge-outline-secondary {
    color: #6c757d;
    border: 1px solid #6c757d;
    background-color: transparent;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.fs-6 {
    font-size: 0.875rem !important;
}

.fw-bold {
    font-weight: 700 !important;
}

.me-1 { margin-right: 0.25rem !important; }
.me-2 { margin-right: 0.5rem !important; }

/* Tombol aksi horizontal */
.d-flex.gap-1 .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

/* Responsive adjustments untuk tombol aksi */
@media (max-width: 768px) {
    .d-flex.gap-1 {
        flex-direction: column !important;
        gap: 0.25rem !important;
    }

    .d-flex.gap-1 .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
