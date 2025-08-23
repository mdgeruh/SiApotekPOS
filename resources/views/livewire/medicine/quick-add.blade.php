<div>
    <!-- Button to open modal -->
    <button type="button" class="btn btn-success btn-sm" wire:click="openModal">
        <i class="fas fa-plus"></i> Quick Add Medicine
    </button>

    <!-- Modal -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Obat Baru</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" wire:model="name" placeholder="Masukkan nama obat">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Kode Obat <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                                   id="code" wire:model="code" placeholder="Kode obat">
                                            <button type="button" class="btn btn-outline-secondary" wire:click="generateCode">
                                                <i class="fas fa-magic"></i>
                                            </button>
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" wire:model="category_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Satuan <span class="text-danger">*</span></label>
                                        <select class="form-control @error('unit_id') is-invalid @enderror"
                                                id="unit_id" wire:model="unit_id" required>
                                            <option value="">Pilih Satuan</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} @if($unit->abbreviation) ({{ $unit->abbreviation }}) @endif</option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                   id="price" wire:model="price" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                               id="stock" wire:model="stock" min="0" placeholder="0">
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="manufacturer_id" class="form-label">Produsen</label>
                                        <select class="form-control @error('manufacturer_id') is-invalid @enderror"
                                                id="manufacturer_id" wire:model="manufacturer_id" required>
                                            <option value="">Pilih Produsen</option>
                                            @foreach($manufacturers as $manufacturer)
                                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('manufacturer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expired_date" class="form-label">Tanggal Kadaluarsa</label>
                                        <input type="date" class="form-control @error('expired_date') is-invalid @enderror"
                                               id="expired_date" wire:model="expired_date">
                                        @error('expired_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" wire:model="description" rows="3"
                                          placeholder="Deskripsi obat (opsional)"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-save"></i> Simpan
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Flash Messages -->
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<style>
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}
</style>
