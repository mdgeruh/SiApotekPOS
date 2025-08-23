<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\DB;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('manufacturers')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE manufacturers AUTO_INCREMENT = 1');

        $manufacturers = [
            [
                'name' => 'PT Kimia Farma',
                'description' => 'Perusahaan farmasi nasional terbesar di Indonesia',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@kimiafarma.co.id',
                'website' => 'https://www.kimiafarma.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Dexa Medica',
                'description' => 'Perusahaan farmasi terkemuka dengan fokus pada inovasi',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@dexamedica.com',
                'website' => 'https://www.dexamedica.com',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Kalbe Farma',
                'description' => 'Perusahaan farmasi terbesar di Asia Tenggara',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@kalbe.co.id',
                'website' => 'https://www.kalbe.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Tempo Scan Pacific',
                'description' => 'Perusahaan farmasi dengan produk-produk berkualitas',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@temposcan.co.id',
                'website' => 'https://www.temposcan.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Soho Global Health',
                'description' => 'Perusahaan farmasi dengan fokus pada kesehatan global',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@soho.co.id',
                'website' => 'https://www.soho.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Mersifarma Tirmaku',
                'description' => 'Perusahaan farmasi dengan produk-produk tradisional',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@mersifarma.co.id',
                'website' => 'https://www.mersifarma.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Indofarma',
                'description' => 'Perusahaan farmasi dengan fokus pada obat generik',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-xxx-xxxx',
                'email' => 'info@indofarma.co.id',
                'website' => 'https://www.indofarma.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ],
            [
                'name' => 'PT Phapros',
                'description' => 'Perusahaan farmasi dengan produk-produk berkualitas tinggi',
                'address' => 'Semarang, Indonesia',
                'phone' => '+62-24-xxx-xxxx',
                'email' => 'info@phapros.co.id',
                'website' => 'https://www.phapros.co.id',
                'country' => 'Indonesia',
                'is_active' => true
            ]
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }

        $this->command->info('Manufacturer seeder berhasil dibuat!');
    }
}
