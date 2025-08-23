<?php

namespace App\Http\Livewire\Medicine;

use Livewire\Component;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MedicineManagement extends Component
{
    public $search = '';

    // Form properties for adding new medicine
    public $showAddForm = false;
    public $name = '';
    public $code = '';
    public $description = '';
    public $category_id = '';
    public $brand_id = '';
    public $manufacturer_id = '';
    public $unit_id = '';
    public $purchase_price = '';
    public $selling_price = '';
    public $stock = 0;
    public $min_stock = 10;
    public $expired_date = '';

    // Form properties for updating stock
    public $showStockForm = false;
    public $selectedMedicineId = null;
    public $currentStock = 0;
    public $stockChange = '';
    public $stockChangeType = 'add'; // 'add' or 'subtract'
    public $stockNote = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'medicineSelected' => 'setSelectedMedicine',
        'viewAllSearchResults' => 'viewAllSearchResults'
    ];

    // Add watcher for search
    public function updatedSearch()
    {
        // Log untuk debugging
        Log::info('Search updated:', ['search' => $this->search]);
    }

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'code' => 'required|unique:medicines,code',
        'category_id' => 'required|exists:categories,id',
        'unit_id' => 'required|exists:units,id',
        'purchase_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'min_stock' => 'required|integer|min:0',
        'expired_date' => 'required|date|after:today',
        'stockChange' => 'required|numeric|min:0.01',
        'stockNote' => 'nullable|max:255',
    ];

    protected $messages = [
        'name.required' => 'Nama obat wajib diisi',
        'name.min' => 'Nama obat minimal 2 karakter',
        'name.max' => 'Nama obat maksimal 100 karakter',
        'code.required' => 'Kode obat wajib diisi',
        'code.unique' => 'Kode obat sudah digunakan',
        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori tidak valid',
        'unit_id.required' => 'Satuan wajib dipilih',
        'unit_id.exists' => 'Satuan tidak valid',
        'purchase_price.required' => 'Harga beli wajib diisi',
        'purchase_price.numeric' => 'Harga beli harus berupa angka',
        'purchase_price.min' => 'Harga beli tidak boleh negatif',
        'selling_price.required' => 'Harga jual wajib diisi',
        'selling_price.numeric' => 'Harga jual harus berupa angka',
        'selling_price.min' => 'Harga jual tidak boleh negatif',
        'stock.required' => 'Stok wajib diisi',
        'stock.integer' => 'Stok harus berupa angka bulat',
        'stock.min' => 'Stok tidak boleh negatif',
        'min_stock.required' => 'Stok minimum wajib diisi',
        'min_stock.integer' => 'Stok minimum harus berupa angka bulat',
        'min_stock.min' => 'Stok minimum tidak boleh negatif',
        'expired_date.required' => 'Tanggal kadaluarsa wajib diisi',
        'expired_date.date' => 'Format tanggal tidak valid',
        'expired_date.after' => 'Tanggal kadaluarsa minimal besok',
        'stockChange.required' => 'Jumlah perubahan stok wajib diisi',
        'stockChange.numeric' => 'Jumlah perubahan stok harus berupa angka',
        'stockChange.min' => 'Jumlah perubahan stok minimal 0.01',
        'stockNote.max' => 'Catatan maksimal 255 karakter',
    ];

    public function clearSearch()
    {
        $this->search = '';
        $this->emit('searchCleared');
    }

    public function setSelectedMedicine($medicine)
    {
        // Handle medicine selection from other components
        if (is_array($medicine)) {
            $medicine = (object) $medicine;
        }

        // You can implement logic here to handle selected medicine
        // For example, redirect to medicine detail or highlight in table
    }

    public function viewAllSearchResults($searchQuery)
    {
        $this->search = $searchQuery;
    }

    public function showAddMedicineForm()
    {
        $this->showAddForm = true;
        $this->generateCode();
        $this->resetForm();

        // Set default values
        $this->min_stock = 10;
        $this->stock = 0;
        $this->expired_date = now()->addDays(1)->format('Y-m-d');
    }

    public function hideAddMedicineForm()
    {
        $this->showAddForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'code', 'description', 'category_id', 'brand_id',
            'manufacturer_id', 'unit_id', 'purchase_price', 'selling_price',
            'stock', 'min_stock', 'expired_date'
        ]);
    }

    public function generateCode()
    {
        $prefix = 'OBT';
        $year = date('Y');
        $month = date('m');

        // Get the last medicine code for this month
        $lastMedicine = Medicine::where('code', 'like', $prefix . $year . $month . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastMedicine) {
            $lastNumber = (int) substr($lastMedicine->code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->code = $prefix . $year . $month . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function saveMedicine()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Auto-calculate selling price if not provided
            if ($this->purchase_price && (empty($this->selling_price) || $this->selling_price == '' || $this->selling_price == 0)) {
                // Set selling price to 30% markup from purchase price
                $this->selling_price = round($this->purchase_price * 1.3);
            }

            // Validate selling price is higher than purchase price
            if ($this->selling_price && $this->purchase_price) {
                if ($this->selling_price <= $this->purchase_price) {
                    session()->flash('message', 'Harga jual harus lebih tinggi dari harga beli!');
                    session()->flash('messageType', 'error');
                    return;
                }
            }

            Medicine::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'manufacturer_id' => $this->manufacturer_id,
                'unit_id' => $this->unit_id,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
                'price' => $this->selling_price, // Set price to selling_price for compatibility
                'stock' => $this->stock,
                'min_stock' => $this->min_stock,
                'expired_date' => $this->expired_date,
            ]);

            DB::commit();
            $this->hideAddMedicineForm();
            session()->flash('message', 'Obat berhasil ditambahkan!');
            session()->flash('messageType', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('message', 'Gagal menambahkan obat: ' . $e->getMessage());
            session()->flash('messageType', 'error');
        }
    }

    public function showStockUpdateForm($medicineId)
    {
        $medicine = Medicine::find($medicineId);
        if ($medicine) {
            $this->selectedMedicineId = $medicineId;
            $this->currentStock = $medicine->stock;
            $this->showStockForm = true;
        }
    }

    public function hideStockUpdateForm()
    {
        $this->showStockForm = false;
        $this->selectedMedicineId = null;
        $this->currentStock = 0;
        $this->stockChange = '';
        $this->stockChangeType = 'add';
        $this->stockNote = '';
    }

    public function updateStock()
    {
        $this->validate([
            'stockChange' => 'required|numeric|min:0.01',
            'stockNote' => 'nullable|max:255',
        ]);

        try {
            DB::beginTransaction();

            $medicine = Medicine::find($this->selectedMedicineId);
            if (!$medicine) {
                throw new \Exception('Obat tidak ditemukan');
            }

            $newStock = $this->stockChangeType === 'add'
                ? $medicine->stock + $this->stockChange
                : $medicine->stock - $this->stockChange;

            if ($newStock < 0) {
                throw new \Exception('Stok tidak boleh negatif');
            }

            $medicine->update(['stock' => $newStock]);

            DB::commit();
            $this->hideStockUpdateForm();
            session()->flash('message', 'Stok berhasil diperbarui!');
            session()->flash('messageType', 'success');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('message', 'Gagal memperbarui stok: ' . $e->getMessage());
            session()->flash('messageType', 'error');
        }
    }

    public function render()
    {
        $query = Medicine::with(['category', 'brand', 'unit', 'manufacturer']);

        // Apply search filter only
        if ($this->search && trim($this->search) !== '') {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . trim($this->search) . '%')
                  ->orWhere('code', 'like', '%' . trim($this->search) . '%')
                  ->orWhere('description', 'like', '%' . trim($this->search) . '%');
            });
        }

        // Log query untuk debugging
        Log::info('Medicine query with search:', [
            'search' => $this->search,
        ]);

        $medicines = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $manufacturers = Manufacturer::orderBy('name')->get();

        return view('livewire.medicine.medicine-management', compact('medicines', 'categories', 'brands', 'units', 'manufacturers'));
    }
}

