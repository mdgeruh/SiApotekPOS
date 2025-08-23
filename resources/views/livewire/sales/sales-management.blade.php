<div class="container-fluid">
    <div class="row">
        <!-- Form Pencarian Obat -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pencarian Obat</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="searchQuery">Cari Obat (Nama/Kode)</label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="searchQuery"
                                   wire:model.debounce.300ms="searchQuery"
                                   wire:keyup="searchMedicines"
                                   placeholder="Ketik minimal 2 karakter...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Pencarian -->
                    @if($isSearching && !empty($searchResults))
                        <div class="search-results mt-3">
                            <h6 class="text-muted mb-2">Hasil Pencarian:</h6>
                            <div class="list-group">
                                @foreach($searchResults as $medicine)
                                    <button type="button"
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                            wire:click="selectMedicine({{ $medicine->id }})">
                                        <div>
                                            <strong>{{ $medicine->name }}</strong><br>
                                            <small class="text-muted">
                                                {{ $medicine->code }} |
                                                Stok: {{ $medicine->stock }} {{ $medicine->unit ? $medicine->unit->name : 'Unit' }} |
                                                Harga: Rp {{ number_format($medicine->selling_price ?: $medicine->price, 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <span class="badge badge-primary badge-pill">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @elseif($isSearching && empty($searchResults))
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> Tidak ada obat yang ditemukan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Keranjang Belanja -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Keranjang Belanja
                        @if($cartCount > 0)
                            <span class="badge badge-primary">{{ $cartCount }} item</span>
                        @endif
                    </h6>
                    @if(!empty($cart))
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearCart">
                            <i class="fas fa-trash"></i> Kosongkan
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if(empty($cart))
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Keranjang belanja kosong. Silakan cari dan pilih obat.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Obat</th>
                                        <th width="100">Harga</th>
                                        <th width="120">Jumlah</th>
                                        <th width="120">Subtotal</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $index => $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item['name'] }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $item['code'] }} |
                                                    Stok: {{ $item['stock'] }} {{ $item['unit'] }}
                                                </small>
                                            </td>
                                            <td class="text-right">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number"
                                                           class="form-control text-center"
                                                           min="1"
                                                           max="{{ $item['stock'] }}"
                                                           value="{{ $item['quantity'] }}"
                                                           wire:change="updateCartQuantity({{ $index }}, $event.target.value)">
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        wire:click="removeFromCart({{ $index }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right">
                                            <strong class="text-primary h5">
                                                Rp {{ number_format($cartTotal, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Tombol Lanjut ke Pembayaran -->
                        <div class="text-right mt-3">
                            <button type="button"
                                    class="btn btn-success btn-lg"
                                    wire:click="proceedToPayment">
                                <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    @if($showPaymentForm)
        <div class="row">
            <div class="col-lg-8 offset-lg-4">
                <div class="card shadow payment-form">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Belanja</label>
                                    <input type="text"
                                           class="form-control form-control-lg text-right font-weight-bold text-primary"
                                           value="Rp {{ number_format($cartTotal, 0, ',', '.') }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Bayar <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               id="paidAmountInput"
                                               wire:model="paidAmount"
                                               placeholder="Masukkan jumlah bayar..."
                                               oninput="handlePaymentInput(this)"
                                               onblur="formatPaymentInput(this)">
                                    </div>
                                    @if($paidAmount > 0 && $paidAmount < $cartTotal)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Jumlah bayar minimal Rp {{ number_format($cartTotal, 0, ',', '.') }}
                                        </small>
                                    @endif
                                    @if($paidAmount >= $cartTotal && $changeAmount > 0)
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i>
                                            Kembalian: Rp {{ number_format($changeAmount, 0, ',', '.') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kembalian</label>
                                    <input type="text"
                                           class="form-control form-control-lg text-right font-weight-bold text-success"
                                           value="Rp {{ number_format($changeAmount, 0, ',', '.') }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control form-control-lg" wire:model="paymentMethod">
                                        <option value="cash">Tunai</option>
                                        <option value="debit">Debit</option>
                                        <option value="credit">Kartu Kredit</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea class="form-control"
                                      wire:model="notes"
                                      rows="2"
                                      placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <div class="text-right mt-4">
                            <button type="button"
                                    class="btn btn-secondary btn-lg mr-2"
                                    wire:click="showPaymentForm = false">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                            <button type="button"
                                    class="btn btn-success btn-lg"
                                    wire:click="processPayment"
                                    @if($paidAmount < $cartTotal) disabled @endif>
                                <i class="fas fa-check"></i> Proses Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- CSS dan JavaScript akan di-load dari file terpisah -->

<script>
// Simple payment input handler
function handlePaymentInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    let numValue = parseInt(value) || 0;
    input.value = numValue;

    // Trigger Livewire untuk update paidAmount dan hitung kembalian
    if (window.Livewire) {
        const component = document.querySelector('[wire\\:id]');
        if (component) {
            const componentId = component.getAttribute('wire:id');
            window.Livewire.find(componentId).set('paidAmount', numValue);
            window.Livewire.find(componentId).call('calculateChange');
        }
    }
}

// Format payment input when user finishes typing
function formatPaymentInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    let numValue = parseInt(value) || 0;

    // Format dengan separator ribuan
    if (numValue > 0) {
        input.value = numValue.toLocaleString('id-ID');
    } else {
        input.value = '';
    }
}

// Auto-focus on search input
document.addEventListener('livewire:load', function () {
    const searchInput = document.getElementById('searchQuery');
    if (searchInput) {
        searchInput.focus();
    }
});
</script>
