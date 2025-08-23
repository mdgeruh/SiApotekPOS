<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoiceCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        DB::table('invoice_counters')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE invoice_counters AUTO_INCREMENT = 1');

        // Reset counter untuk hari ini jika ada
        InvoiceCounter::resetCounterForDate(Carbon::today());

        // Buat counter untuk beberapa hari terakhir (untuk testing)
        $dates = [
            Carbon::today()->subDays(2), // 2 hari yang lalu
            Carbon::today()->subDays(1), // 1 hari yang lalu
            Carbon::today(),              // Hari ini
        ];

        foreach ($dates as $date) {
            // Generate beberapa invoice untuk setiap tanggal (untuk testing)
            $counter = rand(1, 15); // Random counter antara 1-15

            InvoiceCounter::create([
                'date' => $date,
                'counter' => $counter,
            ]);

            $this->command->info("Created invoice counter for {$date->format('Y-m-d')} with counter: {$counter}");
        }

        $this->command->info('Invoice Counter seeder berhasil dibuat!');
    }
}
