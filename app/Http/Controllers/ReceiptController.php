<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Helpers\AppSettingHelper;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display receipt for thermal printing
     */
    public function print($saleId)
    {
        $sale = Sale::with(['saleDetails.medicine', 'user'])->findOrFail($saleId);

        // Get receipt settings
        $receiptSettings = AppSettingHelper::getReceiptSettings();

        // Set headers for thermal printer
        return response()->view('receipts.print', compact('sale', 'receiptSettings'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Frame-Options', 'DENY');
    }

    /**
     * Generate PDF receipt
     */
    public function pdf($saleId)
    {
        $sale = Sale::with(['saleDetails.medicine', 'user'])->findOrFail($saleId);

        // Get receipt settings
        $receiptSettings = AppSettingHelper::getReceiptSettings();

                        // Generate PDF using DomPDF dengan view khusus PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipts.pdf', compact('sale', 'receiptSettings'));

        // Set paper size yang lebih kecil untuk 1 halaman
        $pdf->setPaper([0, 0, 450, 650], 'portrait');

        // Generate filename
        $filename = 'struk-' . ($sale->invoice_number ?? 'TRX-' . date('YmdHis')) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get receipt data for API
     */
    public function getReceiptData($saleId)
    {
        $sale = Sale::with(['saleDetails.medicine', 'user'])->findOrFail($saleId);

        $receiptData = [
            'sale' => $sale,
            'receipt_settings' => AppSettingHelper::getReceiptSettings(),
            'formatted_currency' => AppSettingHelper::formatCurrency($sale->total_amount),
            'current_datetime' => now()->format('d/m/Y H:i:s'),
            'receipt_header' => AppSettingHelper::pharmacyName() ?: 'Apotek',
            'receipt_footer' => 'Terima kasih atas kunjungan Anda',
        ];

        return response()->json($receiptData);
    }
}
