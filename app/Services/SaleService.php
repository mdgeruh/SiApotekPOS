<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SaleService
{
    /**
     * Get all sales with user
     */
    public function getAllSales()
    {
        return Sale::with('user')->latest()->get();
    }

    /**
     * Get sales by date range
     */
    public function getSalesByDateRange($startDate, $endDate)
    {
        return Sale::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create new sale with details
     */
    public function createSale(array $saleData, array $items): Sale
    {
        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'total_amount' => $saleData['total_amount'],
                'paid_amount' => $saleData['paid_amount'],
                'change_amount' => $saleData['paid_amount'] - $saleData['total_amount'],
                'payment_method' => $saleData['payment_method'],
                'notes' => $saleData['notes'] ?? '',
            ]);

            // Create sale details and update stock
            foreach ($items as $item) {
                $medicine = Medicine::find($item['medicine_id']);

                if (!$medicine || $medicine->stock < $item['quantity']) {
                    throw new \Exception("Stok {$medicine->name} tidak mencukupi.");
                }

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock
                $medicine->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return $sale;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update sale
     */
    public function updateSale(Sale $sale, array $data): bool
    {
        return $sale->update($data);
    }

    /**
     * Delete sale
     */
    public function deleteSale(Sale $sale): bool
    {
        DB::beginTransaction();
        try {
            // Restore stock
            foreach ($sale->saleDetails as $detail) {
                $medicine = Medicine::find($detail->medicine_id);
                if ($medicine) {
                    $medicine->increment('stock', $detail->quantity);
                }
            }

            // Delete sale details
            $sale->saleDetails()->delete();

            // Delete sale
            $sale->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Generate invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $today = Carbon::today();
        $count = Sale::whereDate('created_at', $today)->count() + 1;
        return 'INV-' . $today->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get sales statistics
     */
    public function getSalesStatistics()
    {
        $currentYear = Carbon::now()->year;

        // Monthly statistics
        $monthlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereYear('created_at', $currentYear)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // Payment method statistics
        $paymentStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('payment_method')
        ->get();

        // Daily statistics for current month
        $dailyStats = Sale::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereMonth('created_at', Carbon::now()->month)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'monthly' => $monthlyStats,
            'payment_methods' => $paymentStats,
            'daily' => $dailyStats,
        ];
    }

    /**
     * Get today's sales count
     */
    public function getTodaySalesCount(): int
    {
        return Sale::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Get today's revenue
     */
    public function getTodayRevenue(): float
    {
        return Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
    }

    /**
     * Get recent sales
     */
    public function getRecentSales(int $limit = 5)
    {
        return Sale::with('user')->latest()->limit($limit)->get();
    }
}
