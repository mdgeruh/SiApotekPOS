<div class="advanced-search-container">
    <!-- Search Input with Autocomplete -->
    <div class="search-input-container position-relative">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text"
                   class="form-control form-control-lg"
                   placeholder="Cari obat berdasarkan nama, kode, atau deskripsi..."
                   wire:model.debounce.300ms="searchQuery"
                   wire:keydown.escape="clearSearch"
                   autocomplete="off"
                   id="medicine-search-input">
            <button class="btn btn-outline-secondary" type="button" wire:click="clearSearch">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Search Suggestions -->
        @if($showSuggestions && count($searchSuggestions) > 0)
            <div class="search-suggestions position-absolute w-100 bg-white border rounded shadow-lg"
                 style="z-index: 1050; max-height: 400px; overflow-y: auto; top: 100%;">
                @foreach($searchSuggestions as $suggestion)
                    <div class="suggestion-item p-3 border-bottom cursor-pointer hover-bg-light"
                         wire:click="selectSuggestion({{ $suggestion->id }})"
                         style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="text-primary me-2">{{ $suggestion->name }}</strong>
                                    <span class="badge bg-info">{{ $suggestion->code }}</span>
                                </div>
                                @if($suggestion->description)
                                    <small class="text-muted d-block mb-1">{{ Str::limit($suggestion->description, 80) }}</small>
                                @endif
                                <div class="d-flex gap-2">
                                    <span class="badge bg-secondary">{{ $suggestion->category->name ?? 'N/A' }}</span>
                                    <span class="badge bg-light text-dark">{{ $suggestion->brand->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="text-end ms-3">
                                <div class="mb-1">
                                    <span class="text-primary fw-bold fs-6">Rp {{ number_format($suggestion->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="mb-1">
                                    @if($suggestion->stock <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($suggestion->stock <= ($suggestion->min_stock ?? 10))
                                        <span class="badge bg-warning">Stok: {{ $suggestion->stock }}</span>
                                    @else
                                        <span class="badge bg-success">Stok: {{ $suggestion->stock }}</span>
                                    @endif
                                </div>
                                <div>
                                    @if($suggestion->expired_date < now())
                                        <span class="badge bg-danger">Kadaluarsa</span>
                                    @elseif($suggestion->expired_date < now()->addDays(30))
                                        <span class="badge bg-warning">{{ \App\Helpers\DateHelper::formatDDMMYYYY($suggestion->expired_date) }}</span>
                                    @else
                                        <span class="badge bg-success">{{ \App\Helpers\DateHelper::formatDDMMYYYY($suggestion->expired_date) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- View All Results Link -->
                <div class="p-3 text-center border-top">
                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="viewAllResults">
                        <i class="fas fa-list me-1"></i>Lihat Semua Hasil ({{ $totalResults }})
                    </button>
                </div>
            </div>
        @endif

        <!-- No Results Message -->
        @if($showSuggestions && count($searchSuggestions) === 0 && strlen($searchQuery) >= 2)
            <div class="search-suggestions position-absolute w-100 bg-white border rounded shadow-lg p-4 text-center"
                 style="z-index: 1050; top: 100%;">
                <i class="fas fa-search fa-2x text-muted mb-3"></i>
                <h6 class="text-muted mb-2">Tidak ada obat yang ditemukan</h6>
                <p class="text-muted mb-0">Coba ubah kata kunci pencarian Anda</p>
            </div>
        @endif
    </div>

    <!-- Quick Filter Pills -->
    <!-- Quick filters telah dihapus untuk menyederhanakan interface -->

    <!-- Selected Medicine Display -->
    @if($selectedMedicine)
        <div class="selected-medicine-display mt-3 p-3 bg-light rounded border">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Obat Dipilih: <strong>{{ $selectedMedicine->name }}</strong>
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Kode: {{ $selectedMedicine->code }}</small><br>
                            <small class="text-muted">Kategori: {{ $selectedMedicine->category->name ?? 'N/A' }}</small><br>
                            <small class="text-muted">Merk: {{ $selectedMedicine->brand->name ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Harga: Rp {{ number_format($selectedMedicine->price, 0, ',', '.') }}</small><br>
                            <small class="text-muted">Stok: {{ $selectedMedicine->stock }}</small><br>
                            <small class="text-muted">Kadaluarsa: {{ \App\Helpers\DateHelper::formatDDMMYYYY($selectedMedicine->expired_date) }}</small>
                        </div>
                    </div>
                </div>
                <div class="ms-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="clearSelection">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.advanced-search-container {
    position: relative;
}

.search-input-container {
    position: relative;
}

.search-suggestions {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.suggestion-item:hover {
    background-color: #f8f9fa;
    transition: background-color 0.15s ease-in-out;
}

.suggestion-item:last-child {
    border-bottom: none !important;
}

.hover-bg-light:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}

/* Quick filters CSS telah dihapus */

.selected-medicine-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-suggestions {
        max-height: 300px;
    }

    .quick-filters .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>
