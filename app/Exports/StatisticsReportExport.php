<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\SaleDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StatisticsReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Statistik Bulanan' => new MonthlyStatisticsSheet(),
            'Obat Terlaris' => new TopMedicinesSheet(),
            'Metode Pembayaran' => new PaymentMethodSheet(),
        ];
    }
}

class MonthlyStatisticsSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Sale::selectRaw('
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
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Total Penjualan',
            'Total Pendapatan',
            'Rata-rata per Transaksi'
        ];
    }

    public function map($stat): array
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $monthName = $monthNames[$stat->month] ?? 'Tidak Diketahui';
        $avgPerTransaction = $stat->total_sales > 0 ? $stat->total_revenue / $stat->total_sales : 0;

        return [
            $monthName,
            $stat->total_sales,
            $stat->total_revenue, // Raw number for proper formatting
            round($avgPerTransaction, 0)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row number
        $highestRow = $sheet->getHighestRow();
        
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
        ];

        // Apply number formatting to revenue and average columns (C, D)
        for ($row = 2; $row <= $highestRow; $row++) {
            $styles[$row] = [
                'C' => ['numberFormat' => ['formatCode' => '#,##0']], // Total Pendapatan
                'D' => ['numberFormat' => ['formatCode' => '#,##0']], // Rata-rata per Transaksi
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Bulan
            'B' => 20,  // Total Penjualan
            'C' => 25,  // Total Pendapatan
            'D' => 25,  // Rata-rata per Transaksi
        ];
    }
}

class TopMedicinesSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return SaleDetail::selectRaw('
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
    }

    public function headings(): array
    {
        return [
            'Nama Obat',
            'Kode',
            'Total Terjual',
            'Total Pendapatan'
        ];
    }

    public function map($medicine): array
    {
        return [
            $medicine->name,
            $medicine->code,
            $medicine->total_quantity,
            $medicine->total_revenue // Raw number for proper formatting
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row number
        $highestRow = $sheet->getHighestRow();
        
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47']
                ]
            ],
        ];

        // Apply number formatting to revenue column (D)
        for ($row = 2; $row <= $highestRow; $row++) {
            $styles[$row] = [
                'D' => ['numberFormat' => ['formatCode' => '#,##0']], // Total Pendapatan
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,  // Nama Obat
            'B' => 15,  // Kode
            'C' => 20,  // Total Terjual
            'D' => 25,  // Total Pendapatan
        ];
    }
}

class PaymentMethodSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Sale::selectRaw('
            payment_method,
            COUNT(*) as total_sales,
            SUM(total_amount) as total_revenue
        ')
        ->groupBy('payment_method')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Metode Pembayaran',
            'Total Transaksi',
            'Total Pendapatan'
        ];
    }

    public function map($payment): array
    {
        return [
            ucfirst($payment->payment_method),
            $payment->total_sales,
            $payment->total_revenue // Raw number for proper formatting
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row number
        $highestRow = $sheet->getHighestRow();
        
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFC000']
                ]
            ],
        ];

        // Apply number formatting to revenue column (C)
        for ($row = 2; $row <= $highestRow; $row++) {
            $styles[$row] = [
                'C' => ['numberFormat' => ['formatCode' => '#,##0']], // Total Pendapatan
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Metode Pembayaran
            'B' => 20,  // Total Transaksi
            'C' => 25,  // Total Pendapatan
        ];
    }
}
