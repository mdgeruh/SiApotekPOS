<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockReport extends Component
{
    // Filter properties
    public $categoryFilter = '';
    public $brandFilter = '';
    public $stockStatus = 'all';

    // Data properties
    public $medicines = [];
    public $stockStats = [];
    public $categoryStockStats = [];
    public $categories = [];
    public $brands = [];

    protected $queryString = [
        'categoryFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'stockStatus' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedCategoryFilter()
    {
        $this->loadData();
    }

    public function updatedBrandFilter()
    {
        $this->loadData();
    }

    public function updatedStockStatus()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = Medicine::with(['category', 'brand', 'unit']);

        // Filter berdasarkan kategori
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Filter berdasarkan brand
        if ($this->brandFilter) {
            $query->where('brand_id', $this->brandFilter);
        }

        // Filter berdasarkan status stok - PERBAIKAN LOGIC
        switch ($this->stockStatus) {
            case 'low':
                // Stok menipis: stok < min_stock tapi > 0
                $query->whereRaw('stock < min_stock')
                      ->where('stock', '>', 0);
                break;
            case 'out':
                // Stok habis: stok = 0
                $query->where('stock', 0);
                break;
            case 'expiring':
                // Obat yang akan expired dalam 30 hari, terlepas dari stok
                $query->where('expired_date', '<=', Carbon::now()->addDays(30))
                      ->where('expired_date', '>', Carbon::now()); // Pastikan belum expired
                break;
            case 'expired':
                // Obat yang sudah expired
                $query->where('expired_date', '<=', Carbon::now());
                break;
            case 'available':
                // Stok tersedia: stok >= min_stock dan tidak expired dalam 30 hari
                $query->whereRaw('stock >= min_stock')
                      ->where(function($q) {
                          $q->whereNull('expired_date')
                            ->orWhere('expired_date', '>', Carbon::now()->addDays(30));
                      });
                break;
        }

        $this->medicines = $query->orderBy('stock', 'asc')->get();

        // Jika tidak ada data yang ditemukan untuk filter tertentu, tampilkan semua data
        if (count($this->medicines) == 0 && $this->stockStatus != 'all') {
            $this->medicines = Medicine::with(['category', 'brand', 'unit'])
                                     ->orderBy('stock', 'asc')
                                     ->get();
        }

        // Data untuk filter
        $this->categories = Category::orderBy('name')->get();
        $this->brands = Brand::orderBy('name')->get();

        // Statistik stok - PERBAIKAN STATISTIK
        $this->stockStats = [
            'total_medicines' => Medicine::count(),
            'low_stock' => Medicine::whereRaw('stock < min_stock')
                                   ->where('stock', '>', 0)->count(),
            'out_of_stock' => Medicine::where('stock', 0)->count(),
            'expiring_soon' => Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))
                                        ->where('expired_date', '>', Carbon::now())->count(),
            'expired' => Medicine::where('expired_date', '<=', Carbon::now())->count(),
            'total_stock_value' => Medicine::sum(DB::raw('stock * price')),
            'available_stock' => Medicine::whereRaw('stock >= min_stock')
                                         ->where(function($q) {
                                             $q->whereNull('expired_date')
                                               ->orWhere('expired_date', '>', Carbon::now()->addDays(30));
                                         })->count()
        ];

        // Statistik berdasarkan kategori
        $this->categoryStockStats = Medicine::selectRaw('
            categories.name as category_name,
            COUNT(*) as total_medicines,
            SUM(stock) as total_stock,
            SUM(stock * price) as total_value
        ')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_value')
        ->get();
    }

    public function resetFilters()
    {
        $this->reset(['categoryFilter', 'brandFilter', 'stockStatus']);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sales.stock-report');
    }
}
