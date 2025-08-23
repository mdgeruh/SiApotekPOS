<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        $query = Sale::active()->with(['user', 'saleDetails.medicine']);

        // Filter berdasarkan invoice number
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        // Filter berdasarkan nama kasir
        if ($request->filled('cashier')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cashier . '%');
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            if ($request->payment_status === 'paid') {
                $query->whereRaw('paid_amount >= total_amount');
            } else {
                $query->whereRaw('paid_amount < total_amount');
            }
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        // Data untuk dropdown filter
        $paymentMethods = [
            'cash' => 'Tunai',
            'debit' => 'Debit',
            'credit' => 'Kartu Kredit',
            'transfer' => 'Transfer'
        ];

        return view('sales.index', compact('sales', 'paymentMethods'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        // Validasi akan ditangani oleh Livewire component
        return redirect()->route('sales.index');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'saleDetails.medicine.category', 'saleDetails.medicine.unit']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load(['saleDetails.medicine']);

        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,debit,credit,transfer'
        ]);

        $sale->update([
            'notes' => $request->notes,
            'payment_method' => $request->payment_method
        ]);

        return redirect()->route('sales.show', $sale)->with('success', 'Penjualan berhasil diperbarui!');
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Arsipkan penjualan (soft delete)
            $sale->update([
                'is_archived' => true,
                'archive_reason' => 'Diarsipkan oleh admin',
                'archived_at' => now()
            ]);

            DB::commit();

            return redirect()->route('sales.index')->with('success', "Penjualan '{$sale->invoice_number}' berhasil diarsipkan!");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Archive the specified sale
     */
    public function archive(Sale $sale, Request $request)
    {
        try {
            $reason = $request->input('archive_reason', 'Diarsipkan oleh admin');
            $sale->update([
                'is_archived' => true,
                'archive_reason' => $reason,
                'archived_at' => now()
            ]);

            return redirect()->route('sales.index')
                ->with('success', "Penjualan '{$sale->invoice_number}' berhasil diarsipkan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengarsipkan penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified archived sale
     */
    public function restore(Sale $sale)
    {
        try {
            $sale->update([
                'is_archived' => false,
                'archive_reason' => null,
                'archived_at' => null
            ]);

            return redirect()->route('sales.log')
                ->with('success', "Penjualan '{$sale->invoice_number}' berhasil dikembalikan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Display archived sales log
     */
    public function log()
    {
        $archivedSales = Sale::with(['user', 'saleDetails.medicine'])
            ->archived()
            ->orderBy('archived_at', 'desc')
            ->get();

        // Statistics
        $totalArchived = Sale::archived()->count();
        $totalActive = Sale::active()->count();
        $totalSales = Sale::count();

        return view('sales.log', compact('archivedSales', 'totalArchived', 'totalActive', 'totalSales'));
    }

    public function printReceipt(Sale $sale)
    {
        $sale->load(['user', 'saleDetails.medicine.unit']);

        $pdf = PDF::loadView('receipts.pdf', compact('sale'));

        return $pdf->stream('receipt-' . $sale->invoice_number . '.pdf');
    }

    public function printReceiptView(Sale $sale)
    {
        $sale->load(['user', 'saleDetails.medicine.unit']);

        return view('receipts.print', compact('sale'));
    }
}
