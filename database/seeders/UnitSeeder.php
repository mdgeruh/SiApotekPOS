<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('units')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE units AUTO_INCREMENT = 1');

        $units = [
            [
                'name' => 'Tablet',
                'abbreviation' => 'tab',
                'description' => 'Satuan tablet obat',
                'is_active' => true
            ],
            [
                'name' => 'Kapsul',
                'abbreviation' => 'kap',
                'description' => 'Satuan kapsul obat',
                'is_active' => true
            ],
            [
                'name' => 'Botol',
                'abbreviation' => 'bot',
                'description' => 'Satuan botol obat cair',
                'is_active' => true
            ],
            [
                'name' => 'Ampul',
                'abbreviation' => 'amp',
                'description' => 'Satuan ampul obat injeksi',
                'is_active' => true
            ],
            [
                'name' => 'Vial',
                'abbreviation' => 'vial',
                'description' => 'Satuan vial obat injeksi',
                'is_active' => true
            ],
            [
                'name' => 'Tube',
                'abbreviation' => 'tube',
                'description' => 'Satuan tube salep/krim',
                'is_active' => true
            ],
            [
                'name' => 'Sachet',
                'abbreviation' => 'sct',
                'description' => 'Satuan sachet obat bubuk',
                'is_active' => true
            ],
            [
                'name' => 'Strip',
                'abbreviation' => 'strip',
                'description' => 'Satuan strip obat tablet/kapsul',
                'is_active' => true
            ],
            [
                'name' => 'Pcs',
                'abbreviation' => 'pcs',
                'description' => 'Satuan pieces untuk berbagai jenis obat',
                'is_active' => true
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'box',
                'description' => 'Satuan box kemasan obat',
                'is_active' => true
            ]
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('Unit seeder berhasil dibuat!');
    }
}
