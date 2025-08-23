<div class="quick-search-container">
    <div class="input-group">
        <span class="input-group-text bg-primary text-white">
            <i class="fas fa-search"></i>
        </span>
        <input type="text"
               class="form-control"
               placeholder="Cari obat cepat..."
               wire:model.debounce.300ms="searchQuery"
               wire:keydown.enter="performSearch"
               autocomplete="off">
        <button class="btn btn-outline-secondary" type="button" wire:click="clearSearch">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Quick Search Results -->
    @if($showResults && count($searchResults) > 0)
        <div class="search-results mt-2">
            <div class="list-group">
                @foreach($searchResults as $medicine)
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start"
                         wire:click="selectMedicine({{ $medicine->id }})"
                         style="cursor: pointer;">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $medicine->name }}</div>
                            <small class="text-muted">{{ $medicine->code }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                            <br>
                            <small class="text-muted">
                                @if($medicine->stock <= 0)
                                    <span class="text-danger">Stok Habis</span>
                                @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                                    <span class="text-warning">Stok: {{ $medicine->stock }}</span>
                                @else
                                    <span class="text-success">Stok: {{ $medicine->stock }}</span>
                                @endif
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($showResults && count($searchResults) === 0 && strlen($searchQuery) >= 2)
        <div class="alert alert-info mt-2 mb-0 py-2">
            <i class="fas fa-info-circle me-1"></i>
            Tidak ada obat yang ditemukan untuk "{{ $searchQuery }}"
        </div>
    @endif
</div>

<style>
.quick-search-container {
    position: relative;
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.list-group-item {
    border-left: 3px solid transparent;
}

.list-group-item:hover {
    border-left-color: #007bff;
}
</style>
