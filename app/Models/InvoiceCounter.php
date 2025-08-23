<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceCounter extends Model
{
    protected $fillable = ['date', 'counter'];

    protected $casts = [
        'date' => 'date',
        'counter' => 'integer',
    ];

    /**
     * Generate invoice number dengan format INV-YYYYMMDD-XXXX
     */
    public static function generateInvoiceNumber(): string
    {
        $today = Carbon::today();
        
        // Gunakan database transaction untuk memastikan atomicity
        return DB::transaction(function () use ($today) {
            // Cari atau buat counter untuk hari ini
            $counter = self::firstOrCreate(
                ['date' => $today],
                ['counter' => 0]
            );
            
            // Increment counter
            $counter->increment('counter');
            
            // Format: INV-YYYYMMDD-XXXX (4 digit dengan leading zeros)
            $dateFormat = $today->format('Ymd');
            $counterFormat = str_pad($counter->counter, 4, '0', STR_PAD_LEFT);
            
            return "INV-{$dateFormat}-{$counterFormat}";
        });
    }

    /**
     * Reset counter untuk tanggal tertentu (untuk testing)
     */
    public static function resetCounterForDate($date): void
    {
        self::where('date', $date)->delete();
    }

    /**
     * Get counter untuk tanggal tertentu
     */
    public static function getCounterForDate($date): int
    {
        $counter = self::where('date', $date)->first();
        return $counter ? $counter->counter : 0;
    }
}
