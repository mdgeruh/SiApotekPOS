<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class MedicineService
{

    /**
     * Get all medicines with category (no pagination)
     */
    public function getAllMedicines()
    {
        return Medicine::with('category')->get();
    }

    /**
     * Get medicines with search (no pagination)
     */
    public function getMedicinesWithSearch(string $search = '')
    {
        $query = Medicine::with(['category', 'brand', 'unit', 'manufacturer']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Find medicine by ID
     */
    public function find(int $id): ?Medicine
    {
        return Medicine::find($id);
    }

    /**
     * Get all categories for medicine forms
     */
    public function getAllCategories()
    {
        return Category::all();
    }

    /**
     * Create new medicine
     */
    public function createMedicine(array $data): Medicine
    {
        return Medicine::create($data);
    }

    /**
     * Update medicine
     */
    public function updateMedicine(Medicine $medicine, array $data): bool
    {
        return $medicine->update($data);
    }

    /**
     * Delete medicine
     */
    public function deleteMedicine(Medicine $medicine): bool
    {
        // Check if medicine has sales records
        if ($medicine->saleDetails()->exists()) {
            // Auto-archive if medicine has sales records
            return $this->archiveMedicine($medicine, 'Otomatis diarsipkan saat penghapusan karena memiliki riwayat penjualan');
        }

        return $medicine->delete();
    }

    /**
     * Archive medicine (soft delete)
     */
    public function archiveMedicine(Medicine $medicine, string $reason = null): bool
    {
        try {
            $medicine->update([
                'is_archived' => true,
                'archive_reason' => $reason,
                'archived_at' => now()
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengarsipkan obat: ' . $e->getMessage());
        }
    }

    /**
     * Restore archived medicine
     */
    public function restoreMedicine(Medicine $medicine): bool
    {
        try {
            $medicine->update(['is_archived' => false]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengembalikan obat: ' . $e->getMessage());
        }
    }

    /**
     * Get medicines with low stock
     */
    public function getLowStockMedicines(int $threshold = 10, int $limit = 5)
    {
        return Medicine::with(['unit', 'category'])
            ->where('stock', '<', $threshold)
            ->orderBy('stock', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get expiring medicines
     */
    public function getExpiringMedicines(int $days = 30, int $limit = 5)
    {
        return Medicine::where('expired_date', '<=', now()->addDays($days))
            ->select('name', 'code', 'expired_date', 'stock')
            ->orderBy('expired_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get available medicines for sales
     */
    public function getAvailableMedicines(string $search = '')
    {
        return Medicine::where('stock', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->get();
    }

    /**
     * Update medicine stock
     */
    public function updateStock(int $medicineId, int $quantity): bool
    {
        $medicine = Medicine::find($medicineId);
        if (!$medicine) {
            return false;
        }

        if ($medicine->stock < $quantity) {
            return false;
        }

        $medicine->decrement('stock', $quantity);
        return true;
    }

    /**
     * Get top selling medicines
     */
    public function getTopSellingMedicines(int $limit = 5)
    {
        return DB::table('sale_details')
            ->join('medicines', 'sale_details.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', 'medicines.code', DB::raw('SUM(sale_details.quantity) as total_sold'))
            ->groupBy('medicines.id', 'medicines.name', 'medicines.code')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get medicines by category
     */
    public function getByCategory(int $categoryId)
    {
        return Medicine::where('category_id', $categoryId)
            ->with('category')
            ->get();
    }

    /**
     * Search medicines
     */
    public function search(string $query)
    {
        return Medicine::where('name', 'like', '%' . $query . '%')
            ->orWhere('code', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->with('category')
            ->get();
    }

    /**
     * Get available medicines for sales (alias for getAvailableMedicines)
     */
    public function getAvailableForSales(string $search = '')
    {
        return $this->getAvailableMedicines($search);
    }

    /**
     * Get low stock medicines (alias for getLowStockMedicines)
     */
    public function getLowStock(int $threshold = 10, int $limit = 5)
    {
        return $this->getLowStockMedicines($threshold, $limit);
    }

    /**
     * Get expiring medicines (alias for getExpiringMedicines)
     */
    public function getExpiring(int $days = 30, int $limit = 5)
    {
        return $this->getExpiringMedicines($days, $limit);
    }

    /**
     * Get medicine statistics for dashboard
     */
    public function getMedicineStatistics()
    {
        return [
            'total' => Medicine::count(),
            'stock_safe' => Medicine::where('stock', '>', 20)->count(),
            'stock_low' => Medicine::where('stock', '<=', 20)->where('stock', '>', 0)->count(),
            'stock_out' => Medicine::where('stock', '<=', 0)->count(),
            'stock_expired' => Medicine::where('expired_date', '<', now())->count(),
            'stock_expiring_soon' => Medicine::where('expired_date', '<=', now()->addDays(30))
                ->where('expired_date', '>', now())
                ->count(),
        ];
    }
}
