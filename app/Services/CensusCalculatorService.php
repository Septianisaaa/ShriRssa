<?php

namespace App\Services;

use App\Models\DailyCensus;
use App\Models\Room;
use App\Models\Admission;
use App\Models\PatientTransfer;
use Carbon\Carbon;

class CensusCalculatorService
{
    /**
     * Sinkronisasi & hitung ulang data Sensus Harian (DailyCensus) secara real-time
     */
    public static function syncRoomDate(Room $room, string $date): DailyCensus
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // 1. Pasien Awal (Pasien aktif di ruangan pada jam 00:00:00 tanggal tersebut)
        $initialPatients = Admission::where('current_room_id', $room->id)
            ->where('admission_date', '<', $start)
            ->where(function ($q) use ($start) {
                $q->whereNull('discharge_date')
                  ->orWhere('discharge_date', '>=', $start);
            })
            ->count();

        // 2. Pasien Masuk (MRS) pada tanggal ini
        $admissionsCount = Admission::where('initial_room_id', $room->id)
            ->whereBetween('admission_date', [$start, $end])
            ->count();

        // 3. Mutasi Pindahan Masuk (Transfer In) pada tanggal ini
        $transfersInCount = PatientTransfer::where('to_room_id', $room->id)
            ->where('status', 'accepted')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('accepted_at', [$start, $end])
                  ->orWhereBetween('transfer_date', [$start, $end]);
            })
            ->count();

        // 4. Mutasi Pindahan Keluar (Transfer Out) pada tanggal ini
        $transfersOutCount = PatientTransfer::where('from_room_id', $room->id)
            ->where('status', 'accepted')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('accepted_at', [$start, $end])
                  ->orWhereBetween('transfer_date', [$start, $end]);
            })
            ->count();

        // 5. Pasien Keluar Hidup (KRS) pada tanggal ini
        $dischargesCount = Admission::where('current_room_id', $room->id)
            ->where('status', 'discharged')
            ->whereBetween('discharge_date', [$start, $end])
            ->whereNotIn('discharge_condition', ['deceased_under_48h', 'deceased_over_48h', 'deceased'])
            ->count();

        // 6. Pasien Meninggal < 48 Jam pada tanggal ini
        $deathsUnder48h = Admission::where('current_room_id', $room->id)
            ->where('status', 'deceased')
            ->where('discharge_condition', 'deceased_under_48h')
            ->whereBetween('discharge_date', [$start, $end])
            ->count();

        // 7. Pasien Meninggal >= 48 Jam pada tanggal ini
        $deathsOver48h = Admission::where('current_room_id', $room->id)
            ->where('status', 'deceased')
            ->where('discharge_condition', 'deceased_over_48h')
            ->whereBetween('discharge_date', [$start, $end])
            ->count();

        // 8. Pasien Sisa (Akhir Hari)
        $remainingPatients = $initialPatients + $admissionsCount + $transfersInCount - $transfersOutCount - $dischargesCount - $deathsUnder48h - $deathsOver48h;
        $remainingPatients = max(0, $remainingPatients);

        // 9. Pasien Masuk & Keluar Hari Yang Sama (ODC) pada tanggal ini
        $sameDayDischargesCount = Admission::where('current_room_id', $room->id)
            ->whereIn('status', ['discharged', 'deceased'])
            ->whereBetween('admission_date', [$start, $end])
            ->whereBetween('discharge_date', [$start, $end])
            ->count();

        // Hari Perawatan (HP) = Pasien Sisa + ODC
        $careDays = $remainingPatients + $sameDayDischargesCount;

        // 10. Total Lama Dirawat (LD) Pasien Keluar/Meninggal pada tanggal ini
        $totalLengthOfStay = (int) Admission::where('current_room_id', $room->id)
            ->whereIn('status', ['discharged', 'deceased'])
            ->whereBetween('discharge_date', [$start, $end])
            ->sum('length_of_stay');

        return DailyCensus::updateOrCreate(
            ['room_id' => $room->id, 'census_date' => $date],
            [
                'initial_patients' => max(0, $initialPatients),
                'admissions_count' => $admissionsCount,
                'transfers_in_count' => $transfersInCount,
                'transfers_out_count' => $transfersOutCount,
                'discharges_count' => $dischargesCount,
                'deaths_under_48h' => $deathsUnder48h,
                'deaths_over_48h' => $deathsOver48h,
                'remaining_patients' => $remainingPatients,
                'care_days' => max(0, $careDays),
                'total_length_of_stay' => $totalLengthOfStay,
            ]
        );
    }

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

