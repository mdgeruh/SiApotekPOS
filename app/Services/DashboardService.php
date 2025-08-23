<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Medicine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'total_sales' => $this->getTotalSales(),
            'total_medicines' => $this->getTotalMedicines(),
            'total_users' => $this->getTotalUsers(),
            'today_sales' => $this->getTodaySalesCount(),
            'today_revenue' => $this->getTodayRevenue(),
            'low_stock_medicines' => $this->getLowStockMedicinesCount(),
            'expiring_medicines' => $this->getExpiringMedicinesCount(),
            'expired_medicines' => $this->getExpiredMedicinesCount(),
        ];
    }

    /**
     * Get dashboard charts data
     */
    public function getDashboardCharts(): array
    {
        return [
            'monthly_sales' => $this->getMonthlySalesChart(),
            'top_selling_medicines' => $this->getTopSellingMedicines(),
            'payment_methods' => $this->getPaymentMethodStats(),
        ];
    }

    /**
     * Get dashboard lists
     */
    public function getDashboardLists(): array
    {
        // Clear any potential cache to ensure fresh data
        $this->clearCache();

        return [
            'recent_sales' => $this->getRecentSales(),
            'low_stock_medicines_list' => $this->getLowStockMedicinesList(),
            'expiring_medicines_list' => $this->getExpiringMedicinesList(),
            'expired_medicines_list' => $this->getExpiredMedicinesList(),
        ];
    }

    /**
     * Clear cache and ensure fresh data
     */
    private function clearCache(): void
    {
        // Clear query cache
        DB::flushQueryLog();

        // Clear any potential model cache
        Medicine::flushEventListeners();

        // Force refresh of any cached queries
        DB::connection()->disableQueryLog();
        DB::connection()->enableQueryLog();
    }

    /**
     * Get total sales count
     */
    private function getTotalSales(): int
    {
        return Sale::count();
    }

    /**
     * Get total medicines count
     */
    private function getTotalMedicines(): int
    {
        return Medicine::count();
    }

    /**
     * Get total users count
     */
    private function getTotalUsers(): int
    {
        return User::count();
    }

    /**
     * Get today's sales count
     */
    private function getTodaySalesCount(): int
    {
        return Sale::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Get today's revenue
     */
    private function getTodayRevenue(): float
    {
        return Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
    }

    /**
     * Get low stock medicines count
     * Improved logic: consider min_stock field and stock levels
     */
    private function getLowStockMedicinesCount(): int
    {
        return Medicine::where(function($query) {
            $query->where('stock', '<=', DB::raw('min_stock'))
                  ->orWhere('stock', '<=', 5); // Critical level
        })->count();
    }

    /**
     * Get expiring medicines count (within 30 days)
     */
    private function getExpiringMedicinesCount(): int
    {
        return Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))
            ->where('expired_date', '>', Carbon::now())
            ->where('stock', '>', 0) // Only count medicines with stock
            ->count();
    }

    /**
     * Get expired medicines count
     */
    private function getExpiredMedicinesCount(): int
    {
        // Clear any potential cache and get fresh data
        DB::flushQueryLog();

        // Get count of expired medicines with stock > 0
        $count = Medicine::where('expired_date', '<', Carbon::now())
            ->where('stock', '>', 0)
            ->count();

        // Log for debugging (remove in production)
        Log::info("Expired medicines count: {$count}", [
            'query' => Medicine::where('expired_date', '<', Carbon::now())
                ->where('stock', '>', 0)
                ->toSql()
        ]);

        return $count;
    }

    /**
     * Get monthly sales chart data
     */
    private function getMonthlySalesChart()
    {
        $data = Sale::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total_sales, SUM(total_amount) as total_revenue')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // If no data, return empty collection
        if ($data->isEmpty()) {
            return collect();
        }

        return $data;
    }

    /**
     * Get top selling medicines
     */
    private function getTopSellingMedicines()
    {
        return DB::table('sale_details')
            ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', 'medicines.code', DB::raw('SUM(sale_details.quantity) as total_sold'))
            ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get payment method statistics
     */
    private function getPaymentMethodStats()
    {
        $data = Sale::selectRaw('payment_method, COUNT(*) as total, SUM(total_amount) as total_amount')
            ->groupBy('payment_method')
            ->get();

        // If no data, return empty collection
        if ($data->isEmpty()) {
            return collect();
        }

        return $data;
    }

    /**
     * Get recent sales
     */
    private function getRecentSales()
    {
        return Sale::with('user')->latest()->take(5)->get();
    }

    /**
     * Get low stock medicines list
     * Improved logic: consider min_stock field and stock levels
     */
    private function getLowStockMedicinesList()
    {
        return Medicine::with(['unit', 'category'])
            ->where(function($query) {
                $query->where('stock', '<=', DB::raw('min_stock'))
                      ->orWhere('stock', '<=', 5); // Critical level
            })
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get expiring medicines list (within 30 days)
     */
    private function getExpiringMedicinesList()
    {
        return Medicine::with(['unit', 'category'])
            ->where('expired_date', '<=', Carbon::now()->addDays(30))
            ->where('expired_date', '>', Carbon::now())
            ->where('stock', '>', 0) // Only count medicines with stock
            ->orderBy('expired_date', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get expired medicines list
     */
    private function getExpiredMedicinesList()
    {
        // Clear any potential cache and get fresh data
        DB::flushQueryLog();

        // Get expired medicines with stock > 0
        $medicines = Medicine::with(['unit', 'category'])
            ->where('expired_date', '<', Carbon::now())
            ->where('stock', '>', 0)
            ->orderBy('expired_date', 'desc')
            ->limit(10)
            ->get();

        // Log for debugging (remove in production)
        Log::info("Expired medicines list count: {$medicines->count()}", [
            'medicines' => $medicines->map(function($medicine) {
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'code' => $medicine->code,
                    'stock' => $medicine->stock,
                    'expired_date' => $medicine->expired_date,
                    'days_expired' => Carbon::parse($medicine->expired_date)->diffInDays(now())
                ];
            })
        ]);

        return $medicines;
    }
}
