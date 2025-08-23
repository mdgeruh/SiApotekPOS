<?php

namespace App\Repositories;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class MedicineRepository extends BaseRepository
{
    public function __construct(Medicine $model)
    {
        parent::__construct($model);
    }

    /**
     * Get medicines with category
     */
    public function getWithCategory()
    {
        return $this->model->with('category')->get();
    }

    /**
     * Get available medicines for sales
     */
    public function getAvailableForSales(string $search = '')
    {
        return $this->model->where('stock', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->get();
    }

    /**
     * Get low stock medicines
     */
    public function getLowStock(int $threshold = 10, int $limit = 5)
    {
        return $this->model->with(['unit', 'category'])
            ->where('stock', '<', $threshold)
            ->orderBy('stock', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get expiring medicines
     */
    public function getExpiring(int $days = 30, int $limit = 5)
    {
        return $this->model->where('expired_date', '<=', now()->addDays($days))
            ->select('name', 'code', 'expired_date', 'stock')
            ->orderBy('expired_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top selling medicines
     */
    public function getTopSelling(int $limit = 5)
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
     * Update medicine stock
     */
    public function updateStock(int $medicineId, int $quantity): bool
    {
        $medicine = $this->find($medicineId);
        if (!$medicine || $medicine->stock < $quantity) {
            return false;
        }

        $medicine->decrement('stock', $quantity);
        return true;
    }

    /**
     * Get medicines by category
     */
    public function getByCategory(int $categoryId)
    {
        return $this->model->where('category_id', $categoryId)
            ->with('category')
            ->get();
    }

    /**
     * Search medicines
     */
    public function search(string $query)
    {
        return $this->model->where('name', 'like', '%' . $query . '%')
            ->orWhere('code', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->with('category')
            ->get();
    }
}
