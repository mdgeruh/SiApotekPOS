<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $paymentMethod;
    protected $period;

    public function __construct($startDate = null, $endDate = null, $paymentMethod = null, $period = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->paymentMethod = $paymentMethod;
        $this->period = $period;
    }

    public function collection()
    {
        $query = Sale::with(['user', 'saleDetails.medicine']);

        // Apply date filters only if not 'all' period and dates are provided
        if ($this->period !== 'all' && $this->startDate && $this->endDate) {
            // Convert string dates to Carbon instances if needed
            $startDate = is_string($this->startDate) ? Carbon::parse($this->startDate)->startOfDay() : $this->startDate;
            $endDate = is_string($this->endDate) ? Carbon::parse($this->endDate)->endOfDay() : $this->endDate;

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply payment method filter if specified
        if ($this->paymentMethod && $this->paymentMethod !== '') {
            $query->where('payment_method', $this->paymentMethod);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function map($sale): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $sale->invoice_number,
            $sale->created_at->format('d/m/Y H:i:s'),
            $sale->user->name ?? 'N/A',
            ucfirst($sale->payment_method),
            $sale->saleDetails->count() ?? 0,
            $sale->total_amount, // Raw number for proper formatting
            $sale->paid_amount,   // Raw number for proper formatting
            $sale->change_amount, // Raw number for proper formatting
            $sale->notes ?? '-'
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

        // Apply number formatting to amount columns (G, H, I)
        for ($row = 2; $row <= $highestRow; $row++) {
            $styles[$row] = [
                'G' => ['numberFormat' => ['formatCode' => '#,##0']], // Total Amount
                'H' => ['numberFormat' => ['formatCode' => '#,##0']], // Paid Amount
                'I' => ['numberFormat' => ['formatCode' => '#,##0']], // Change Amount
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Invoice Number
            'C' => 20,  // Tanggal
            'D' => 20,  // Kasir
            'E' => 20,  // Metode Pembayaran
            'F' => 15,  // Total Items
            'G' => 20,  // Total Amount
            'H' => 20,  // Paid Amount
            'I' => 20,  // Change Amount
            'J' => 30,  // Notes
        ];
    }
}
