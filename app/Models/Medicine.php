<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'category_id',
        'brand_id',
        'manufacturer_id',
        'unit_id',
        'price',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock',
        'expired_date',
        'is_archived',
        'archive_reason',
        'archived_at',
    ];

    protected $casts = [
        'expired_date' => 'date',
        'price' => 'decimal:0',
        'purchase_price' => 'decimal:0',
        'selling_price' => 'decimal:0',
        'min_stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function sales()
    {
        return $this->hasManyThrough(Sale::class, SaleDetail::class);
    }

    /**
     * Scope untuk obat yang aktif (tidak diarsipkan)
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope untuk obat yang diarsipkan
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
