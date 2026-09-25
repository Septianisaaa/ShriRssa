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
     * Sinkronisasi & hitung ulang Sensus Harian berdasarkan
     * posisi pasien pada tanggal sensus.
     *
     * LOGIKA:
     * - Pasien MRS baru       -> Admissions
     * - Pasien pindahan masuk -> PatientTransfer ke ruangan tujuan
     * - Pasien pindahan keluar -> PatientTransfer dari ruangan asal
     * - Pasien sisa           -> pasien yang memang berada di ruangan
     *                            pada awal hari
     * - LD                    -> tetap dihitung dari MRS pertama
     */
    public static function syncRoomDate(Room $room, string $date): DailyCensus
{
    $start = Carbon::parse($date)->startOfDay();
    $end = Carbon::parse($date)->endOfDay();

    /*
    |--------------------------------------------------------------------------
    | 1. PASIEN AWAL
    |--------------------------------------------------------------------------
    | Pasien yang sudah berada di ruangan sebelum tanggal sensus
    | dan belum KRS / meninggal sebelum tanggal tersebut.
    |
    */
    $initialPatients = Admission::where('current_room_id', $room->id)
        ->where('admission_date', '<', $start)
        ->where(function ($q) use ($start) {
            $q->whereNull('discharge_date')
                ->orWhere('discharge_date', '>=', $start);
        })
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 2. PASIEN BARU / MRS
    |--------------------------------------------------------------------------
    | Hanya pasien yang benar-benar MRS pertama kali di ruangan tersebut.
    |
    | Pasien pindahan TIDAK dihitung sebagai MRS.
    |
    */
    $admissionsCount = Admission::where('initial_room_id', $room->id)
        ->whereBetween('admission_date', [$start, $end])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 3. PASIEN PINDAHAN MASUK / TRANSFER IN
    |--------------------------------------------------------------------------
    | Pasien yang berasal dari ruangan lain dan diterima di ruangan ini.
    |
    */
    $transfersInCount = PatientTransfer::where('to_room_id', $room->id)
    ->where('status', 'accepted')
    ->whereBetween('transfer_date', [$start, $end])
    ->count();

    /*
    |--------------------------------------------------------------------------
    | 4. PASIEN PINDAHAN KELUAR / TRANSFER OUT
    |--------------------------------------------------------------------------
    | Pasien yang dipindahkan dari ruangan ini ke ruangan lain.
    |
    */
    $transfersOutCount = PatientTransfer::where('from_room_id', $room->id)
    ->where('status', 'accepted')
    ->whereBetween('transfer_date', [$start, $end])
    ->count();


    /*
    |--------------------------------------------------------------------------
    | 5. PASIEN KRS / KELUAR HIDUP
    |--------------------------------------------------------------------------
    | Mutasi TIDAK masuk ke sini.
    |
    */
    $dischargesCount = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereBetween('discharge_date', [$start, $end])
        ->whereNotIn('discharge_condition', [
            'deceased_under_48h',
            'deceased_over_48h',
            'deceased'
        ])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 6. MENINGGAL < 48 JAM
    |--------------------------------------------------------------------------
    */
    $deathsUnder48h = Admission::where('current_room_id', $room->id)
        ->where('status', 'deceased')
        ->where('discharge_condition', 'deceased_under_48h')
        ->whereBetween('discharge_date', [$start, $end])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 7. MENINGGAL >= 48 JAM
    |--------------------------------------------------------------------------
    */
    $deathsOver48h = Admission::where('current_room_id', $room->id)
        ->where('status', 'deceased')
        ->where('discharge_condition', 'deceased_over_48h')
        ->whereBetween('discharge_date', [$start, $end])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 8. PASIEN SISA / AKHIR HARI
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | Pasien Awal
    | + Pasien MRS
    | + Transfer In
    | - Transfer Out
    | - KRS
    | - Meninggal
    |
    */
    $remainingPatients =
        $initialPatients
        + $admissionsCount
        + $transfersInCount
        - $transfersOutCount
        - $dischargesCount
        - $deathsUnder48h
        - $deathsOver48h;

    $remainingPatients = max(0, $remainingPatients);


    /*
    |--------------------------------------------------------------------------
    | 9. ONE DAY CARE
    |--------------------------------------------------------------------------
    */
    $sameDayDischargesCount = Admission::where('current_room_id', $room->id)
        ->whereIn('status', ['discharged', 'deceased'])
        ->whereBetween('admission_date', [$start, $end])
        ->whereBetween('discharge_date', [$start, $end])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 10. HARI PERAWATAN
    |--------------------------------------------------------------------------
    */
    $careDays = $remainingPatients + $sameDayDischargesCount;


    /*
    |--------------------------------------------------------------------------
    | 11. TOTAL LAMA DIRAWAT
    |--------------------------------------------------------------------------
    |
    | Hanya pasien yang benar-benar KRS / meninggal.
    | Mutasi tidak dianggap pasien keluar rumah sakit.
    |
    */
    $totalLengthOfStay = (int) Admission::where('current_room_id', $room->id)
        ->whereIn('status', ['discharged', 'deceased'])
        ->whereBetween('discharge_date', [$start, $end])
        ->sum('length_of_stay');


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA SENSUS
    |--------------------------------------------------------------------------
    */
    return DailyCensus::updateOrCreate(
        [
            'room_id' => $room->id,
            'census_date' => $date
        ],
        [
            'initial_patients' => max(0, $initialPatients),

            // Pasien benar-benar MRS
            'admissions_count' => $admissionsCount,

            // Pasien pindahan masuk
            'transfers_in_count' => $transfersInCount,

            // Pasien pindahan keluar
            'transfers_out_count' => $transfersOutCount,

            // Pasien KRS
            'discharges_count' => $dischargesCount,

            // Meninggal
            'deaths_under_48h' => $deathsUnder48h,
            'deaths_over_48h' => $deathsOver48h,

            // Pasien sisa
            'remaining_patients' => $remainingPatients,

            // Hari perawatan
            'care_days' => max(0, $careDays),

            // Lama dirawat
            'total_length_of_stay' => $totalLengthOfStay,
        ]
    );
}


    /**
     * Hitung indikator Rawat Inap Rumah Sakit
     * berdasarkan periode / ruangan tertentu.
     */
    public static function calculateIndicators(
        Room $room,
        int $year,
        int $month
    ): array {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();

        $daysInMonth = $startDate->daysInMonth;

        /*
        |--------------------------------------------------------------------------
        | Ambil sensus harian bulan tersebut
        |--------------------------------------------------------------------------
        */

        $censuses = DailyCensus::where('room_id', $room->id)
            ->whereYear('census_date', $year)
            ->whereMonth('census_date', $month)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Total Hari Perawatan
        |--------------------------------------------------------------------------
        */

        $totalCareDays = $censuses->sum('care_days');


        /*
        |--------------------------------------------------------------------------
        | Total Lama Dirawat
        |--------------------------------------------------------------------------
        */

        $totalLengthOfStay = $censuses->sum('total_length_of_stay');


        /*
        |--------------------------------------------------------------------------
        | Total KRS
        |--------------------------------------------------------------------------
        */

        $totalDischarges = $censuses->sum('discharges_count');


        /*
        |--------------------------------------------------------------------------
        | Total Meninggal < 48 Jam
        |--------------------------------------------------------------------------
        */

        $totalDeathsUnder48h = $censuses->sum('deaths_under_48h');


        /*
        |--------------------------------------------------------------------------
        | Total Meninggal >= 48 Jam
        |--------------------------------------------------------------------------
        */

        $totalDeathsOver48h = $censuses->sum('deaths_over_48h');


        /*
        |--------------------------------------------------------------------------
        | Total Meninggal
        |--------------------------------------------------------------------------
        */

        $totalDeaths =
            $totalDeathsUnder48h +
            $totalDeathsOver48h;


        /*
        |--------------------------------------------------------------------------
        | Total Pasien Keluar
        |--------------------------------------------------------------------------
        */

        $totalOut =
            $totalDischarges +
            $totalDeaths;


        /*
        |--------------------------------------------------------------------------
        | Kapasitas Tempat Tidur
        |--------------------------------------------------------------------------
        */

        $capacity = $room->capacity;


        return [
            'total_care_days' => $totalCareDays,

            'total_length_of_stay' => $totalLengthOfStay,

            'total_discharges' => $totalDischarges,

            'total_deaths_under_48h' => $totalDeathsUnder48h,

            'total_deaths_over_48h' => $totalDeathsOver48h,

            'total_deaths' => $totalDeaths,

            'total_out' => $totalOut,

            'capacity' => $capacity,

            'days_in_month' => $daysInMonth,
        ];
    }
}