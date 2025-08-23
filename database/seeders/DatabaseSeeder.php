<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            UnitSeeder::class,
            ManufacturerSeeder::class,
            MedicineSeeder::class,
            InvoiceCounterSeeder::class, // Tambahkan ini sebelum SaleSeeder
            SaleSeeder::class,
            AppSettingSeeder::class,
        ]);
    }
}
