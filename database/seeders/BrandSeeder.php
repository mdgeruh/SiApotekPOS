<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('brands')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE brands AUTO_INCREMENT = 1');

        $brands = [
            [
                'name' => 'Kimia Farma',
                'description' => 'Perusahaan farmasi nasional terkemuka di Indonesia',
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Dexa Medica',
                'description' => 'Perusahaan farmasi yang fokus pada inovasi produk',
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Kalbe Farma',
                'description' => 'Perusahaan farmasi terbesar di Indonesia',
                'country' => 'Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Pfizer',
                'description' => 'Perusahaan farmasi multinasional terkemuka',
                'country' => 'Amerika Serikat',
                'is_active' => true,
            ],
            [
                'name' => 'Novartis',
                'description' => 'Perusahaan farmasi global yang inovatif',
                'country' => 'Swiss',
                'is_active' => true,
            ],
            [
                'name' => 'Roche',
                'description' => 'Perusahaan farmasi terkemuka di bidang onkologi',
                'country' => 'Swiss',
                'is_active' => true,
            ],
            [
                'name' => 'Merck',
                'description' => 'Perusahaan farmasi dan kimia terkemuka',
                'country' => 'Jerman',
                'is_active' => true,
            ],
            [
                'name' => 'Sanofi',
                'description' => 'Perusahaan farmasi global yang fokus pada kesehatan',
                'country' => 'Prancis',
                'is_active' => true,
            ],
            [
                'name' => 'GlaxoSmithKline',
                'description' => 'Perusahaan farmasi terkemuka di Inggris',
                'country' => 'Inggris',
                'is_active' => true,
            ],
            [
                'name' => 'AstraZeneca',
                'description' => 'Perusahaan farmasi yang fokus pada penyakit kardiovaskular',
                'country' => 'Inggris',
                'is_active' => true,
            ],
            [
                'name' => 'Johnson & Johnson',
                'description' => 'Perusahaan farmasi dan perawatan kesehatan terkemuka',
                'country' => 'Amerika Serikat',
                'is_active' => true,
            ],
            [
                'name' => 'Bayer',
                'description' => 'Perusahaan farmasi dan kimia terkemuka di Jerman',
                'country' => 'Jerman',
                'is_active' => true,
            ],
            [
                'name' => 'Eli Lilly',
                'description' => 'Perusahaan farmasi yang fokus pada diabetes dan onkologi',
                'country' => 'Amerika Serikat',
                'is_active' => true,
            ],
            [
                'name' => 'Bristol-Myers Squibb',
                'description' => 'Perusahaan farmasi yang fokus pada penyakit serius',
                'country' => 'Amerika Serikat',
                'is_active' => true,
            ],
            [
                'name' => 'Takeda',
                'description' => 'Perusahaan farmasi terkemuka di Jepang',
                'country' => 'Jepang',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('Brand seeder berhasil dibuat!');
    }
}
