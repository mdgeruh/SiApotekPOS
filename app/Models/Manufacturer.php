<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'country',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi dengan medicines
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
