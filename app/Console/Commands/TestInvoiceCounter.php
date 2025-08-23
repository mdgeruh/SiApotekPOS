<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvoiceCounter;
use Carbon\Carbon;

class TestInvoiceCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:test {--count=5 : Jumlah invoice yang akan di-generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test generate invoice number dengan InvoiceCounter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');

        $this->info("Generating {$count} invoice numbers...");
        $this->newLine();

        $invoiceNumbers = [];

        for ($i = 1; $i <= $count; $i++) {
            $invoiceNumber = InvoiceCounter::generateInvoiceNumber();
            $invoiceNumbers[] = $invoiceNumber;

            $this->line("Invoice {$i}: {$invoiceNumber}");
        }

        $this->newLine();
        $this->info("Generated {$count} invoice numbers successfully!");

        // Tampilkan counter untuk hari ini
        $today = Carbon::today();
        $todayCounter = InvoiceCounter::getCounterForDate($today);
        $this->info("Today's counter: {$todayCounter}");

        // Tampilkan semua counter yang ada
        $this->newLine();
        $this->info("All invoice counters:");
        $counters = InvoiceCounter::orderBy('date', 'desc')->get();

        $headers = ['Date', 'Counter', 'Created At'];
        $rows = $counters->map(function ($counter) {
            return [
                $counter->date->format('Y-m-d'),
                $counter->counter,
                $counter->created_at->format('Y-m-d H:i:s')
            ];
        })->toArray();

        $this->table($headers, $rows);

        return Command::SUCCESS;
    }
}
