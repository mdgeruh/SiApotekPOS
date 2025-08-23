<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $medicineId = $this->route('medicine');
        $uniqueRule = $medicineId
            ? "unique:medicines,code,{$medicineId}"
            : 'unique:medicines,code';

        return [
            'name' => 'required|string|max:255',
            'code' => "required|string|{$uniqueRule}|max:50",
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'unit_id' => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0|max:999999999',
            'selling_price' => 'required|numeric|min:0|max:999999999',
            'price' => 'nullable|numeric|min:0|max:999999999',
            'stock' => 'required|integer|min:0|max:999999',
            'min_stock' => 'required|integer|min:0|max:999999',
            'expired_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama obat harus diisi.',
            'name.max' => 'Nama obat maksimal 255 karakter.',
            'code.required' => 'Kode obat harus diisi.',
            'code.unique' => 'Kode obat sudah digunakan.',
            'code.max' => 'Kode obat maksimal 50 karakter.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'brand_id.exists' => 'Brand yang dipilih tidak valid.',
            'manufacturer_id.exists' => 'Produsen yang dipilih tidak valid.',
            'unit_id.required' => 'Satuan harus dipilih.',
            'unit_id.exists' => 'Satuan yang dipilih tidak valid.',
            'purchase_price.required' => 'Harga beli harus diisi.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.min' => 'Harga beli minimal 0.',
            'purchase_price.max' => 'Harga beli maksimal 999.999.999.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa angka.',
            'selling_price.min' => 'Harga jual minimal 0.',
            'selling_price.max' => 'Harga jual maksimal 999.999.999.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal 0.',
            'price.max' => 'Harga maksimal 999.999.999.',
            'stock.required' => 'Stok harus diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok minimal 0.',
            'stock.max' => 'Stok maksimal 999.999.',
            'min_stock.required' => 'Stok minimum harus diisi.',
            'min_stock.integer' => 'Stok minimum harus berupa angka bulat.',
            'min_stock.min' => 'Stok minimum minimal 0.',
            'min_stock.max' => 'Stok minimum maksimal 999.999.',
            'expired_date.required' => 'Tanggal kadaluarsa harus diisi.',
            'expired_date.date' => 'Tanggal kadaluarsa harus berupa tanggal.',
            'expired_date.after' => 'Tanggal kadaluarsa harus setelah hari ini.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama obat',
            'code' => 'kode obat',
            'category_id' => 'kategori',
            'brand_id' => 'brand',
            'manufacturer_id' => 'produsen',
            'unit_id' => 'satuan',
            'purchase_price' => 'harga beli',
            'selling_price' => 'harga jual',
            'price' => 'harga',
            'stock' => 'stok',
            'min_stock' => 'stok minimum',
            'expired_date' => 'tanggal kadaluarsa',
            'description' => 'deskripsi',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $purchasePrice = $this->input('purchase_price');
            $sellingPrice = $this->input('selling_price');

            if ($purchasePrice && $sellingPrice && $sellingPrice <= $purchasePrice) {
                $validator->errors()->add('selling_price', 'Harga jual harus lebih tinggi dari harga beli.');
            }
        });
    }
}
