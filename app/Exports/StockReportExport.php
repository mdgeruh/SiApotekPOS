<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StockReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Medicine::with(['category', 'brand', 'unit'])
            ->orderBy('stock', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function map($medicine): array
    {
        // Tentukan status stok
        $stockStatus = '';
        if ($medicine->stock == 0) {
            $stockStatus = 'Habis';
        } elseif ($medicine->stock <= 10) {
            $stockStatus = 'Menipis';
        } else {
            $stockStatus = 'Tersedia';
        }

        // Hitung total nilai stok
        $totalValue = $medicine->stock * $medicine->price;

        // Format expired date
        $expiredDate = $medicine->expired_date ? 
            Carbon::parse($medicine->expired_date)->format('d/m/Y') : 'N/A';

        return [
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
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row number
        $highestRow = $sheet->getHighestRow();
        
        $styles = [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];

        // Apply number formatting to price and total value columns (G, H)
        for ($row = 2; $row <= $highestRow; $row++) {
            $styles[$row] = [
                'G' => ['numberFormat' => ['formatCode' => '#,##0']], // Harga
                'H' => ['numberFormat' => ['formatCode' => '#,##0']], // Total Nilai Stok
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Kode Obat
            'B' => 30,  // Nama Obat
            'C' => 20,  // Kategori
            'D' => 20,  // Brand
            'E' => 10,  // Stok
            'F' => 15,  // Unit
            'G' => 20,  // Harga
            'H' => 25,  // Total Nilai Stok
            'I' => 20,  // Expired Date
            'J' => 15,  // Status Stok
            'K' => 25,  // Manufacturer
        ];
    }
}
