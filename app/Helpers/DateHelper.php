<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal ke format Indonesia (hari bulan tahun)
     */
    public static function formatIndonesian($date, $includeTime = false)
    {
        if (!$date) return '-';

        $carbon = Carbon::parse($date);

        if ($includeTime) {
            return $carbon->isoFormat('D MMMM Y H:mm');
        }

        return $carbon->isoFormat('D MMMM Y');
    }

    /**
     * Format tanggal ke format DD/MM/YYYY
     */
    public static function formatDDMMYYYY($date, $includeTime = false)
    {
        if (!$date) return '-';

        $carbon = Carbon::parse($date);

        if ($includeTime) {
            return $carbon->format('d/m/Y H:i');
        }

        return $carbon->format('d/m/Y');
    }

    /**
     * Format tanggal untuk input date (Y-m-d)
     */
    public static function formatForInput($date)
    {
        if (!$date) return '';

        return Carbon::parse($date)->format('Y-m-d');
    }

    /**
     * Format tanggal untuk display dengan status
     */
    public static function formatWithStatus($date)
    {
        if (!$date) return '-';

        $carbon = Carbon::parse($date);
        $formatted = $carbon->format('d/m/Y');

        if ($carbon->isPast()) {
            return $formatted . ' (Kadaluarsa)';
        } elseif ($carbon->diffInDays(now(), false) <= 30) {
            return $formatted . ' (Segera Kadaluarsa)';
        }

        return $formatted;
    }

    /**
     * Format tanggal untuk display dengan status (format Indonesia)
     */
    public static function formatWithStatusIndonesian($date)
    {
        if (!$date) return '-';

        $carbon = Carbon::parse($date);
        $formatted = $carbon->isoFormat('D MMMM Y');

        if ($carbon->isPast()) {
            return $formatted . ' (Kadaluarsa)';
        } elseif ($carbon->diffInDays(now(), false) <= 30) {
            return $formatted . ' (Segera Kadaluarsa)';
        }

        return $formatted;
    }
}
