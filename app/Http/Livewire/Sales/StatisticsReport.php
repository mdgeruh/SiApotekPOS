<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;

class StatisticsReport extends Component
{
    // Filter properties
    public $period = 'this_year';

    // Data properties
    public $periodStats = [];
    public $topMedicines = [];
    public $paymentStats = [];
    public $yearlyStats = [];
    public $categoryStats = [];

    protected $queryString = [
        'period' => ['except' => 'this_year'],
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedPeriod()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $dateRange = $this->getDateRange();

        // Statistik penjualan berdasarkan periode
        $this->periodStats = Sale::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // Top selling medicines untuk periode yang dipilih
        $this->topMedicines = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
        ->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
        ->orderByDesc('total_quantity')
        ->limit(10)
        ->get();

        // Sales by payment method untuk periode yang dipilih
        $this->paymentStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('payment_method')
        ->get();

        // Statistik tahunan
        $this->yearlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

        // Statistik kategori obat
        $this->categoryStats = SaleDetail::selectRaw('
            categories.name as category_name,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
        ->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_revenue')
        ->limit(10)
        ->get();
    }

    private function getDateRange()
    {
        switch ($this->period) {
            case 'today':
                return [
                    'start' => Carbon::today()->startOfDay(),
                    'end' => Carbon::today()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => Carbon::now()->startOfWeek()->startOfDay(),
                    'end' => Carbon::now()->endOfWeek()->endOfDay()
                ];
            case 'this_month':
                return [
                    'start' => Carbon::now()->startOfMonth()->startOfDay(),
                    'end' => Carbon::now()->endOfMonth()->endOfDay()
                ];
            case 'last_3_months':
                return [
                    'start' => Carbon::now()->subMonths(3)->startOfMonth()->startOfDay(),
                    'end' => Carbon::now()->endOfMonth()->endOfDay()
                ];
            case 'this_year':
                return [
                    'start' => Carbon::now()->startOfYear()->startOfDay(),
                    'end' => Carbon::now()->endOfYear()->endOfDay()
                ];
            case 'last_year':
                return [
                    'start' => Carbon::now()->subYear()->startOfYear()->startOfDay(),
                    'end' => Carbon::now()->subYear()->endOfYear()->endOfDay()
                ];
            case 'all':
                return [
                    'start' => Carbon::create(2020, 1, 1)->startOfDay(),
                    'end' => Carbon::now()->endOfDay()
                ];
            default:
                return [
                    'start' => Carbon::now()->startOfYear()->startOfDay(),
                    'end' => Carbon::now()->endOfYear()->endOfDay()
                ];
        }
    }

    public function resetFilters()
    {
        $this->reset(['period']);
        $this->loadData();
    }

    public function render()
    {
        $dateRange = $this->getDateRange();
        
        return view('livewire.sales.statistics-report', [
            'dateRange' => $dateRange
        ]);
    }
}
