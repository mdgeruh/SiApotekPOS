<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\Unit;
use App\Services\MedicineService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller
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
        return view('medicines.index');
    }

    /**
     * Show the form for creating a new medicine
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $manufacturers = Manufacturer::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $code = $this->generateMedicineCode();

        return view('medicines.create', compact('categories', 'brands', 'manufacturers', 'units', 'code'));
    }

    /**
     * Store a newly created medicine
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:medicines,code|max:50',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit_id' => 'required|exists:units,id',
            'expired_date' => 'required|date|after:today',
        ]);

        // Set price field for backward compatibility
        $validated['price'] = $validated['selling_price'];

        try {
            $medicine = $this->medicineService->createMedicine($validated);

            return redirect()->route('medicines.index')
                ->with('success', 'Obat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan obat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified medicine
     */
    public function show(Request $request, Medicine $medicine)
    {
        $medicine->load(['category', 'brand', 'manufacturer', 'unit']);

        // Return JSON if requested via AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($medicine);
        }

        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine
     */
    public function edit(Medicine $medicine)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $manufacturers = Manufacturer::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        return view('medicines.edit', compact('medicine', 'categories', 'brands', 'manufacturers', 'units'));
    }

    /**
     * Update the specified medicine
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:medicines,code,' . $medicine->id . '|max:50',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit_id' => 'required|exists:units,id',
            'expired_date' => 'required|date',
        ]);

        // Set price field for backward compatibility
        $validated['price'] = $validated['selling_price'];

        try {
            $this->medicineService->updateMedicine($medicine, $validated);

            return redirect()->route('medicines.index')
                ->with('success', 'Obat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui obat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified medicine
     */
    public function destroy(Medicine $medicine)
    {
        try {
            $result = $this->medicineService->deleteMedicine($medicine);

            // Check if medicine was archived instead of deleted
            if ($medicine->fresh()->is_archived) {
                return redirect()->route('medicines.index')
                    ->with('success', "Obat '{$medicine->name}' berhasil diarsipkan karena memiliki riwayat penjualan!");
            }

            return redirect()->route('medicines.index')
                ->with('success', 'Obat berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }

    /**
     * Archive the specified medicine
     */
    public function archive(Medicine $medicine, Request $request)
    {
        try {
            $reason = $request->input('archive_reason', 'Diarsipkan oleh admin');
            $this->medicineService->archiveMedicine($medicine, $reason);

            return redirect()->route('medicines.index')
                ->with('success', "Obat '{$medicine->name}' berhasil diarsipkan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengarsipkan obat: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified archived medicine
     */
    public function restore(Medicine $medicine)
    {
        try {
            $this->medicineService->restoreMedicine($medicine);

            return redirect()->route('medicines.index')
                ->with('success', "Obat '{$medicine->name}' berhasil dikembalikan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan obat: ' . $e->getMessage());
        }
    }

    /**
     * Show archived medicines log
     */
    public function log()
    {
        $archivedMedicines = Medicine::with(['category', 'brand', 'manufacturer', 'unit'])
            ->where('is_archived', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeMedicinesCount = Medicine::where('is_archived', false)->count();
        $categoriesCount = \App\Models\Category::count();
        $expiredMedicinesCount = Medicine::where('expired_date', '<', now())->count();

        return view('medicines.log', compact(
            'archivedMedicines',
            'activeMedicinesCount',
            'categoriesCount',
            'expiredMedicinesCount'
        ));
    }

    /**
     * Show form to update stock for existing medicine
     */
    public function stockUpdateForm(Medicine $medicine)
    {
        $medicine->load(['category', 'brand', 'manufacturer', 'unit']);
        return view('medicines.stock-update', compact('medicine'));
    }

    /**
     * Update stock for existing medicine
     */
    public function stockUpdate(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'stock_change_type' => 'required|in:add,subtract',
            'stock_change' => 'required|numeric|min:0.01',
            'expired_date' => 'nullable|date|after:today',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock_note' => 'nullable|string|max:500',
        ]);

        try {
            // Calculate new stock
            $currentStock = $medicine->stock;
            if ($validated['stock_change_type'] === 'add') {
                $newStock = $currentStock + $validated['stock_change'];
            } else {
                $newStock = max(0, $currentStock - $validated['stock_change']);
            }

            // Update medicine data
            $updateData = ['stock' => $newStock];

            if (!empty($validated['expired_date'])) {
                $updateData['expired_date'] = $validated['expired_date'];
            }

            if (!empty($validated['purchase_price'])) {
                $updateData['purchase_price'] = $validated['purchase_price'];
            }

            $medicine->update($updateData);

            // Log stock change (you can create a separate table for this)
            // For now, we'll just update the medicine

            return redirect()->route('medicines.index')
                ->with('success', 'Stok obat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui stok obat: ' . $e->getMessage());
        }
    }

    /**
     * Show form to add stock for medicine
     */
    public function addStockForm(Request $request)
    {
        $medicineId = $request->query('medicine_id');
        $medicine = null;

        if ($medicineId) {
            $medicine = Medicine::with(['category', 'brand', 'manufacturer', 'unit'])->find($medicineId);
        }

        $medicines = Medicine::orderBy('name')->get();

        return view('medicines.add-stock', compact('medicine', 'medicines'));
    }

    /**
     * Add stock for medicine
     */
    public function addStock(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'stock_to_add' => 'required|numeric|min:0.01',
            'expired_date' => 'nullable|date|after:today',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock_note' => 'nullable|string|max:500',
        ]);

        try {
            $medicine = Medicine::findOrFail($validated['medicine_id']);

            // Calculate new stock
            $newStock = $medicine->stock + $validated['stock_to_add'];

            // Update medicine data
            $updateData = ['stock' => $newStock];

            if (!empty($validated['expired_date'])) {
                $updateData['expired_date'] = $validated['expired_date'];
            }

            if (!empty($validated['purchase_price'])) {
                $updateData['purchase_price'] = $validated['purchase_price'];
            }

            $medicine->update($updateData);

            return redirect()->route('medicines.show', $medicine)
                ->with('success', 'Stok berhasil ditambahkan! Stok baru: ' . $newStock);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }



    /**
     * Generate unique medicine code
     */
    private function generateMedicineCode()
    {
        $prefix = 'OBT';
        $year = date('Y');
        $month = date('m');

        $lastMedicine = Medicine::where('code', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('code', 'desc')
            ->first();

        if ($lastMedicine) {
            $lastNumber = (int) substr($lastMedicine->code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
