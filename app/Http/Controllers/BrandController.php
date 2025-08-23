<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of brands
     */
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(15);
        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
        ]);

        try {
            Brand::create($validated);

            return redirect()->route('brands.index')
                ->with('success', 'Merk obat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan merk: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified brand
     */
    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        try {
            $brand->update($validated);

            return redirect()->route('brands.index')
                ->with('success', 'Merk obat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui merk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand)
    {
        try {
            // Check if brand has medicines
            if ($brand->medicines()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus merk yang masih memiliki obat!');
            }

            $brand->delete();

            return redirect()->route('brands.index')
                ->with('success', 'Merk obat berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus merk: ' . $e->getMessage());
        }
    }

    /**
     * Toggle brand status
     */
    public function toggleStatus(Brand $brand)
    {
        try {
            $brand->update(['is_active' => !$brand->is_active]);

            $status = $brand->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Merk obat berhasil {$status}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status merk: ' . $e->getMessage());
        }
    }
}
