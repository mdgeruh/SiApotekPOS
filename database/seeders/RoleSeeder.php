<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('roles')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 1');

        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'Administrator sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kasir',
                'description' => 'Kasir untuk transaksi penjualan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manajemen_obat',
                'description' => 'Manajemen stok dan data obat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Role seeder berhasil dibuat!');
    }
}
