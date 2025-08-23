<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\InvoiceCounter;
use App\Services\SaleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesManagement extends Component
{
    // Properties untuk pencarian obat
    public $searchQuery = '';
    public $searchResults = [];
    public $isSearching = false;

    // Properties untuk keranjang belanja
    public $cart = [];
    public $cartTotal = 0;
    public $cartCount = 0;

    // Properties untuk pembayaran
    public $paidAmount = 0;
    public $changeAmount = 0;
    public $paymentMethod = 'cash';
    public $notes = '';

    // Properties untuk form
    public $showPaymentForm = false;
    public $selectedMedicine = null;
    public $quantity = 1;

    protected $listeners = [
        'medicineSelected' => 'addToCart',
        'removeFromCart' => 'removeFromCart',
        'updateQuantity' => 'updateCartQuantity',
        'clearCart' => 'clearCart'
    ];

    public function mount()
    {
        $this->cart = [];
        $this->cartTotal = 0;
        $this->cartCount = 0;
    }

    public function searchMedicines()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->isSearching = true;
            $this->searchResults = Medicine::where(function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('code', 'like', '%' . $this->searchQuery . '%');
            })
            ->where('stock', '>', 0)
            ->with(['category', 'brand', 'unit'])
            ->limit(10)
            ->get();
        } else {
            $this->searchResults = [];
            $this->isSearching = false;
        }
    }

    public function selectMedicine($medicineId)
    {
        $medicine = Medicine::find($medicineId);
        if ($medicine && $medicine->stock > 0) {
            $this->selectedMedicine = $medicine;
            $this->quantity = 1;
            $this->addToCart($medicine->id, 1);
        }
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isSearching = false;
    }

    public function addToCart($medicineId, $quantity = null)
    {
        $medicine = Medicine::find($medicineId);
        if (!$medicine || $medicine->stock <= 0) {
            return;
        }

        $qty = $quantity ?? $this->quantity;
        if ($qty <= 0 || $qty > $medicine->stock) {
            return;
        }

        // Cek apakah obat sudah ada di keranjang
        $existingIndex = collect($this->cart)->search(function ($item) use ($medicineId) {
            return $item['medicine_id'] == $medicineId;
        });

        if ($existingIndex !== false) {
            // Update quantity jika sudah ada
            $newQty = $this->cart[$existingIndex]['quantity'] + $qty;
            if ($newQty <= $medicine->stock) {
                $this->cart[$existingIndex]['quantity'] = $newQty;
                $this->cart[$existingIndex]['subtotal'] = $newQty * $medicine->selling_price;
            }
        } else {
            // Tambah obat baru ke keranjang
            $this->cart[] = [
                'medicine_id' => $medicine->id,
                'name' => $medicine->name,
                'code' => $medicine->code,
                'price' => $medicine->selling_price ?: $medicine->price,
                'quantity' => $qty,
                'subtotal' => $qty * ($medicine->selling_price ?: $medicine->price),
                'stock' => $medicine->stock,
                'unit' => $medicine->unit ? $medicine->unit->name : 'Unit'
            ];
        }

        $this->updateCartTotals();
        $this->selectedMedicine = null;
        $this->quantity = 1;
    }

    public function removeFromCart($index)
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            $this->updateCartTotals();
        }
    }

    public function updateCartQuantity($index, $quantity)
    {
        if (isset($this->cart[$index])) {
            $medicine = Medicine::find($this->cart[$index]['medicine_id']);
            if ($medicine && $quantity > 0 && $quantity <= $medicine->stock) {
                $this->cart[$index]['quantity'] = $quantity;
                $this->cart[$index]['subtotal'] = $quantity * $this->cart[$index]['price'];
                $this->updateCartTotals();
            }
        }
    }

    public function updateCartTotals()
    {
        $this->cartTotal = collect($this->cart)->sum('subtotal');
        $this->cartCount = collect($this->cart)->sum('quantity');
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->cartTotal = 0;
        $this->cartCount = 0;
        $this->showPaymentForm = false;
        $this->paidAmount = 0;
        $this->changeAmount = 0;
    }

    public function proceedToPayment()
    {
        if (empty($this->cart)) {
            return;
        }
        $this->showPaymentForm = true;
        $this->emit('paymentFormShown');
    }

    public function calculateChange()
    {
        if ($this->paidAmount >= $this->cartTotal) {
            $this->changeAmount = $this->paidAmount - $this->cartTotal;
        } else {
            $this->changeAmount = 0;
        }
    }

    public function updatedPaidAmount()
    {
        // Pastikan paidAmount adalah angka positif
        if (is_numeric($this->paidAmount) && $this->paidAmount > 0) {
            $this->calculateChange();
        } else {
            $this->paidAmount = 0;
            $this->changeAmount = 0;
        }
    }

    public function processPayment()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong!');
            return;
        }

        if ($this->paidAmount < $this->cartTotal) {
            session()->flash('error', 'Jumlah bayar minimal Rp ' . number_format($this->cartTotal, 0, ',', '.'));
            return;
        }

        try {
            DB::beginTransaction();

            // Generate invoice number menggunakan InvoiceCounter
            $invoiceNumber = InvoiceCounter::generateInvoiceNumber();

            // Create sale record
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'total_amount' => $this->cartTotal,
                'paid_amount' => $this->paidAmount,
                'change_amount' => $this->changeAmount,
                'payment_method' => $this->paymentMethod,
                'notes' => $this->notes,
            ]);

            // Create sale details
            foreach ($this->cart as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock
                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // Reset form
            $this->clearCart();

            // Redirect ke halaman sukses atau cetak struk
            return redirect()->route('sales.show', $sale->id)->with('success', 'Penjualan berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.sales-management');
    }
}
