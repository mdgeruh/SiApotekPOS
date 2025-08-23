<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MedicineResource;
use App\Services\MedicineService;
use Illuminate\Http\Request;

class MedicineApiController extends BaseController
{
    protected $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    /**
     * Display a listing of medicines
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');
            $categoryId = $request->get('category_id');

            if ($categoryId) {
                $medicines = $this->medicineService->getByCategory($categoryId);
            } elseif ($search) {
                $medicines = $this->medicineService->search($search);
            } else {
                $medicines = $this->medicineService->getAllMedicines();
            }

            return $this->successResponse(
                MedicineResource::collection($medicines),
                'Medicines retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve medicines: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified medicine
     */
    public function show($id)
    {
        try {
            $medicine = $this->medicineService->find($id);

            if (!$medicine) {
                return $this->notFoundResponse('Medicine not found');
            }

            return $this->successResponse(
                new MedicineResource($medicine),
                'Medicine retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve medicine: ' . $e->getMessage());
        }
    }

    /**
     * Get available medicines for sales
     */
    public function available(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $medicines = $this->medicineService->getAvailableForSales($search);

            return $this->successResponse(
                MedicineResource::collection($medicines),
                'Available medicines retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve available medicines: ' . $e->getMessage());
        }
    }

    /**
     * Get low stock medicines
     */
    public function lowStock()
    {
        try {
            $medicines = $this->medicineService->getLowStock();

            return $this->successResponse(
                $medicines,
                'Low stock medicines retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve low stock medicines: ' . $e->getMessage());
        }
    }

    /**
     * Get expiring medicines
     */
    public function expiring()
    {
        try {
            $medicines = $this->medicineService->getExpiring();

            return $this->successResponse(
                $medicines,
                'Expiring medicines retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve expiring medicines: ' . $e->getMessage());
        }
    }
}
