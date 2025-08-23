<div class="medicine-search-container position-relative">
    <div class="input-group">
        <input type="text"
               class="form-control"
               placeholder="Cari obat berdasarkan nama, kode, atau deskripsi..."
               wire:model.debounce.300ms="search"
               autocomplete="off">
        <button class="btn btn-outline-secondary" type="button" wire:click="clearSearch">
            <i class="fas fa-times"></i>
        </button>
    </div>

    @if($showResults && count($medicines) > 0)
        <div class="search-results position-absolute w-100 bg-white border rounded shadow-sm" style="z-index: 1000; max-height: 300px; overflow-y: auto;">
            @foreach($medicines as $medicine)
                <div class="search-result-item p-2 border-bottom cursor-pointer hover-bg-light"
                     wire:click="selectMedicine({{ $medicine->id }})"
                     style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $medicine->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $medicine->code }}</small>
                            @if($medicine->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($medicine->description, 50) }}</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                            <br>
                            <small class="text-muted">Stok: {{ $medicine->stock }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($showResults && count($medicines) === 0 && strlen($search) >= 2)
        <div class="search-results position-absolute w-100 bg-white border rounded shadow-sm p-3 text-center text-muted" style="z-index: 1000;">
            <i class="fas fa-search me-2"></i>
            Tidak ada obat yang ditemukan untuk "{{ $search }}"
        </div>
    @endif

    @if($selectedMedicine)
        <div class="selected-medicine mt-2 p-2 bg-light rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $selectedMedicine->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $selectedMedicine->code }}</small>
                </div>
                <div class="text-end">
                    <span class="badge bg-success">Rp {{ number_format($selectedMedicine->price, 0, ',', '.') }}</span>
                    <br>
                    <small class="text-muted">Stok: {{ $selectedMedicine->stock }}</small>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.medicine-search-container {
    position: relative;
}

.search-results {
    top: 100%;
    left: 0;
    right: 0;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.hover-bg-light:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
