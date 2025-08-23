<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units',
            'abbreviation' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $unit = Unit::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil ditambahkan',
                'data' => $unit
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan satuan obat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan obat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $unit
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan obat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $unit
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan obat tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units,name,' . $id,
            'abbreviation' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $unit->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil diperbarui',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui satuan obat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Satuan obat tidak ditemukan'
            ], 404);
        }

        try {
            // Soft delete dengan mengubah is_active menjadi false
            $unit->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus satuan obat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
