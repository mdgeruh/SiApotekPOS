<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Validator;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufacturers = Manufacturer::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $manufacturers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manufacturers.create');
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
            'name' => 'required|string|max:255|unique:manufacturers',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
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
            $manufacturer = Manufacturer::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Produsen berhasil ditambahkan',
                'data' => $manufacturer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produsen',
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
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json([
                'success' => false,
                'message' => 'Produsen tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $manufacturer
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
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json([
                'success' => false,
                'message' => 'Produsen tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $manufacturer
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
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json([
                'success' => false,
                'message' => 'Produsen tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:manufacturers,name,' . $id,
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
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
            $manufacturer->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Produsen berhasil diperbarui',
                'data' => $manufacturer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produsen',
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
        $manufacturer = Manufacturer::find($id);

        if (!$manufacturer) {
            return response()->json([
                'success' => false,
                'message' => 'Produsen tidak ditemukan'
            ], 404);
        }

        try {
            // Soft delete dengan mengubah is_active menjadi false
            $manufacturer->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Produsen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produsen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
