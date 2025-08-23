<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Manufacturer;
use App\Models\Unit;
use Carbon\Carbon;

class CreateTestMedicineData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-medicine-data {--count=20 : Jumlah obat yang akan dibuat}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat data dummy obat dengan berbagai status stok dan tanggal expired untuk testing laporan stok';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        
        // Cek apakah ada data yang diperlukan
        $categories = Category::all();
        $brands = Brand::all();
        $manufacturers = Manufacturer::all();
        $units = Unit::all();
        
        if ($categories->isEmpty() || $brands->isEmpty() || $manufacturers->isEmpty() || $units->isEmpty()) {
            $this->error('Tidak ada data kategori, brand, manufacturer, atau unit di database. Jalankan seeder terlebih dahulu.');
            return 1;
        }
        
        $this->info("Membuat {$count} data obat dummy dengan berbagai status...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        for ($i = 0; $i < $count; $i++) {
            // Buat obat dengan status stok yang berbeda-beda
            $stockStatus = $i % 5; // 0-4 untuk berbagai status
            
            $stock = 0;
            $expiredDate = null;
            
            switch ($stockStatus) {
                case 0: // Habis
                    $stock = 0;
                    $expiredDate = null;
                    break;
                case 1: // Menipis
                    $stock = rand(1, 9);
                    $expiredDate = null;
                    break;
                case 2: // Akan expired
                    $stock = rand(5, 20);
                    $expiredDate = Carbon::now()->addDays(rand(1, 30));
                    break;
                case 3: // Sudah expired
                    $stock = rand(5, 20);
                    $expiredDate = Carbon::now()->subDays(rand(1, 30));
                    break;
                case 4: // Tersedia
                    $stock = rand(10, 50);
                    $expiredDate = Carbon::now()->addDays(rand(31, 365));
                    break;
            }
            
            $medicine = Medicine::create([
                'code' => 'MED-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'name' => 'Obat Test ' . ($i + 1) . ' - ' . $this->getMedicineType($stockStatus),
                'description' => 'Deskripsi obat test untuk status: ' . $this->getStatusDescription($stockStatus),
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'manufacturer_id' => $manufacturers->random()->id,
                'unit_id' => $units->random()->id,
                'stock' => $stock,
                'price' => rand(5000, 100000),
                'expired_date' => $expiredDate,
                'min_stock' => 10,
                'max_stock' => 100,
            ]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Berhasil membuat {$count} data obat dummy!");
        $this->info("Total obat di database: " . Medicine::count());
        
        // Tampilkan statistik
        $this->info("\nStatistik Stok:");
        $this->info("- Habis: " . Medicine::where('stock', 0)->count());
        $this->info("- Menipis: " . Medicine::where('stock', '<', 10)->where('stock', '>', 0)->count());
        $this->info("- Akan Expired: " . Medicine::where('expired_date', '<=', Carbon::now()->addDays(30))->where('expired_date', '>', Carbon::now())->count());
        $this->info("- Sudah Expired: " . Medicine::where('expired_date', '<=', Carbon::now())->count());
        $this->info("- Tersedia: " . Medicine::where('stock', '>=', 10)->where(function($q) {
            $q->whereNull('expired_date')->orWhere('expired_date', '>', Carbon::now()->addDays(30));
        })->count());
        
        return 0;
    }
    
    private function getMedicineType($status)
    {
        $types = ['Paracetamol', 'Amoxicillin', 'Ibuprofen', 'Omeprazole', 'Cetirizine'];
        return $types[$status % count($types)];
    }
    
    private function getStatusDescription($status)
    {
        $descriptions = [
            'Stok habis',
            'Stok menipis',
            'Akan expired',
            'Sudah expired',
            'Stok tersedia'
        ];
        return $descriptions[$status];
    }
}
