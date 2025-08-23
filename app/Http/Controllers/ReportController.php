<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Exports\SalesReportExport;
use App\Exports\StockReportExport;
use App\Exports\StatisticsReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function salesReport()
    {
        return view('reports.sales');
    }

    public function salesStatistics()
    {
        return view('reports.statistics');
    }

    public function stockReport()
    {
        return view('reports.stock');
    }

    // Export methods
    public function exportSalesReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $paymentMethod = $request->get('payment_method');
        $period = $request->get('period');
        $format = $request->get('format', 'csv');

        // Handle period-based date filtering
        if (!$startDate && !$endDate && $period) {
            $dateRange = $this->getDateRangeFromPeriod($period);
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
        }

        if ($format === 'csv') {
            return $this->exportToCSV($startDate, $endDate, $paymentMethod, $period);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($startDate, $endDate, $paymentMethod, $period);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($startDate, $endDate, $paymentMethod, $period);
        } else {
            return response()->json(['error' => 'Format tidak didukung: ' . $format], 400);
        }
    }

    public function exportStockReport(Request $request)
    {
        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            return $this->exportStockToCSV();
        } elseif ($format === 'excel') {
            return $this->exportStockToExcel();
        } elseif ($format === 'pdf') {
            return $this->exportStockToPDF();
        } else {
            return response()->json(['error' => 'Format tidak didukung'], 400);
        }
    }

    public function exportStatisticsReport(Request $request)
    {
        $format = $request->get('format', 'excel');

        if ($format === 'csv') {
            return $this->exportStatisticsToCSV();
        } elseif ($format === 'excel') {
            return $this->exportStatisticsToExcel();
        } elseif ($format === 'pdf') {
            return $this->exportStatisticsToPDF();
        } else {
            return response()->json(['error' => 'Format tidak didukung'], 400);
        }
    }

    // CSV Export methods
    private function exportToCSV($startDate, $endDate, $paymentMethod = null, $period = null)
    {
        $query = Sale::with(['user', 'saleDetails.medicine']);

        // Apply date filters only if not 'all' period and dates are provided
        if ($period !== 'all' && $startDate && $endDate) {
            // Convert string dates to Carbon instances
            if (is_string($startDate)) {
                $startDate = Carbon::parse($startDate)->startOfDay();
            }
            if (is_string($endDate)) {
                $endDate = Carbon::parse($endDate)->endOfDay();
            }

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply payment method filter if specified
        if ($paymentMethod && $paymentMethod !== '') {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $filename = 'laporan_penjualan_' . Carbon::parse($startDate)->format('Y-m-d') . '_' . Carbon::parse($endDate)->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'No',
                'Invoice Number',
                'Tanggal',
                'Kasir',
                'Metode Pembayaran',
                'Total Items',
                'Total Amount',
                'Paid Amount',
                'Change Amount',
                'Notes'
            ]);

            // Data CSV
            $no = 1;
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $no++,
                    $sale->invoice_number,
                    $sale->created_at->format('d/m/Y H:i:s'),
                    $sale->user->name ?? 'N/A',
                    ucfirst($sale->payment_method),
                    $sale->saleDetails->count() ?? 0,
                    $sale->total_amount, // Raw number for proper formatting
                    $sale->paid_amount,   // Raw number for proper formatting
                    $sale->change_amount, // Raw number for proper formatting
                    $sale->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportStockToCSV()
    {
        $medicines = Medicine::with(['category', 'brand', 'unit'])
            ->orderBy('stock', 'asc')
            ->get();

        $filename = 'laporan_stok_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($medicines) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header CSV
            fputcsv($file, [
                'Kode Obat',
                'Nama Obat',
                'Kategori',
                'Brand',
                'Stok',
                'Unit',
                'Harga',
                'Total Nilai Stok',
                'Expired Date',
                'Status Stok',
                'Manufacturer'
            ]);

            // Data CSV
            foreach ($medicines as $medicine) {
                $stockStatus = '';
                if ($medicine->stock == 0) {
                    $stockStatus = 'Habis';
                } elseif ($medicine->stock <= 10) {
                    $stockStatus = 'Menipis';
                } else {
                    $stockStatus = 'Tersedia';
                }

                $totalValue = $medicine->stock * $medicine->price;
                $expiredDate = $medicine->expired_date ?
                    Carbon::parse($medicine->expired_date)->format('d/m/Y') : 'N/A';

                fputcsv($file, [
                    $medicine->code,
                    $medicine->name,
                    $medicine->category->name ?? 'N/A',
                    $medicine->brand->name ?? 'N/A',
                    $medicine->stock,
                    $medicine->unit->name ?? 'N/A',
                    $medicine->price, // Raw number for proper formatting
                    $totalValue,       // Raw number for proper formatting
                    $expiredDate,
                    $stockStatus,
                    $medicine->manufacturer ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // Excel Export methods
    private function exportToExcel($startDate, $endDate, $paymentMethod = null, $period = null)
    {
        // Generate filename based on period
        if ($period === 'all') {
            $filename = 'laporan_penjualan_semua_data_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        } else {
            $startDateFormatted = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : 'unknown';
            $endDateFormatted = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : 'unknown';
            $filename = 'laporan_penjualan_' . $startDateFormatted . '_' . $endDateFormatted . '.xlsx';
        }

        return Excel::download(new SalesReportExport($startDate, $endDate, $paymentMethod, $period), $filename);
    }

    private function exportStockToExcel()
    {
        $filename = 'laporan_stok_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new StockReportExport(), $filename);
    }

    private function exportStatisticsToExcel()
    {
        $filename = 'laporan_statistik_' . Carbon::now()->format('Y') . '.xlsx';

        return Excel::download(new StatisticsReportExport(), $filename);
    }

    private function exportStatisticsToCSV()
    {
        // Get statistics data
        $monthlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $topMedicines = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
        ->orderByDesc('total_quantity')
        ->limit(20)
        ->get();

        $paymentMethodStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('payment_method')
        ->get();

        $filename = 'laporan_statistik_' . Carbon::now()->format('Y') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($monthlyStats, $topMedicines, $paymentMethodStats) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Monthly Statistics
            fputcsv($file, ['']);
            fputcsv($file, ['STATISTIK BULANAN']);
            fputcsv($file, ['Bulan', 'Total Penjualan', 'Total Pendapatan', 'Rata-rata per Transaksi']);

            foreach ($monthlyStats as $stat) {
                $monthNames = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $monthName = $monthNames[$stat->month] ?? 'Tidak Diketahui';
                $avgPerTransaction = $stat->total_sales > 0 ? $stat->total_revenue / $stat->total_sales : 0;

                fputcsv($file, [
                    $monthName,
                    $stat->total_sales,
                    $stat->total_revenue,
                    round($avgPerTransaction, 0)
                ]);
            }

            // Top Medicines
            fputcsv($file, ['']);
            fputcsv($file, ['TOP 20 OBAT TERLARIS']);
            fputcsv($file, ['No', 'Nama Obat', 'Kode', 'Total Terjual', 'Total Pendapatan']);

            foreach ($topMedicines as $index => $medicine) {
                fputcsv($file, [
                    $index + 1,
                    $medicine->name,
                    $medicine->code,
                    $medicine->total_quantity,
                    $medicine->total_revenue
                ]);
            }

            // Payment Method Stats
            fputcsv($file, ['']);
            fputcsv($file, ['STATISTIK METODE PEMBAYARAN']);
            fputcsv($file, ['Metode Pembayaran', 'Total Transaksi', 'Total Pendapatan']);

            foreach ($paymentMethodStats as $payment) {
                fputcsv($file, [
                    ucfirst($payment->payment_method),
                    $payment->total_sales,
                    $payment->total_revenue
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // PDF Export methods
    private function exportToPDF($startDate, $endDate, $paymentMethod = null, $period = null)
    {
        $query = Sale::with(['user', 'saleDetails.medicine']);

        // Apply date filters only if not 'all' period and dates are provided
        if ($period !== 'all' && $startDate && $endDate) {
            // Convert string dates to Carbon instances
            if (is_string($startDate)) {
                $startDate = Carbon::parse($startDate)->startOfDay();
            }
            if (is_string($endDate)) {
                $endDate = Carbon::parse($endDate)->endOfDay();
            }

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply payment method filter if specified
        if ($paymentMethod && $paymentMethod !== '') {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_amount');

        // Get daily stats
        $dailyStatsQuery = Sale::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $startDate && $endDate) {
            $dailyStatsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($paymentMethod && $paymentMethod !== '') {
            $dailyStatsQuery->where('payment_method', $paymentMethod);
        }

        $dailyStats = $dailyStatsQuery->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get payment method stats
        $paymentMethodStatsQuery = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $startDate && $endDate) {
            $paymentMethodStatsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($paymentMethod && $paymentMethod !== '') {
            $paymentMethodStatsQuery->where('payment_method', $paymentMethod);
        }

        $paymentMethodStats = $paymentMethodStatsQuery->groupBy('payment_method')
            ->get();

        $data = compact('sales', 'totalSales', 'totalRevenue', 'startDate', 'endDate', 'dailyStats', 'paymentMethodStats', 'period', 'paymentMethod');

        $pdf = PDF::loadView('reports.pdf.sales', $data);
        $pdf->setPaper('a4', 'portrait');

        // Generate filename based on period
        if ($period === 'all') {
            $filename = 'laporan_penjualan_semua_data_' . Carbon::now()->format('Y-m-d') . '.pdf';
        } else {
            $startDateFormatted = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : 'unknown';
            $endDateFormatted = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : 'unknown';
            $filename = 'laporan_penjualan_' . $startDateFormatted . '_' . $endDateFormatted . '.pdf';
        }

        return $pdf->download($filename);
    }

    private function exportStockToPDF()
    {
        $medicines = Medicine::with(['category', 'brand', 'unit'])
            ->orderBy('stock', 'asc')
            ->get();

        $lowStockMedicines = Medicine::where('stock', '<', 10)->get();
        $outOfStockMedicines = Medicine::where('stock', 0)->get();
        $expiringMedicines = Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))->get();

        $stockStats = [
            'total_medicines' => Medicine::count(),
            'low_stock' => $lowStockMedicines->count(),
            'out_of_stock' => $outOfStockMedicines->count(),
            'expiring_soon' => $expiringMedicines->count(),
            'total_stock_value' => Medicine::sum(DB::raw('stock * price'))
        ];

        $data = compact('medicines', 'lowStockMedicines', 'outOfStockMedicines', 'expiringMedicines', 'stockStats');

        $pdf = PDF::loadView('reports.pdf.stock', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'laporan_stok_' . Carbon::now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportStatisticsToPDF()
    {
        // Get statistics data
        $monthlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $topMedicines = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
        ->orderByDesc('total_quantity')
        ->limit(20)
        ->get();

        $paymentMethodStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('payment_method')
        ->get();

        $data = compact('monthlyStats', 'topMedicines', 'paymentMethodStats');

        $pdf = PDF::loadView('reports.pdf.statistics', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'laporan_statistik_' . Carbon::now()->format('Y') . '.pdf';

        return $pdf->download($filename);
    }

    // Print methods
    public function printSalesReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $paymentMethod = $request->get('payment_method');
        $period = $request->get('period');

        // Handle period-based date filtering
        if (!$startDate && !$endDate && $period) {
            $dateRange = $this->getDateRangeFromPeriod($period);
            $startDate = $dateRange['start'];
            $endDate = $dateRange['end'];
        }

        // Build query with proper relationships
        $query = Sale::with(['user', 'saleDetails.medicine']);

        // Apply date filters only if not 'all' period and dates are provided
        if ($period !== 'all' && $startDate && $endDate) {
            // Convert string dates to Carbon instances
            if (is_string($startDate)) {
                $startDate = Carbon::parse($startDate)->startOfDay();
            }
            if (is_string($endDate)) {
                $endDate = Carbon::parse($endDate)->endOfDay();
            }

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply payment method filter if specified
        if ($paymentMethod && $paymentMethod !== '') {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        // Calculate accurate totals
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_amount');
        $totalItems = $sales->sum(function ($sale) {
            return $sale->saleDetails->sum('quantity');
        });

        // Daily statistics
        $dailyStatsQuery = Sale::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $startDate && $endDate) {
            $dailyStatsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($paymentMethod && $paymentMethod !== '') {
            $dailyStatsQuery->where('payment_method', $paymentMethod);
        }

        $dailyStats = $dailyStatsQuery->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method statistics
        $paymentMethodStatsQuery = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $startDate && $endDate) {
            $paymentMethodStatsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($paymentMethod && $paymentMethod !== '') {
            $paymentMethodStatsQuery->where('payment_method', $paymentMethod);
        }

        $paymentMethodStats = $paymentMethodStatsQuery->groupBy('payment_method')
            ->get();

        // Top medicines
        $topMedicinesQuery = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id');

        if ($period !== 'all' && $startDate && $endDate) {
            $topMedicinesQuery->whereBetween('sales.created_at', [$startDate, $endDate]);
        }

        if ($paymentMethod && $paymentMethod !== '') {
            $topMedicinesQuery->where('sales.payment_method', $paymentMethod);
        }

        $topMedicines = $topMedicinesQuery->groupBy('medicines.id', 'medicines.name', 'medicines.code')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $data = compact(
            'sales', 'totalSales', 'totalRevenue', 'totalItems',
            'startDate', 'endDate', 'dailyStats', 'paymentMethodStats',
            'topMedicines', 'period', 'paymentMethod'
        );

        return view('reports.print.sales', $data);
    }

    private function getDateRangeFromPeriod($period)
    {
        $now = Carbon::now();

        switch ($period) {
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
            case 'all':
                return [
                    'start' => null,
                    'end' => null
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }

    public function printStockReport(Request $request)
    {
        $medicines = Medicine::with(['category', 'brand', 'unit'])
            ->orderBy('stock', 'asc')
            ->get();

        $lowStockMedicines = Medicine::where('stock', '<', 10)->get();
        $outOfStockMedicines = Medicine::where('stock', 0)->get();
        $expiringMedicines = Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))->get();

        $stockStats = [
            'total_medicines' => Medicine::count(),
            'low_stock' => $lowStockMedicines->count(),
            'out_of_stock' => $outOfStockMedicines->count(),
            'expiring_soon' => $expiringMedicines->count(),
            'total_stock_value' => Medicine::sum(DB::raw('stock * price'))
        ];

        $data = compact('medicines', 'lowStockMedicines', 'outOfStockMedicines', 'expiringMedicines', 'stockStats');

        return view('reports.print.stock', $data);
    }

    public function printStatisticsReport(Request $request)
    {
        $period = $request->get('period', 'this_year');
        $dateRange = $this->getDateRangeFromPeriod($period);

        // Statistik penjualan berdasarkan periode
        $monthlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $dateRange['start'] && $dateRange['end']) {
            $monthlyStats->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $monthlyStats = $monthlyStats->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top selling medicines untuk periode yang dipilih
        $topMedicines = SaleDetail::selectRaw('
            medicines.name,
            medicines.code,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id');

        if ($period !== 'all' && $dateRange['start'] && $dateRange['end']) {
            $topMedicines->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $topMedicines = $topMedicines->groupBy('medicines.id', 'medicines.name', 'medicines.code')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();

        // Sales by payment method untuk periode yang dipilih
        $paymentMethodStats = Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ');

        if ($period !== 'all' && $dateRange['start'] && $dateRange['end']) {
            $paymentMethodStats->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $paymentMethodStats = $paymentMethodStats->groupBy('payment_method')->get();

        // Statistik tahunan (selalu tampilkan semua tahun)
        $yearlyStats = Sale::selectRaw('
            YEAR(created_at) as year,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

        // Statistik kategori obat untuk periode yang dipilih
        $categoryStats = SaleDetail::selectRaw('
            categories.name as category_name,
            SUM(sale_details.quantity) as total_quantity,
            SUM(sale_details.subtotal) as total_revenue
        ')
        ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->join('sales', 'sale_details.sale_id', '=', 'sales.id');

        if ($period !== 'all' && $dateRange['start'] && $dateRange['end']) {
            $categoryStats->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $categoryStats = $categoryStats->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $data = compact('monthlyStats', 'topMedicines', 'paymentMethodStats', 'yearlyStats', 'categoryStats', 'period');

        return view('reports.print.statistics', $data);
    }
}
