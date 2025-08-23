<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get sales report data
     */
    public function getSalesReport($startDate, $endDate): array
    {
        $sales = Sale::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $sales->count();
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        $dailyStats = Sale::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $paymentMethodStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('payment_method')
        ->get();

        return [
            'sales' => $sales,
            'total_sales' => $totalSales,
            'total_revenue' => $totalRevenue,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'daily_stats' => $dailyStats,
            'payment_method_stats' => $paymentMethodStats,
        ];
    }

    /**
     * Get sales statistics
     */
    public function getSalesStatistics(): array
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

        // Top selling medicines
        $topMedicines = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
        ->orderByDesc('total_quantity')
        ->limit(10)
        ->get();

        // Sales by payment method
        $paymentStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('payment_method')
        ->get();

        // Yearly statistics
        $yearlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

        return [
            'monthly' => $monthlyStats,
            'top_medicines' => $topMedicines,
            'payment_methods' => $paymentStats,
            'yearly' => $yearlyStats,
        ];
    }

    /**
     * Get stock report
     */
    public function getStockReport(): array
    {
        $totalMedicines = Medicine::count();
        $totalStock = Medicine::sum('stock');
        $totalValue = Medicine::sum(DB::raw('stock * price'));

        $lowStockMedicines = Medicine::where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get();

        $outOfStockMedicines = Medicine::where('stock', 0)
            ->with('category')
            ->get();

        $expiringMedicines = Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->get();

        $stockByCategory = Medicine::selectRaw('
            categories.name as category_name,
            COUNT(medicines.id) as total_medicines,
            SUM(medicines.stock) as total_stock,
            SUM(medicines.stock * medicines.price) as total_value
        ')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->groupBy('categories.id', 'categories.name')
        ->orderBy('total_value', 'desc')
        ->get();

        return [
            'summary' => [
                'total_medicines' => $totalMedicines,
                'total_stock' => $totalStock,
                'total_value' => $totalValue,
            ],
            'low_stock' => $lowStockMedicines,
            'out_of_stock' => $outOfStockMedicines,
            'expiring' => $expiringMedicines,
            'by_category' => $stockByCategory,
        ];
    }

    /**
     * Export sales report to CSV
     */
    public function exportSalesReport($startDate, $endDate): \Symfony\Component\HttpFoundation\Response
    {
        $sales = Sale::with(['user', 'saleDetails.medicine'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'sales_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Invoice Number',
                'Date',
                'Customer',
                'Items',
                'Total Amount',
                'Payment Method',
                'Cashier'
            ]);

            // CSV data
            foreach ($sales as $sale) {
                $items = $sale->saleDetails->map(function($detail) {
                    return $detail->medicine->name . ' (' . $detail->quantity . ')';
                })->implode(', ');

                fputcsv($file, [
                    $sale->invoice_number,
                    $sale->created_at->format('Y-m-d H:i:s'),
                    'Walk-in Customer',
                    $items,
                    number_format($sale->total_amount, 2),
                    $sale->payment_method,
                    $sale->user->name
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export stock report to CSV
     */
    public function exportStockReport(): \Symfony\Component\HttpFoundation\Response
    {
        $medicines = Medicine::with('category')->orderBy('name')->get();

        $filename = 'stock_report_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($medicines) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Code',
                'Name',
                'Category',
                'Stock',
                'Unit',
                'Price',
                'Total Value',
                'Expired Date'
            ]);

            // CSV data
            foreach ($medicines as $medicine) {
                fputcsv($file, [
                    $medicine->code,
                    $medicine->name,
                    $medicine->category->name ?? 'Uncategorized',
                    $medicine->stock,
                    $medicine->unit,
                    number_format($medicine->price, 2),
                    number_format($medicine->stock * $medicine->price, 2),
                    $medicine->expired_date ? $medicine->expired_date->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
