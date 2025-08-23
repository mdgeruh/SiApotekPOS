<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('app_settings')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE app_settings AUTO_INCREMENT = 1');

        AppSetting::create([
            'app_name' => 'Apotek POS',
            'pharmacy_name' => 'Apotek Sejahtera',
            'address' => 'Jl. Contoh No. 123, Kota, Provinsi 12345',
            'phone' => '+62 812-3456-7890',
            'email' => 'info@apoteksejahtera.com',
            'website' => 'https://apoteksejahtera.com',
            'tax_number' => '12.345.678.9-123.000',
            'license_number' => 'SIPA-1234567890',
            'owner_name' => 'Dr. Nama Pemilik',
            'description' => 'Apotek terpercaya dengan pelayanan terbaik untuk kesehatan keluarga Anda.',
            'logo_path' => null,
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'maintenance_mode' => false,
        ]);

        $this->command->info('App Setting seeder berhasil dibuat!');
    }
}
