<?php

namespace App\Services;

use App\Models\DailyCensus;
use App\Models\Room;
use Carbon\Carbon;

class CensusCalculatorService
{
    /**
     * Hitung Indikator Rawat Inap Rumah Sakit untuk periode/ruangan tertentu
     */
    public static function calculateIndicators(Room $room, int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $censuses = DailyCensus::where('room_id', $room->id)
            ->whereYear('census_date', $year)
            ->whereMonth('census_date', $month)
            ->get();

        $totalCareDays = $censuses->sum('care_days'); // Total Hari Perawatan (HP)
        $totalLengthOfStay = $censuses->sum('total_length_of_stay'); // Total Lama Dirawat (LD)
        $totalDischarges = $censuses->sum('discharges_count');
        $totalDeathsUnder48h = $censuses->sum('deaths_under_48h');
        $totalDeathsOver48h = $censuses->sum('deaths_over_48h');
        $totalDeaths = $totalDeathsUnder48h + $totalDeathsOver48h;
        $totalOut = $totalDischarges + $totalDeaths;

        $capacity = $room->capacity;

        // Formula Perhitungan Indikator RS
        // 1. BOR = (Total HP / (Jumlah TT * Jumlah Hari)) * 100%
        $bor = ($capacity > 0 && $daysInMonth > 0) 
            ? round(($totalCareDays / ($capacity * $daysInMonth)) * 100, 2) 
            : 0;

        // 2. ALOS = Total LD Pasien Keluar / Total Pasien Keluar (Hidup + Meninggal)
        $alos = ($totalOut > 0) ? round($totalLengthOfStay / $totalOut, 2) : 0;

        // 3. TOI = ((Jumlah TT * Jumlah Hari) - Total HP) / Total Pasien Keluar
        $toi = ($totalOut > 0 && $capacity > 0) 
            ? round((($capacity * $daysInMonth) - $totalCareDays) / $totalOut, 2) 
            : 0;

        // 4. BTO = Total Pasien Keluar / Jumlah TT
        $bto = ($capacity > 0) ? round($totalOut / $capacity, 2) : 0;

        // 5. NDR (Net Death Rate per 1000) = (Meninggal > 48 jam / Pasien Keluar) * 1000
        $ndr = ($totalOut > 0) ? round(($totalDeathsOver48h / $totalOut) * 1000, 2) : 0;

        // 6. GDR (Gross Death Rate per 1000) = (Total Meninggal / Pasien Keluar) * 1000
        $gdr = ($totalOut > 0) ? round(($totalDeaths / $totalOut) * 1000, 2) : 0;

        return [
            'room' => $room,
            'period' => $startDate->translatedFormat('F Y'),
            'days_in_month' => $daysInMonth,
            'total_care_days' => $totalCareDays,
            'total_length_of_stay' => $totalLengthOfStay,
            'total_discharges' => $totalDischarges,
            'total_deaths' => $totalDeaths,
            'total_out' => $totalOut,
            'bor' => $bor,
            'alos' => $alos,
            'toi' => $toi,
            'bto' => $bto,
            'ndr' => $ndr,
            'gdr' => $gdr,
        ];
    }
}
