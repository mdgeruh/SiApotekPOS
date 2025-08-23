<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('categories')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

        DB::table('categories')->insert([
            [
                'name' => 'Antibiotik',
                'description' => 'Obat untuk mengatasi infeksi bakteri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Analgesik',
                'description' => 'Obat penghilang rasa sakit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Antipiretik',
                'description' => 'Obat penurun demam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vitamin',
                'description' => 'Suplemen vitamin dan mineral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Obat Luar',
                'description' => 'Obat untuk penggunaan luar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Category seeder berhasil dibuat!');
    }
}
