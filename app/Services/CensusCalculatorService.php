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

    $dischargesCuredCount = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereBetween('discharge_date', [$start, $end])
        ->whereIn('discharge_condition', ['cured', 'improved', 'sembuh'])
        ->count();

    $dischargesApsCount = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereBetween('discharge_date', [$start, $end])
        ->whereIn('discharge_condition', ['aps', 'pulang_paksa'])
        ->count();

    $dischargesRunCount = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereBetween('discharge_date', [$start, $end])
        ->whereIn('discharge_condition', ['run', 'lari'])
        ->count();

    $dischargesReferredCount = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereBetween('discharge_date', [$start, $end])
        ->whereIn('discharge_condition', ['referred', 'dirujuk'])
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
            'discharges_cured_count' => $dischargesCuredCount,
            'discharges_aps_count' => $dischargesApsCount,
            'discharges_run_count' => $dischargesRunCount,
            'discharges_referred_count' => $dischargesReferredCount,

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

    /**
     * Hitung Rekap Sensus Harian per Kelas Perawatan (Tabel 1 PDF).
     */
    public static function getDailySummaryByClass(string $date, ?Room $selectedRoom = null): array
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $standardClasses = ['VVIP', 'VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'Non Kelas / Khusus'];
        
        $queryRooms = Room::where('is_active', true);
        if ($selectedRoom) {
            $queryRooms->where('id', $selectedRoom->id);
        }
        $rooms = $queryRooms->get();

        $existingClasses = $rooms->pluck('room_class')->filter()->unique()->values()->toArray();
        $classes = array_values(array_unique(array_merge($standardClasses, $existingClasses)));

        $result = [];

        foreach ($classes as $cls) {
            $classRooms = $rooms->filter(fn($r) => ($r->room_class ?? 'Non Kelas / Khusus') === $cls || ($cls === 'Non Kelas / Khusus' && empty($r->room_class)));
            $roomIds = $classRooms->pluck('id')->toArray();
            $capacity = $classRooms->sum('capacity');

            if (empty($roomIds) && $selectedRoom && ($selectedRoom->room_class ?? 'Non Kelas / Khusus') === $cls) {
                $roomIds = [$selectedRoom->id];
                $capacity = $selectedRoom->capacity;
            }

            if (empty($roomIds)) {
                $result[$cls] = [
                    'class_name' => $cls,
                    'penderita_awal' => 0,
                    'ob' => 0,
                    'dari' => 0,
                    'keluar_dp' => 0,
                    'keluar_pp' => 0,
                    'keluar_lari' => 0,
                    'keluar_rj' => 0,
                    'ke_ruang_lain' => 0,
                    'jumlah_keluar_hidup' => 0,
                    'mati_under_48' => 0,
                    'mati_over_48' => 0,
                    'jumlah_mati' => 0,
                    'total_keluar' => 0,
                    'sisa_pasien' => 0,
                    'pulang_hari_sama' => 0,
                    'hari_perawatan' => 0,
                    'ld' => 0,
                    'jml_tt' => $capacity,
                ];
                continue;
            }

            // Penderita Awal (sisa kemarin)
            $penderitaAwal = Admission::whereIn('current_room_id', $roomIds)
                ->where('admission_date', '<', $start)
                ->where(function ($q) use ($start) {
                    $q->whereNull('discharge_date')
                        ->orWhere('discharge_date', '>=', $start);
                })
                ->count();

            // OB (One Day Care)
            $ob = Admission::whereIn('current_room_id', $roomIds)
                ->whereIn('status', ['discharged', 'deceased'])
                ->whereBetween('admission_date', [$start, $end])
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            // Dari (Transfer In)
            $dari = PatientTransfer::whereIn('to_room_id', $roomIds)
                ->where('status', 'accepted')
                ->whereBetween('transfer_date', [$start, $end])
                ->count();

            // Keluar Hidup
            $keluarDp = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'cured')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $keluarPp = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'discharged')
                ->whereIn('discharge_condition', ['improved', 'aps'])
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $keluarLari = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'unimproved')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $keluarRj = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'referred')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $keRuangLain = PatientTransfer::whereIn('from_room_id', $roomIds)
                ->where('status', 'accepted')
                ->whereBetween('transfer_date', [$start, $end])
                ->count();

            $jumlahKeluarHidup = $keluarDp + $keluarPp + $keluarLari + $keluarRj + $keRuangLain;

            // Mati
            $matiUnder48 = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'deceased')
                ->where('discharge_condition', 'deceased_under_48h')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $matiOver48 = Admission::whereIn('current_room_id', $roomIds)
                ->where('status', 'deceased')
                ->where('discharge_condition', 'deceased_over_48h')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $jumlahMati = $matiUnder48 + $matiOver48;
            $totalKeluar = $jumlahKeluarHidup + $jumlahMati;

            // Pasien Baru MRS pada tanggal tersebut
            $penderitaBaru = Admission::whereIn('initial_room_id', $roomIds)
                ->whereBetween('admission_date', [$start, $end])
                ->count();

            // Pasien Sisa = Penderita Awal + Penderita Baru + Dari - Total Keluar
            $sisaPasien = max(0, $penderitaAwal + $penderitaBaru + $dari - $totalKeluar);

            // Hari Perawatan (HP = Sisa Pasien + ODC)
            $hariPerawatan = $sisaPasien + $ob;

            // Lama Dirawat (LD)
            $ld = (int) Admission::whereIn('current_room_id', $roomIds)
                ->whereIn('status', ['discharged', 'deceased'])
                ->whereBetween('discharge_date', [$start, $end])
                ->sum('length_of_stay');

            $result[$cls] = [
                'class_name' => $cls,
                'penderita_awal' => $penderitaAwal,
                'ob' => $ob,
                'dari' => $dari,
                'keluar_dp' => $keluarDp,
                'keluar_pp' => $keluarPp,
                'keluar_lari' => $keluarLari,
                'keluar_rj' => $keluarRj,
                'ke_ruang_lain' => $keRuangLain,
                'jumlah_keluar_hidup' => $jumlahKeluarHidup,
                'mati_under_48' => $matiUnder48,
                'mati_over_48' => $matiOver48,
                'jumlah_mati' => $jumlahMati,
                'total_keluar' => $totalKeluar,
                'sisa_pasien' => $sisaPasien,
                'pulang_hari_sama' => $ob,
                'hari_perawatan' => $hariPerawatan,
                'ld' => $ld,
                'jml_tt' => $capacity,
            ];
        }

        return $result;
    }

    /**
     * Hitung Rekap Sensus Bulanan Matriks per Tanggal (Tabel 2 PDF).
     */
    public static function getMonthlyMatrix(Room $room, int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $matrix = [];
        $totals = [
            'penderita_awal' => 0,
            'penderita_baru' => 0,
            'penderita_dari' => 0,
            'keluar_dipulangkan' => 0,
            'keluar_pulang_paksa' => 0,
            'keluar_lari' => 0,
            'keluar_dirujuk' => 0,
            'mati_under_48' => 0,
            'mati_over_48' => 0,
            'ke_ruang_lain' => 0,
            'sisa_pasien' => 0,
            'ob' => 0,
            'hp' => 0,
            'ld' => 0,
            'tt' => $room->capacity,
            'jkn' => 0,
            'spm' => 0,
            'jkmm' => 0,
            'pks' => 0,
            'umum' => 0,
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $start = Carbon::parse($dateStr)->startOfDay();
            $end = Carbon::parse($dateStr)->endOfDay();

            // Penderita Awal (sisa kemarin)
            $penderitaAwal = Admission::where('current_room_id', $room->id)
                ->where('admission_date', '<', $start)
                ->where(function ($q) use ($start) {
                    $q->whereNull('discharge_date')
                        ->orWhere('discharge_date', '>=', $start);
                })
                ->count();

            // Penderita Baru
            $penderitaBaru = Admission::where('initial_room_id', $room->id)
                ->whereBetween('admission_date', [$start, $end])
                ->count();

            // Penderita Dari (Transfer In)
            $penderitaDari = PatientTransfer::where('to_room_id', $room->id)
                ->where('status', 'accepted')
                ->whereBetween('transfer_date', [$start, $end])
                ->count();

            // Keluar Hidup
            $dipulangkan = Admission::where('current_room_id', $room->id)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'cured')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $pulangPaksa = Admission::where('current_room_id', $room->id)
                ->where('status', 'discharged')
                ->whereIn('discharge_condition', ['improved', 'aps'])
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $lari = Admission::where('current_room_id', $room->id)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'unimproved')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $dirujuk = Admission::where('current_room_id', $room->id)
                ->where('status', 'discharged')
                ->where('discharge_condition', 'referred')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            // Mati
            $matiUnder48 = Admission::where('current_room_id', $room->id)
                ->where('status', 'deceased')
                ->where('discharge_condition', 'deceased_under_48h')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $matiOver48 = Admission::where('current_room_id', $room->id)
                ->where('status', 'deceased')
                ->where('discharge_condition', 'deceased_over_48h')
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            // Ke Ruang Lain (Transfer Out)
            $keRuangLain = PatientTransfer::where('from_room_id', $room->id)
                ->where('status', 'accepted')
                ->whereBetween('transfer_date', [$start, $end])
                ->count();

            // OB (One Day Care)
            $ob = Admission::where('current_room_id', $room->id)
                ->whereIn('status', ['discharged', 'deceased'])
                ->whereBetween('admission_date', [$start, $end])
                ->whereBetween('discharge_date', [$start, $end])
                ->count();

            $totalKeluar = $dipulangkan + $pulangPaksa + $lari + $dirujuk + $matiUnder48 + $matiOver48 + $keRuangLain;

            // Sisa Pasien
            $sisaPasien = max(0, $penderitaAwal + $penderitaBaru + $penderitaDari - $totalKeluar);

            // Hari Perawatan
            $hp = $sisaPasien + $ob;

            // Lama Dirawat (LD)
            $ld = (int) Admission::where('current_room_id', $room->id)
                ->whereIn('status', ['discharged', 'deceased'])
                ->whereBetween('discharge_date', [$start, $end])
                ->sum('length_of_stay');

            // Cara Bayar breakdown untuk pasien aktif/MRS pada hari ini
            $admissionsDay = Admission::where('current_room_id', $room->id)
                ->where('admission_date', '<=', $end)
                ->where(function ($q) use ($start) {
                    $q->whereNull('discharge_date')
                        ->orWhere('discharge_date', '>=', $start);
                })
                ->get();

            $jkn = $admissionsDay->filter(fn($a) => in_array(strtolower($a->payment_method ?? ''), ['jkn', 'bpjs', 'bpjs kesehatan']))->count();
            $spm = $admissionsDay->filter(fn($a) => strtolower($a->payment_method ?? '') === 'spm')->count();
            $jkmm = $admissionsDay->filter(fn($a) => strtolower($a->payment_method ?? '') === 'jkmm')->count();
            $pks = $admissionsDay->filter(fn($a) => strtolower($a->payment_method ?? '') === 'pks')->count();
            $umum = $admissionsDay->count() - ($jkn + $spm + $jkmm + $pks);
            if ($umum < 0) $umum = 0;

            $dayData = [
                'day' => $day,
                'date' => $dateStr,
                'penderita_awal' => $penderitaAwal,
                'penderita_baru' => $penderitaBaru,
                'penderita_dari' => $penderitaDari,
                'keluar_dipulangkan' => $dipulangkan,
                'keluar_pulang_paksa' => $pulangPaksa,
                'keluar_lari' => $lari,
                'keluar_dirujuk' => $dirujuk,
                'mati_under_48' => $matiUnder48,
                'mati_over_48' => $matiOver48,
                'ke_ruang_lain' => $keRuangLain,
                'sisa_pasien' => $sisaPasien,
                'ob' => $ob,
                'hp' => $hp,
                'ld' => $ld,
                'tt' => $room->capacity,
                'jkn' => $jkn,
                'spm' => $spm,
                'jkmm' => $jkmm,
                'pks' => $pks,
                'umum' => $umum,
            ];

            $matrix[$day] = $dayData;

            // Accumulate totals
            $totals['penderita_awal'] += $penderitaAwal;
            $totals['penderita_baru'] += $penderitaBaru;
            $totals['penderita_dari'] += $penderitaDari;
            $totals['keluar_dipulangkan'] += $dipulangkan;
            $totals['keluar_pulang_paksa'] += $pulangPaksa;
            $totals['keluar_lari'] += $lari;
            $totals['keluar_dirujuk'] += $dirujuk;
            $totals['mati_under_48'] += $matiUnder48;
            $totals['mati_over_48'] += $matiOver48;
            $totals['ke_ruang_lain'] += $keRuangLain;
            $totals['sisa_pasien'] += $sisaPasien;
            $totals['ob'] += $ob;
            $totals['hp'] += $hp;
            $totals['ld'] += $ld;
            $totals['jkn'] += $jkn;
            $totals['spm'] += $spm;
            $totals['jkmm'] += $jkmm;
            $totals['pks'] += $pks;
            $totals['umum'] += $umum;
        }

        return [
            'matrix' => $matrix,
            'totals' => $totals,
            'days_in_month' => $daysInMonth,
        ];
    }
}