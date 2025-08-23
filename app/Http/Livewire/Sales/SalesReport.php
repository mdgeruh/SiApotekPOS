<?php

namespace App\Http\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesReport extends Component
{
    public $period = 'this_month';
    public $startDate;
    public $endDate;
    public $paymentMethod = '';
    public $showCustomDate = false;

    public $sales = null;
    public $totalSales = 0;
    public $totalRevenue = 0;
    public $totalItems = 0;
    public $paymentMethodStats = [];
    public $topMedicines = [];
    public $dailyStats = [];

    // Tambahan untuk fallback
    public $hasData = false;
    public $errorMessage = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedPeriod()
    {
        if ($this->period !== 'custom') {
            $this->showCustomDate = false;
            $this->startDate = null;
            $this->endDate = null;
        } else {
            $this->showCustomDate = true;
        }

        $this->loadData();
    }

    public function updatedStartDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->period = 'custom';
        }
        $this->loadData();
    }

    public function updatedEndDate()
    {
        if ($this->startDate && $this->endDate) {
            $this->loadData();
        }
    }

    public function updatedPaymentMethod()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $dateRange = $this->getDateRange();

        // Reset error
        $this->errorMessage = '';

        try {
            // Cek total data sales di database (tanpa filter)
            $totalSalesInDB = Sale::count();

            if ($totalSalesInDB == 0) {
                $this->errorMessage = 'Tidak ada data penjualan di database. Silakan buat transaksi penjualan terlebih dahulu.';
                $this->setEmptyData();
                return;
            }

            // Query untuk sales
            $query = Sale::with(['user', 'saleDetails.medicine']);

            // Apply date filters only if not 'all' period
            if ($this->period !== 'all') {
                if ($dateRange['start']) {
                    $query->where('created_at', '>=', $dateRange['start']);
                }
                if ($dateRange['end']) {
                    $query->where('created_at', '<=', $dateRange['end']);
                }
            }

            // Apply payment method filter
            if ($this->paymentMethod) {
                $query->where('payment_method', $this->paymentMethod);
            }

            $this->sales = $query->orderBy('created_at', 'desc')->get();

            // Jika tidak ada data untuk periode yang dipilih, coba tampilkan semua data
            if ($this->sales->count() == 0 && $this->period !== 'all') {
                $fallbackQuery = Sale::with(['user', 'saleDetails.medicine']);

                if ($this->paymentMethod) {
                    $fallbackQuery->where('payment_method', $this->paymentMethod);
                }

                $this->sales = $fallbackQuery->orderBy('created_at', 'desc')->get();

                if ($this->sales->count() > 0) {
                    $this->errorMessage = 'Tidak ada data untuk periode yang dipilih. Menampilkan semua data penjualan.';
                }
            }

            // Hitung total sales dan revenue
            $this->totalSales = $this->sales->count();
            $this->totalRevenue = $this->sales->sum('total_amount');

            // Hitung total items
            $this->totalItems = $this->sales->sum(function ($sale) {
                return $sale->saleDetails->sum('quantity');
            });

            // Statistik metode pembayaran
            $this->paymentMethodStats = $this->sales->groupBy('payment_method')
                ->map(function ($group) {
                    return [
                        'payment_method' => $group->first()->payment_method,
                        'total_sales' => $group->count(),
                        'total_revenue' => $group->sum('total_amount')
                    ];
                })->values()->toArray();

            // Top medicines
            $medicineStats = [];
            foreach ($this->sales as $sale) {
                foreach ($sale->saleDetails as $detail) {
                    $medicineId = $detail->medicine_id;
                    if (!isset($medicineStats[$medicineId])) {
                        $medicineStats[$medicineId] = [
                            'name' => $detail->medicine->name ?? 'Unknown',
                            'code' => $detail->medicine->code ?? 'N/A',
                            'total_quantity' => 0,
                            'total_revenue' => 0
                        ];
                    }
                    $medicineStats[$medicineId]['total_quantity'] += $detail->quantity;
                    $medicineStats[$medicineId]['total_revenue'] += $detail->subtotal;
                }
            }

            // Sort dan ambil top 10
            $this->topMedicines = collect($medicineStats)
                ->sortByDesc('total_quantity')
                ->take(10)
                ->values()
                ->toArray();

            // Daily stats
            $this->dailyStats = $this->sales->groupBy(function ($sale) {
                return $sale->created_at->format('Y-m-d');
            })->map(function ($group, $date) {
                return [
                    'date' => $date,
                    'total_sales' => $group->count(),
                    'total_revenue' => $group->sum('total_amount')
                ];
            })->sortByDesc('date')->values()->toArray();

            // Set flag bahwa ada data
            $this->hasData = $this->sales->count() > 0;

        } catch (\Exception $e) {
            // Log error
            Log::error('SalesReport error: ' . $e->getMessage(), [
                'period' => $this->period,
                'date_range' => $dateRange,
                'trace' => $e->getTraceAsString()
            ]);

            $this->errorMessage = 'Terjadi kesalahan saat memuat data: ' . $e->getMessage();
            $this->setEmptyData();
        }
    }

    private function setEmptyData()
    {
        $this->sales = collect();
        $this->totalSales = 0;
        $this->totalRevenue = 0;
        $this->totalItems = 0;
        $this->paymentMethodStats = [];
        $this->topMedicines = [];
        $this->dailyStats = [];
        $this->hasData = false;
    }

    public function resetFilters()
    {
        $this->reset(['period', 'startDate', 'endDate', 'paymentMethod']);
        $this->showCustomDate = false;
        $this->loadData();
    }

    public function showAllData()
    {
        $this->period = 'all';
        $this->loadData();
    }

    private function getDateRange()
    {
        $now = Carbon::now();

        switch ($this->period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'last_3_months':
                return [
                    'start' => $now->copy()->subMonths(3)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'last_year':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear()
                ];
            case 'custom':
                return [
                    'start' => $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null,
                    'end' => $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null
                ];
            case 'all':
                return [
                    'start' => null,
                    'end' => null
                ];
            default:
                return [
                    'start' => null,
                    'end' => null
                ];
        }
    }

    public function render()
    {
        $dateRange = $this->getDateRange();
        return view('livewire.sales.sales-report', [
            'dateRange' => $dateRange
        ]);
    }
}
