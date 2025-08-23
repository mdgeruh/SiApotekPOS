<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'country',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
