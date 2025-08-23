<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'pharmacy_name',
        'address',
        'phone',
        'email',
        'website',
        'tax_number',
        'license_number',
        'owner_name',
        'description',
        'logo_path',
        'favicon_path',
        'currency',
        'timezone',
        'maintenance_mode',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Get setting value by key
     */
    public static function getSetting($key, $default = null)
    {
        $setting = static::first();
        return $setting ? $setting->$key : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setSetting($key, $value)
    {
        $setting = static::firstOrCreate([]);
        $setting->update([$key => $value]);
        return $setting;
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings()
    {
        $setting = static::first();
        return $setting ? $setting->toArray() : [];
    }
}
