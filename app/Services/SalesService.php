<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalesService
{
    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber(): string
    {
        $today = Carbon::now()->format('Ymd');
        $count = Sale::whereDate('created_at', Carbon::today())->count() + 1;
        return 'INV-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create new sale transaction
     */
    public function createSale(array $saleData, array $cartItems): Sale
    {
        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'invoice_number' => $saleData['invoice_number'],
                'user_id' => Auth::id(),
                'total_amount' => $saleData['total_amount'],
                'paid_amount' => $saleData['paid_amount'],
                'change_amount' => $saleData['change_amount'],
                'payment_method' => $saleData['payment_method'],
                'notes' => $saleData['notes'] ?? '',
            ]);

            foreach ($cartItems as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock
                $medicine = Medicine::find($item['medicine_id']);
                $medicine->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return $sale;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get sales with filters
     */
    public function getSalesWithFilters(array $filters = [])
    {
        $query = Sale::with(['user', 'saleDetails.medicine']);

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('invoice_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function ($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['payment_method']) && !empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get sales summary
     */
    public function getSalesSummary(array $filters = []): array
    {
        $query = Sale::query();

        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['payment_method']) && !empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        return [
            'total_sales' => $query->count(),
            'total_amount' => $query->sum('total_amount'),
            'total_paid' => $query->sum('paid_amount'),
            'total_change' => $query->sum('change_amount'),
        ];
    }

    /**
     * Get daily sales for chart
     */
    public function getDailySales(int $days = 30): array
    {
        $sales = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as total')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $counts = [];
        $totals = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $sale = $sales->where('date', $date)->first();

            $dates[] = Carbon::parse($date)->format('d/m');
            $counts[] = $sale ? $sale->count : 0;
            $totals[] = $sale ? (float) $sale->total : 0;
        }

        return [
            'labels' => $dates,
            'counts' => $counts,
            'totals' => $totals,
        ];
    }

    /**
     * Get top selling medicines
     */
    public function getTopSellingMedicines(int $limit = 10, array $filters = []): array
    {
        $query = SaleDetail::with(['medicine', 'sale'])
            ->selectRaw('medicine_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
            ->groupBy('medicine_id');

        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->whereDate('created_at', '>=', $filters['date_from']);
            });
        }

        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->whereDate('created_at', '<=', $filters['date_to']);
            });
        }

        return $query->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'medicine' => $item->medicine,
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                ];
            })
            ->toArray();
    }

    /**
     * Validate cart items
     */
    public function validateCartItems(array $cartItems): array
    {
        $errors = [];
        $validatedItems = [];

        foreach ($cartItems as $index => $item) {
            $medicine = Medicine::find($item['medicine_id']);

            if (!$medicine) {
                $errors[] = "Produk dengan ID {$item['medicine_id']} tidak ditemukan";
                continue;
            }

            if ($medicine->stock < $item['quantity']) {
                $errors[] = "Stok {$medicine->name} tidak mencukupi. Tersedia: {$medicine->stock}, Diminta: {$item['quantity']}";
                continue;
            }

            $validatedItems[] = [
                'medicine_id' => $medicine->id,
                'name' => $medicine->name,
                'code' => $medicine->code,
                'price' => $medicine->selling_price,
                'quantity' => $item['quantity'],
                'subtotal' => $medicine->selling_price * $item['quantity'],
                'stock' => $medicine->stock
            ];
        }

        return [
            'errors' => $errors,
            'validated_items' => $validatedItems
        ];
    }

    /**
     * Calculate cart total
     */
    public function calculateCartTotal(array $cartItems): float
    {
        return collect($cartItems)->sum('subtotal');
    }

    /**
     * Calculate change amount
     */
    public function calculateChangeAmount(float $paidAmount, float $totalAmount): float
    {
        return $paidAmount - $totalAmount;
    }
}
