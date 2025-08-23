<?php

namespace App\Helpers;

use App\Models\AppSetting;

class AppSettingHelper
{
    /**
     * Get app setting value
     */
    public static function get($key, $default = null)
    {
        return AppSetting::getSetting($key, $default);
    }

    /**
     * Set app setting value
     */
    public static function set($key, $value)
    {
        return AppSetting::setSetting($key, $value);
    }

    /**
     * Get app name
     */
    public static function appName()
    {
        return static::get('app_name', 'Apotek POS');
    }

    /**
     * Get pharmacy name
     */
    public static function pharmacyName()
    {
        return static::get('pharmacy_name', 'Apotek');
    }

    /**
     * Get address
     */
    public static function address()
    {
        return static::get('address', '');
    }

    /**
     * Get phone
     */
    public static function phone()
    {
        return static::get('phone', '');
    }

    /**
     * Get email
     */
    public static function email()
    {
        return static::get('email', '');
    }

    /**
     * Get website
     */
    public static function website()
    {
        return static::get('website', '');
    }

    /**
     * Get logo URL
     */
    public static function logoUrl()
    {
        $logoPath = static::get('logo_path');
        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            return asset('storage/' . $logoPath);
        }
        return null;
    }

    /**
     * Get logo as base64 for PDF
     */
    public static function logoBase64()
    {
        $logoPath = static::get('logo_path');
        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            $filePath = storage_path('app/public/' . $logoPath);
            $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
            $data = file_get_contents($filePath);
            $base64 = base64_encode($data);
            return 'data:image/' . $fileType . ';base64,' . $base64;
        }
        return null;
    }



    /**
     * Get favicon URL
     */
    public static function faviconUrl()
    {
        $faviconPath = static::get('favicon_path');
        if ($faviconPath && file_exists(storage_path('app/public/' . $faviconPath))) {
            return asset('storage/' . $faviconPath);
        }
        return null;
    }

    /**
     * Get favicon as base64 for PDF
     */
    public static function faviconBase64()
    {
        $faviconPath = static::get('favicon_path');
        if ($faviconPath && file_exists(storage_path('app/public/' . $faviconPath))) {
            $filePath = storage_path('app/public/' . $faviconPath);
            $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
            $data = file_get_contents($filePath);
            $base64 = base64_encode($data);
            return 'data:image/' . $fileType . ';base64,' . $base64;
        }
        return null;
    }

    /**
     * Get currency
     */
    public static function currency()
    {
        return static::get('currency', 'IDR');
    }

    /**
     * Get timezone
     */
    public static function timezone()
    {
        return static::get('timezone', 'Asia/Jakarta');
    }

    /**
     * Get page title based on current route
     */
    public static function pageTitle()
    {
        $routeName = request()->route()->getName();
        $pageTitles = [
            // Dashboard
            'dashboard' => 'Dashboard',

            // Profile & Settings
            'profile' => 'Profil Pengguna',
            'profile.update' => 'Update Profil',
            'profile.change-password' => 'Ubah Password',
            'profile.activities' => 'Aktivitas Pengguna',
            'settings' => 'Pengaturan',
            'settings.update' => 'Update Pengaturan',

            // Medicines
            'medicines.index' => 'Daftar Obat',
            'medicines.create' => 'Tambah Obat Baru',
            'medicines.show' => 'Detail Obat',
            'medicines.edit' => 'Edit Obat',
            'medicines.stock-update' => 'Update Stok Obat',

            // Categories
            'categories.index' => 'Daftar Kategori',
            'categories.create' => 'Tambah Kategori Baru',
            'categories.show' => 'Detail Kategori',
            'categories.edit' => 'Edit Kategori',

            // Brands
            'brands.index' => 'Daftar Merek',
            'brands.create' => 'Tambah Merek Baru',
            'brands.show' => 'Detail Merek',
            'brands.edit' => 'Edit Merek',

            // Sales
            'sales.index' => 'Daftar Penjualan',
            'sales.create' => 'Transaksi Baru',
            'sales.show' => 'Detail Penjualan',
            'sales.edit' => 'Edit Penjualan',
            'sales.print-receipt' => 'Cetak Struk',
            'sales.print-view' => 'Preview Struk',

            // Reports
            'reports.sales' => 'Laporan Penjualan',
            'reports.statistics' => 'Laporan Statistik',
            'reports.stock' => 'Laporan Stok',

            // Users (Admin)
            'users.index' => 'Manajemen Pengguna',
            'users.create' => 'Tambah Pengguna Baru',
            'users.show' => 'Detail Pengguna',
            'users.edit' => 'Edit Pengguna',

            // App Settings
            'app-settings.index' => 'Pengaturan Aplikasi',

            // Receipts
            'receipts.print' => 'Cetak Struk',
            'receipts.pdf' => 'Struk PDF',

            // Demo & Test
            'components.demo' => 'Demo Komponen',
            'thumbnail.demo' => 'Demo Thumbnail',
            'categories.test' => 'Test Kategori',
            'sales.test' => 'Test Penjualan',
            'simple.test' => 'Test Sederhana',
        ];

        return $pageTitles[$routeName] ?? 'Apotek POS';
    }

    /**
     * Get full page title with app name
     */
    public static function fullPageTitle()
    {
        $pageTitle = static::pageTitle();
        $appName = static::appName();

        if ($pageTitle === 'Dashboard') {
            return $appName;
        }

        return $pageTitle . ' - ' . $appName;
    }

    /**
     * Get breadcrumb data for current page
     */
    public static function breadcrumbs()
    {
        $routeName = request()->route()->getName();
        $breadcrumbs = [
            'dashboard' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => true]
            ],

            'profile' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Profil Pengguna', 'url' => '#', 'active' => true]
            ],

            'medicines.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Obat', 'url' => '#', 'active' => true]
            ],

            'medicines.create' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Obat', 'url' => route('medicines.index'), 'active' => false],
                ['title' => 'Tambah Obat Baru', 'url' => '#', 'active' => true]
            ],

            'medicines.show' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Obat', 'url' => route('medicines.index'), 'active' => false],
                ['title' => 'Detail Obat', 'url' => '#', 'active' => true]
            ],

            'medicines.edit' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Obat', 'url' => route('medicines.index'), 'active' => false],
                ['title' => 'Edit Obat', 'url' => '#', 'active' => true]
            ],

            'categories.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Kategori', 'url' => '#', 'active' => true]
            ],

            'categories.create' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Kategori', 'url' => route('categories.index'), 'active' => false],
                ['title' => 'Tambah Kategori Baru', 'url' => '#', 'active' => true]
            ],

            'categories.edit' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Kategori', 'url' => route('categories.index'), 'active' => false],
                ['title' => 'Edit Kategori', 'url' => '#', 'active' => true]
            ],

            'brands.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Merek', 'url' => '#', 'active' => true]
            ],

            'brands.create' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Merek', 'url' => route('brands.index'), 'active' => false],
                ['title' => 'Tambah Merek Baru', 'url' => '#', 'active' => true]
            ],

            'brands.edit' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Merek', 'url' => route('brands.index'), 'active' => false],
                ['title' => 'Edit Merek', 'url' => '#', 'active' => true]
            ],

            'sales.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Penjualan', 'url' => '#', 'active' => true]
            ],

            'sales.create' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Penjualan', 'url' => route('sales.index'), 'active' => false],
                ['title' => 'Transaksi Baru', 'url' => '#', 'active' => true]
            ],

            'sales.show' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Daftar Penjualan', 'url' => route('sales.index'), 'active' => false],
                ['title' => 'Detail Penjualan', 'url' => '#', 'active' => true]
            ],

            'reports.sales' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Laporan Penjualan', 'url' => '#', 'active' => true]
            ],

            'reports.statistics' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Laporan Statistik', 'url' => '#', 'active' => true]
            ],

            'reports.stock' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Laporan Stok', 'url' => '#', 'active' => true]
            ],

            'users.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Manajemen Pengguna', 'url' => '#', 'active' => true]
            ],

            'users.create' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Manajemen Pengguna', 'url' => route('users.index'), 'active' => false],
                ['title' => 'Tambah Pengguna Baru', 'url' => '#', 'active' => true]
            ],

            'users.edit' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Manajemen Pengguna', 'url' => route('users.index'), 'active' => false],
                ['title' => 'Edit Pengguna', 'url' => '#', 'active' => true]
            ],

            'app-settings.index' => [
                ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
                ['title' => 'Pengaturan Aplikasi', 'url' => '#', 'active' => true]
            ],
        ];

        return $breadcrumbs[$routeName] ?? [
            ['title' => 'Dashboard', 'url' => route('dashboard'), 'active' => false],
            ['title' => 'Halaman', 'url' => '#', 'active' => true]
        ];
    }

    /**
     * Get current page icon based on route
     */
    public static function pageIcon()
    {
        $routeName = request()->route()->getName();
        $icons = [
            'dashboard' => 'fas fa-tachometer-alt',
            'profile' => 'fas fa-user',
            'medicines.index' => 'fas fa-pills',
            'medicines.create' => 'fas fa-plus-circle',
            'medicines.show' => 'fas fa-eye',
            'medicines.edit' => 'fas fa-edit',
            'categories.index' => 'fas fa-tags',
            'categories.create' => 'fas fa-plus-circle',
            'categories.edit' => 'fas fa-edit',
            'brands.index' => 'fas fa-trademark',
            'brands.create' => 'fas fa-plus-circle',
            'brands.edit' => 'fas fa-edit',
            'sales.index' => 'fas fa-shopping-cart',
            'sales.create' => 'fas fa-plus-circle',
            'sales.show' => 'fas fa-eye',
            'reports.sales' => 'fas fa-chart-bar',
            'reports.statistics' => 'fas fa-chart-line',
            'reports.stock' => 'fas fa-boxes',
            'users.index' => 'fas fa-users',
            'users.create' => 'fas fa-user-plus',
            'users.edit' => 'fas fa-user-edit',
            'app-settings.index' => 'fas fa-cog',
        ];

        return $icons[$routeName] ?? 'fas fa-home';
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return static::get('maintenance_mode', false);
    }

    /**
     * Get all settings as array
     */
    public static function all()
    {
        $setting = AppSetting::first();
        return $setting ? $setting->toArray() : [];
    }

    /**
     * Get settings for receipt printing
     */
    public static function getReceiptSettings()
    {
        $setting = AppSetting::first();
        if (!$setting) {
            return [
                'pharmacy_name' => 'Apotek',
                'address' => 'Alamat tidak tersedia',
                'phone' => 'Telepon tidak tersedia',
                'email' => null,
                'website' => null,
                'tax_number' => 'NPWP tidak tersedia',
                'license_number' => 'No. Izin tidak tersedia',
                'owner_name' => 'Pemilik tidak tersedia',
                'currency' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'logo_path' => null,
            ];
        }

        return [
            'pharmacy_name' => $setting->pharmacy_name ?: 'Apotek',
            'address' => $setting->address ?: 'Alamat tidak tersedia',
            'phone' => $setting->phone ?: 'Telepon tidak tersedia',
            'email' => $setting->email,
            'website' => $setting->website,
            'tax_number' => $setting->tax_number ?: 'NPWP tidak tersedia',
            'license_number' => $setting->license_number ?: 'No. Izin tidak tersedia',
            'owner_name' => $setting->owner_name ?: 'Pemilik tidak tersedia',
            'currency' => $setting->currency ?: 'IDR',
            'timezone' => $setting->timezone ?: 'Asia/Jakarta',
            'logo_path' => $setting->logo_path,
        ];
    }

    /**
     * Format currency for receipt
     */
    public static function formatCurrency($amount)
    {
        $currency = self::currency();
        $formatted = number_format($amount, 0, ',', '.');

        switch ($currency) {
            case 'IDR':
                return 'Rp ' . $formatted;
            case 'USD':
                return '$' . $formatted;
            case 'EUR':
                return '€' . $formatted;
            default:
                return $formatted;
        }
    }

    /**
     * Get current date time in app timezone
     */
    public static function getCurrentDateTime()
    {
        $timezone = self::timezone() ?: 'Asia/Jakarta';
        $date = new \DateTime('now', new \DateTimeZone($timezone));
        return $date->format('d/m/Y H:i:s');
    }

    /**
     * Get receipt header text
     */
    public static function getReceiptHeader()
    {
        $settings = self::getReceiptSettings();
        $header = [];

        if ($settings['pharmacy_name']) {
            $header[] = strtoupper($settings['pharmacy_name']);
        }

        if ($settings['address']) {
            $header[] = $settings['address'];
        }

        if ($settings['phone']) {
            $header[] = 'Telp: ' . $settings['phone'];
        }

        return $header;
    }

    /**
     * Get receipt footer text
     */
    public static function getReceiptFooter()
    {
        $settings = self::getReceiptSettings();
        $footer = [];

        if ($settings['tax_number']) {
            $footer[] = 'NPWP: ' . $settings['tax_number'];
        }

        if ($settings['license_number']) {
            $footer[] = 'No. Izin: ' . $settings['license_number'];
        }

        if ($settings['owner_name']) {
            $footer[] = 'Pemilik: ' . $settings['owner_name'];
        }

        $footer[] = 'Terima kasih atas kepercayaan Anda';
        $footer[] = 'Semoga lekas sembuh';

        return $footer;
    }
}
