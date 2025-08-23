<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class SalesRepository
{
    protected $sale;
    protected $saleDetail;
    protected $medicine;

    public function __construct(Sale $sale, SaleDetail $saleDetail, Medicine $medicine)
    {
        $this->sale = $sale;
        $this->saleDetail = $saleDetail;
        $this->medicine = $medicine;
    }

    /**
     * Get all sales without pagination
     */
    public function getAllSales(): Collection
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get sales by date range
     */
    public function getSalesByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get sales by payment method
     */
    public function getSalesByPaymentMethod(string $paymentMethod): Collection
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->where('payment_method', $paymentMethod)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get sales by user
     */
    public function getSalesByUser(int $userId): Collection
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get sale by ID with details
     */
    public function getSaleById(int $saleId): ?Sale
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine.category', 'saleDetails.medicine.brand', 'saleDetails.medicine.unit'])
            ->find($saleId);
    }

    /**
     * Get sale by invoice number
     */
    public function getSaleByInvoiceNumber(string $invoiceNumber): ?Sale
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->where('invoice_number', $invoiceNumber)
            ->first();
    }

    /**
     * Create new sale
     */
    public function createSale(array $data): Sale
    {
        return $this->sale->create($data);
    }

    /**
     * Create sale details
     */
    public function createSaleDetails(int $saleId, array $details): void
    {
        foreach ($details as $detail) {
            $detail['sale_id'] = $saleId;
            $this->saleDetail->create($detail);
        }
    }

    /**
     * Update sale
     */
    public function updateSale(int $saleId, array $data): bool
    {
        $sale = $this->sale->find($saleId);
        if ($sale) {
            return $sale->update($data);
        }
        return false;
    }

    /**
     * Delete sale
     */
    public function deleteSale(int $saleId): bool
    {
        $sale = $this->sale->find($saleId);
        if ($sale) {
            // Restore stock before deleting
            foreach ($sale->saleDetails as $detail) {
                $medicine = $this->medicine->find($detail->medicine_id);
                if ($medicine) {
                    $medicine->increment('stock', $detail->quantity);
                }
            }

            return $sale->delete();
        }
        return false;
    }

    /**
     * Get sales statistics
     */
    public function getSalesStatistics(string $startDate = null, string $endDate = null): array
    {
        $query = $this->sale->query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalSales = $query->count();
        $totalAmount = $query->sum('total_amount');
        $totalPaid = $query->sum('paid_amount');
        $totalChange = $query->sum('change_amount');

        // Payment method breakdown
        $paymentMethods = $this->sale
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Daily sales for last 30 days
        $dailySales = $this->sale
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_sales' => $totalSales,
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_change' => $totalChange,
            'payment_methods' => $paymentMethods,
            'daily_sales' => $dailySales,
        ];
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $limit = 10, string $startDate = null, string $endDate = null): Collection
    {
        $query = $this->saleDetail
            ->with(['medicine', 'sale'])
            ->selectRaw('medicine_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
            ->groupBy('medicine_id');

        if ($startDate && $endDate) {
            $query->whereHas('sale', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        return $query->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get low stock medicines
     */
    public function getLowStockMedicines(int $threshold = 10): Collection
    {
        return $this->medicine
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Search sales
     */
    public function searchSales(string $searchTerm): Collection
    {
        return $this->sale
            ->with(['user', 'saleDetails.medicine'])
            ->where(function ($query) use ($searchTerm) {
                $query->where('invoice_number', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('saleDetails.medicine', function ($medicineQuery) use ($searchTerm) {
                          $medicineQuery->where('name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('code', 'like', '%' . $searchTerm . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get sales count by date
     */
    public function getSalesCountByDate(string $date): int
    {
        return $this->sale->whereDate('created_at', $date)->count();
    }

    /**
     * Get total sales amount by date
     */
    public function getTotalSalesAmountByDate(string $date): float
    {
        return $this->sale->whereDate('created_at', $date)->sum('total_amount');
    }
}
