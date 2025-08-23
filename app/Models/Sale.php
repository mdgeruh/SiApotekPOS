<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'notes',
        'is_archived',
        'archive_reason',
        'archived_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:0',
        'paid_amount' => 'decimal:0',
        'change_amount' => 'decimal:0',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    /**
     * Scope untuk penjualan yang aktif (tidak diarsipkan)
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope untuk penjualan yang diarsipkan
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
