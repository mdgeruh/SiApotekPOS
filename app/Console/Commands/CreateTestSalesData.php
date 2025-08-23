<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use App\Models\User;
use Carbon\Carbon;

class CreateTestSalesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-sales-data {--count=10 : Jumlah transaksi yang akan dibuat}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat data dummy penjualan untuk testing laporan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        
        // Cek apakah ada data yang diperlukan
        $medicines = Medicine::all();
        $users = User::all();
        
        if ($medicines->isEmpty()) {
            $this->error('Tidak ada data obat di database. Jalankan seeder terlebih dahulu.');
            return 1;
        }
        
        if ($users->isEmpty()) {
            $this->error('Tidak ada data user di database. Jalankan seeder terlebih dahulu.');
            return 1;
        }
        
        $this->info("Membuat {$count} transaksi penjualan dummy...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        for ($i = 0; $i < $count; $i++) {
            // Buat transaksi dengan tanggal yang berbeda-beda
            $saleDate = Carbon::now()->subDays(rand(0, 30));
            
            $sale = Sale::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $users->random()->id,
                'total_amount' => 0, // Akan diupdate setelah detail dibuat
                'paid_amount' => 0, // Akan diupdate setelah detail dibuat
                'change_amount' => 0,
                'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                'notes' => 'Data testing - ' . $saleDate->format('d/m/Y'),
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);
            
            // Buat detail penjualan
            $detailCount = rand(1, 3); // 1-3 item per transaksi
            $totalAmount = 0;
            
            for ($j = 0; $j < $detailCount; $j++) {
                $medicine = $medicines->random();
                $quantity = rand(1, 5);
                $price = $medicine->price;
                $subtotal = $quantity * $price;
                
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                
                $totalAmount += $subtotal;
            }
            
            // Update total amount dan paid amount
            $sale->update([
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount, // Bayar tepat
                'change_amount' => 0,
            ]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Berhasil membuat {$count} transaksi penjualan dummy!");
        $this->info("Total transaksi di database: " . Sale::count());
        $this->info("Total detail penjualan: " . SaleDetail::count());
        
        return 0;
    }
}
