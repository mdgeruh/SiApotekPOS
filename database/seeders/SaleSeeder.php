<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Medicine;
use App\Models\User;
use App\Models\InvoiceCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data existing
        DB::table('sale_details')->delete();
        DB::table('sales')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE sales AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE sale_details AUTO_INCREMENT = 1');

        // Ambil data yang diperlukan
        $medicines = Medicine::all();
        $users = User::all();

        if ($medicines->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Tidak ada data medicines atau users. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Data penjualan yang realistis dengan tanggal yang bervariasi
        $salesData = [
            // 2 tahun lalu (2023)
            [
                'date' => '2023-01-15',
                'payment_method' => 'cash',
                'total_amount' => 125000,
                'items' => [
                    ['medicine_id' => 1, 'quantity' => 2, 'price' => 45000, 'subtotal' => 90000],
                    ['medicine_id' => 3, 'quantity' => 1, 'price' => 35000, 'subtotal' => 35000],
                ]
            ],
            [
                'date' => '2023-02-20',
                'payment_method' => 'transfer',
                'total_amount' => 89000,
                'items' => [
                    ['medicine_id' => 2, 'quantity' => 3, 'price' => 25000, 'subtotal' => 75000],
                    ['medicine_id' => 4, 'quantity' => 1, 'price' => 14000, 'subtotal' => 14000],
                ]
            ],
            [
                'date' => '2023-03-10',
                'payment_method' => 'card',
                'total_amount' => 156000,
                'items' => [
                    ['medicine_id' => 1, 'quantity' => 1, 'price' => 45000, 'subtotal' => 45000],
                    ['medicine_id' => 5, 'quantity' => 2, 'price' => 55000, 'subtotal' => 110000],
                ]
            ],
            [
                'date' => '2023-04-05',
                'payment_method' => 'cash',
                'total_amount' => 78000,
                'items' => [
                    ['medicine_id' => 6, 'quantity' => 2, 'price' => 39000, 'subtotal' => 78000],
                ]
            ],
            [
                'date' => '2023-05-12',
                'payment_method' => 'transfer',
                'total_amount' => 112000,
                'items' => [
                    ['medicine_id' => 2, 'quantity' => 2, 'price' => 25000, 'subtotal' => 50000],
                    ['medicine_id' => 7, 'quantity' => 1, 'price' => 62000, 'subtotal' => 62000],
                ]
            ],

            // 1 tahun lalu (2024)
            [
                'date' => '2024-01-08',
                'payment_method' => 'cash',
                'total_amount' => 134000,
                'items' => [
                    ['medicine_id' => 1, 'quantity' => 2, 'price' => 45000, 'subtotal' => 90000],
                    ['medicine_id' => 8, 'quantity' => 1, 'price' => 44000, 'subtotal' => 44000],
                ]
            ],
            [
                'date' => '2024-02-14',
                'payment_method' => 'card',
                'total_amount' => 98000,
                'items' => [
                    ['medicine_id' => 3, 'quantity' => 1, 'price' => 35000, 'subtotal' => 35000],
                    ['medicine_id' => 9, 'quantity' => 2, 'price' => 31500, 'subtotal' => 63000],
                ]
            ],
            [
                'date' => '2024-03-22',
                'payment_method' => 'cash',
                'total_amount' => 167000,
                'items' => [
                    ['medicine_id' => 4, 'quantity' => 1, 'price' => 14000, 'subtotal' => 14000],
                    ['medicine_id' => 10, 'quantity' => 2, 'price' => 76500, 'subtotal' => 153000],
                ]
            ],
            [
                'date' => '2024-04-18',
                'payment_method' => 'transfer',
                'total_amount' => 89000,
                'items' => [
                    ['medicine_id' => 5, 'quantity' => 1, 'price' => 55000, 'subtotal' => 55000],
                    ['medicine_id' => 11, 'quantity' => 1, 'price' => 34000, 'subtotal' => 34000],
                ]
            ],
            [
                'date' => '2024-05-30',
                'payment_method' => 'cash',
                'total_amount' => 145000,
                'items' => [
                    ['medicine_id' => 6, 'quantity' => 1, 'price' => 39000, 'subtotal' => 39000],
                    ['medicine_id' => 12, 'quantity' => 1, 'price' => 67000, 'subtotal' => 67000],
                    ['medicine_id' => 13, 'quantity' => 1, 'price' => 39000, 'subtotal' => 39000],
                ]
            ],
            [
                'date' => '2024-06-15',
                'payment_method' => 'card',
                'total_amount' => 123000,
                'items' => [
                    ['medicine_id' => 7, 'quantity' => 1, 'price' => 62000, 'subtotal' => 62000],
                    ['medicine_id' => 13, 'quantity' => 2, 'price' => 30500, 'subtotal' => 61000],
                ]
            ],
            [
                'date' => '2024-07-08',
                'payment_method' => 'cash',
                'total_amount' => 178000,
                'items' => [
                    ['medicine_id' => 8, 'quantity' => 2, 'price' => 44000, 'subtotal' => 88000],
                    ['medicine_id' => 14, 'quantity' => 1, 'price' => 90000, 'subtotal' => 90000],
                ]
            ],
            [
                'date' => '2024-08-25',
                'payment_method' => 'transfer',
                'total_amount' => 156000,
                'items' => [
                    ['medicine_id' => 9, 'quantity' => 3, 'price' => 28000, 'subtotal' => 84000],
                    ['medicine_id' => 15, 'quantity' => 1, 'price' => 72000, 'subtotal' => 72000],
                ]
            ],

            // Tahun ini (2025)
            [
                'date' => '2025-01-10',
                'payment_method' => 'cash',
                'total_amount' => 189000,
                'items' => [
                    ['medicine_id' => 10, 'quantity' => 2, 'price' => 62500, 'subtotal' => 125000],
                    ['medicine_id' => 16, 'quantity' => 1, 'price' => 64000, 'subtotal' => 64000],
                ]
            ],
            [
                'date' => '2025-02-18',
                'payment_method' => 'card',
                'total_amount' => 134000,
                'items' => [
                    ['medicine_id' => 11, 'quantity' => 2, 'price' => 34000, 'subtotal' => 68000],
                    ['medicine_id' => 17, 'quantity' => 1, 'price' => 66000, 'subtotal' => 66000],
                ]
            ],
            [
                'date' => '2025-03-05',
                'payment_method' => 'cash',
                'total_amount' => 145000,
                'items' => [
                    ['medicine_id' => 12, 'quantity' => 1, 'price' => 67000, 'subtotal' => 67000],
                    ['medicine_id' => 18, 'quantity' => 2, 'price' => 39000, 'subtotal' => 78000],
                ]
            ],
            [
                'date' => '2025-04-12',
                'payment_method' => 'transfer',
                'total_amount' => 167000,
                'items' => [
                    ['medicine_id' => 13, 'quantity' => 3, 'price' => 30500, 'subtotal' => 91500],
                    ['medicine_id' => 19, 'quantity' => 1, 'price' => 75500, 'subtotal' => 75500],
                ]
            ],
            [
                'date' => '2025-05-20',
                'payment_method' => 'cash',
                'total_amount' => 198000,
                'items' => [
                    ['medicine_id' => 14, 'quantity' => 1, 'price' => 90000, 'subtotal' => 90000],
                    ['medicine_id' => 20, 'quantity' => 2, 'price' => 54000, 'subtotal' => 108000],
                ]
            ],
            [
                'date' => '2025-06-08',
                'payment_method' => 'card',
                'total_amount' => 123000,
                'items' => [
                    ['medicine_id' => 15, 'quantity' => 1, 'price' => 72000, 'subtotal' => 72000],
                    ['medicine_id' => 1, 'quantity' => 1, 'price' => 45000, 'subtotal' => 45000],
                ]
            ],
            [
                'date' => Carbon::today()->format('Y-m-d'),
                'payment_method' => 'cash',
                'total_amount' => 178000,
                'items' => [
                    ['medicine_id' => 16, 'quantity' => 2, 'price' => 64000, 'subtotal' => 128000],
                    ['medicine_id' => 2, 'quantity' => 1, 'price' => 25000, 'subtotal' => 25000],
                ]
            ],
            [
                'date' => Carbon::today()->format('Y-m-d'),
                'payment_method' => 'transfer',
                'total_amount' => 145000,
                'items' => [
                    ['medicine_id' => 17, 'quantity' => 1, 'price' => 66000, 'subtotal' => 66000],
                    ['medicine_id' => 3, 'quantity' => 2, 'price' => 35000, 'subtotal' => 70000],
                ]
            ],
            [
                'date' => '2025-08-15',
                'payment_method' => 'cash',
                'total_amount' => 167000,
                'items' => [
                    ['medicine_id' => 18, 'quantity' => 2, 'price' => 39000, 'subtotal' => 78000],
                    ['medicine_id' => 4, 'quantity' => 1, 'price' => 14000, 'subtotal' => 14000],
                ]
            ],
            [
                'date' => '2025-08-12',
                'payment_method' => 'card',
                'total_amount' => 189000,
                'items' => [
                    ['medicine_id' => 19, 'quantity' => 1, 'price' => 75500, 'subtotal' => 75500],
                    ['medicine_id' => 5, 'quantity' => 2, 'price' => 55000, 'subtotal' => 110000],
                ]
            ],
        ];

        $this->command->info('Membuat data penjualan...');

        foreach ($salesData as $saleData) {
            // Buat sale dengan invoice number yang sesuai format
            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber($saleData['date']),
                'user_id' => $users->random()->id,
                'total_amount' => $saleData['total_amount'],
                'paid_amount' => $saleData['total_amount'], // Bayar pas
                'change_amount' => 0, // Tidak ada kembalian
                'payment_method' => $saleData['payment_method'],
                'notes' => $this->getRandomNotes(),
                'created_at' => Carbon::parse($saleData['date'])->addHours(rand(8, 18))->addMinutes(rand(0, 59)),
                'updated_at' => Carbon::parse($saleData['date'])->addHours(rand(8, 18))->addMinutes(rand(0, 59)),
            ]);

            // Buat sale details
            foreach ($saleData['items'] as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->command->info("Created sale: {$sale->invoice_number} - Rp " . number_format($saleData['total_amount'], 0, ',', '.'));
        }

        $this->command->info('Data penjualan berhasil dibuat!');
    }

    /**
     * Generate invoice number dengan format INV-YYYYMMDD-XXXX sesuai dengan InvoiceCounter
     */
    private function generateInvoiceNumber($date): string
    {
        $dateObj = Carbon::parse($date);
        $dateFormat = $dateObj->format('Ymd');

        // Generate counter untuk tanggal tersebut
        $counter = InvoiceCounter::firstOrCreate(
            ['date' => $dateObj],
            ['counter' => 0]
        );

        // Increment counter
        $counter->increment('counter');

        // Format: INV-YYYYMMDD-XXXX (4 digit dengan leading zeros)
        $counterFormat = str_pad($counter->counter, 4, '0', STR_PAD_LEFT);

        return "INV-{$dateFormat}-{$counterFormat}";
    }

    private function getRandomNotes()
    {
        $notes = [
            'Pembayaran tunai',
            'Pembayaran kartu',
            'Pembayaran transfer',
            'Resep dokter',
            'Obat bebas',
            'Obat keras',
            'Antibiotik',
            'Vitamin',
            'Obat flu',
            'Obat sakit kepala',
            'Obat maag',
            'Obat diabetes',
            'Obat darah tinggi',
            'Obat kolesterol',
            'Obat asam urat',
            'Obat rematik',
            'Obat kulit',
            'Obat mata',
            'Obat telinga',
            'Obat hidung',
        ];

        return $notes[array_rand($notes)];
    }
}
