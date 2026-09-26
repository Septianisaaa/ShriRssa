<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\DailyCensus;
use App\Models\PatientTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MonthlyCensusExportService;

class SensusController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    $allRooms = Room::where('is_active', true)->get();

    if ($user && $user->isAdminRuang() && $user->room_id) {
        $selectedRoomId = $user->room_id;

        $rooms = Room::where('id', $user->room_id)->get();
    } else {
        $defaultRoomId = Room::first()?->id;

        $selectedRoomId = $request->get('room_id', $defaultRoomId);

        $rooms = $allRooms->unique('name');
    }

    $selectedDate = $request->get(
        'date',
        Carbon::today()->toDateString()
    );

    if (
        !$selectedRoomId ||
        !Room::where('id', $selectedRoomId)->exists()
    ) {
        $rolePrefix = ($user && $user->isSuperAdmin())
            ? 'shri.'
            : 'admin_ruang.';

        return redirect()
            ->route($rolePrefix . 'dashboard')
            ->withErrors([
                'room' => 'Data ruangan belum tersedia.'
            ]);
    }

    $room = Room::findOrFail($selectedRoomId);

    /*
    |--------------------------------------------------------------------------
    | Sinkronisasi sensus
    |--------------------------------------------------------------------------
    */

    \App\Services\CensusCalculatorService::syncRoomDate(
        $room,
        $selectedDate
    );

    /*
    |--------------------------------------------------------------------------
    | Data Sensus Harian
    |--------------------------------------------------------------------------
    */

    $census = DailyCensus::where('room_id', $room->id)
        ->where('census_date', $selectedDate)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | PASIEN AKTIF
    |--------------------------------------------------------------------------
    */

    $activeAdmissions = Admission::where(
            'current_room_id',
            $room->id
        )
        ->where('status', 'active')
        ->with('patient')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | PASIEN OUT / KRS
    |
    | HANYA:
    | - discharged
    | - deceased
    |
    | MUTASI TIDAK MASUK KE SINI
    |--------------------------------------------------------------------------
    */

    $outAdmissions = Admission::where(
            'current_room_id',
            $room->id
        )
        ->whereIn('status', [
            'discharged',
            'deceased'
        ])
        ->whereNotNull('discharge_date')
        ->whereDate('discharge_date', $selectedDate)
        ->with('patient')
        ->orderBy('discharge_date', 'desc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | PASIEN SISA
    |
    | Pasien yang sudah dirawat SEBELUM tanggal sensus
    | dan masih aktif pada tanggal tersebut.
    |
    | Untuk sementara kita ambil pasien aktif yang MRS
    | sebelum tanggal sensus.
    |--------------------------------------------------------------------------
    */

    $sisaAdmissions = Admission::where(
            'current_room_id',
            $room->id
        )
        ->where('status', 'active')
        ->whereDate(
            'admission_date',
            '<',
            $selectedDate
        )
        ->with('patient')
        ->orderBy('admission_date')
        ->get();

        // =====================================================
    // REKAP PASIEN UNTUK TANGGAL SENSUS
    // =====================================================

    // Pasien sisa = pasien yang sudah dirawat sebelum tanggal sensus
    // dan masih aktif pada awal tanggal sensus
    $sensusDate = Carbon::parse($selectedDate)->startOfDay();

    $pasienSisa = Admission::where('current_room_id', $room->id)
        ->where('admission_date', '<', $sensusDate)
        ->where(function ($query) use ($sensusDate) {
            $query->whereNull('discharge_date')
                ->orWhere('discharge_date', '>=', $sensusDate);
        })
        ->count();

    // Pasien masuk pada tanggal sensus
    $pasienMasuk = Admission::where('current_room_id', $room->id)
        ->whereDate('admission_date', $selectedDate)
        ->count();

    // Pasien KRS / meninggal pada tanggal sensus
    $pasienOut = Admission::where('current_room_id', $room->id)
        ->whereIn('status', ['discharged', 'deceased'])
        ->whereDate('discharge_date', $selectedDate)
        ->count();

    // Total pasien akhir
    $totalPasien = $pasienSisa + $pasienMasuk - $pasienOut;

        // =====================================================
    // REKAP PASIEN SENSUS
    // =====================================================

    // Pasien sisa dari hari sebelumnya
    $pasienSisa = Admission::where('current_room_id', $room->id)
        ->where('admission_date', '<', $sensusDate)
        ->where(function ($query) use ($sensusDate) {
            $query->whereNull('discharge_date')
                ->orWhere('discharge_date', '>=', $sensusDate);
        })
        ->count();

    // Pasien masuk pada tanggal sensus
    $pasienMasuk = Admission::where('current_room_id', $room->id)
        ->whereDate('admission_date', $selectedDate)
        ->count();

    // Pasien OB
    // Untuk sementara dihitung dari pasien yang MRS dan KRS pada hari yang sama
    $pasienOb = Admission::where('current_room_id', $room->id)
        ->where('admission_type', 'new')
        ->whereDate('admission_date', $selectedDate)
        ->count();

    // Pasien pindahan / Transfer In pada tanggal sensus
    $pasienPindahan = \App\Models\PatientTransfer::where('to_room_id', $room->id)
    ->where('status', 'accepted')
    ->whereDate('transfer_date', $selectedDate)
    ->count();

    // Pasien KRS / meninggal pada tanggal sensus
    // Mutasi TIDAK dihitung sebagai pasien out.
    $pasienOut = Admission::where('current_room_id', $room->id)
        ->whereIn('status', ['discharged', 'deceased'])
        ->whereDate('discharge_date', $selectedDate)
        ->count();

    // Pasien Dipindahkan Keluar
    $transfersOut = \App\Models\PatientTransfer::where('from_room_id', $room->id)
    ->where('status', 'accepted')
    ->whereDate('transfer_date', $selectedDate)
    ->count();

    // Total pasien akhir
    $totalPasien = $pasienSisa + $pasienMasuk - $pasienOut;

        // =====================================================
    // PASIEN KRS PADA TANGGAL SENSUS
    // =====================================================

    $krsAdmissions = Admission::where('current_room_id', $room->id)
        ->where('status', 'discharged')
        ->whereDate('discharge_date', $selectedDate)
        ->with('patient')
        ->get();


    // =====================================================
    // PASIEN MENINGGAL PADA TANGGAL SENSUS
    // =====================================================

    $deceasedAdmissions = Admission::where('current_room_id', $room->id)
        ->where('status', 'deceased')
        ->whereDate('discharge_date', $selectedDate)
        ->with('patient')
        ->get();
        
    $transferInAdmissions = PatientTransfer::with('admission.patient')
    ->where('to_room_id', $room->id)
    ->where('status', 'accepted')
    ->whereDate('transfer_date', $selectedDate)
    ->get();
    $pasienPindahan = $transferInAdmissions->count();
    // Data rekap untuk Blade
    $rekap = [
    // Pasien yang dibawa dari hari sebelumnya
    'pasien_sisa' => $pasienSisa,

    // Pasien MRS baru
    'pasien_masuk' => $pasienMasuk,

    // One Day Care
    'pasien_ob' => $pasienOb,

    // Pasien masuk karena mutasi dari ruangan lain
    'pasien_pindahan' => $pasienPindahan,

    // Total pasien masuk hari ini
    'total_pasien_masuk' =>
        $pasienOb + $pasienPindahan,

    // Total pasien yang dirawat pada hari tersebut
    'total_pasien_dirawat' =>
        $pasienSisa + $pasienOb + $pasienPindahan,

    // Pasien KRS
    'pasien_krs' => $krsAdmissions->count(),

    // Pasien dipindahkan ke ruangan lain
'pasien_dipindahkan' => $transfersOut,

    // Pasien meninggal
    'pasien_meninggal' => $deceasedAdmissions->count(),

    // Total pasien keluar
    'total_pasien_out' =>
        $krsAdmissions->count() + $deceasedAdmissions->count(),

    // Sisa pasien untuk hari berikutnya
    'pasien_sisa_akhir' =>
        $pasienSisa
        + $pasienOb
        + $pasienPindahan
        - $krsAdmissions->count()
        - $deceasedAdmissions->count(),
];

    return view('sensus.index', compact(
    'room',
    'rooms',
    'allRooms',
    'selectedDate',
    'census',
    'activeAdmissions',
    'outAdmissions',
    'rekap'
));
}

    public function storePatient(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'rm_number' => 'required|string',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'room_id' => 'required|exists:rooms,id',
            'room_class' => 'nullable|string|max:50',
            'admission_date' => 'required|date',
            'diagnosis' => 'nullable|string',
        ]);

        if ($user && $user->isAdminRuang() && $user->room_id) {
            $validated['room_id'] = $user->room_id;
        }

        DB::transaction(function () use ($validated) {
            $patient = Patient::firstOrCreate(
                ['rm_number' => $validated['rm_number']],
                [
                    'name' => $validated['name'],
                    'gender' => $validated['gender'],
                ]
            );

            $selectedRoom = Room::find($validated['room_id']);

            Admission::create([
                'patient_id' => $patient->id,
                'current_room_id' => $validated['room_id'],
                'initial_room_id' => $validated['room_id'],
                'room_class' => $validated['room_class'] ?? $selectedRoom?->room_class,
                'admission_date' => $validated['admission_date'],
                'admission_type' => 'new',
                'status' => 'active',
                'diagnosis' => $validated['diagnosis'] ?? null,
            ]);

            if ($selectedRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($selectedRoom, Carbon::parse($validated['admission_date'])->toDateString());
            }
        });

        return redirect()->back()->with('success', 'Data Pasien Masuk berhasil dicatat!');
    }

    public function dischargePatient(Request $request, Admission $admission)
    {
        $user = auth()->user();
        if ($user && $user->isAdminRuang() && $user->room_id && $admission->current_room_id != $user->room_id) {
            abort(403, 'Anda tidak memiliki akses untuk memproses pasien di ruangan lain.');
        }

        $validated = $request->validate([
            'discharge_date' => 'required|date|after_or_equal:' . $admission->admission_date->toDateString(),
            'discharge_condition' => 'required|in:cured,improved,unimproved,referred,aps,deceased,deceased_under_48h,deceased_over_48h',
        ]);

        DB::transaction(function () use ($admission, $validated) {
            $admission->discharge_date = $validated['discharge_date'];
            
            $condition = $validated['discharge_condition'];
            if ($condition === 'deceased') {
                $condition = $admission->determineDeceasedCategory($validated['discharge_date']);
            }

            $admission->discharge_condition = $condition;
            $admission->status = in_array($condition, ['deceased_under_48h', 'deceased_over_48h', 'deceased']) ? 'deceased' : 'discharged';
            $admission->length_of_stay = $admission->calculateLengthOfStay();
            $admission->save();

            if ($admission->currentRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($admission->currentRoom, Carbon::parse($validated['discharge_date'])->toDateString());
            }
        });

        $conditionLabel = str_contains($admission->discharge_condition, 'under_48h') 
            ? 'Meninggal < 48 Jam (Otomatis Terbaca)' 
            : (str_contains($admission->discharge_condition, 'over_48h') ? 'Meninggal ≥ 48 Jam (Otomatis Terbaca)' : 'Keluar');

        return redirect()->back()->with('success', "Pasien KRS berhasil diproses ({$conditionLabel})! Lama Dirawat (LD): {$admission->length_of_stay} hari.");
    }

    public function monthly(Request $request)
    {
        $user = auth()->user();
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));

        if ($user && $user->isAdminRuang() && $user->room_id) {
            $roomId = $user->room_id;
            $rooms = Room::where('id', $user->room_id)->get();
        } else {
            $roomId = $request->get('room_id', Room::first()?->id);
            $rooms = Room::where('is_active', true)->get();
        }

        if (!$roomId || !Room::where('id', $roomId)->exists()) {
            $rolePrefix = ($user && $user->isSuperAdmin()) ? 'shri.' : 'admin_ruang.';
            return redirect()->route($rolePrefix . 'dashboard')
                ->withErrors(['room' => 'Data ruangan belum tersedia. Silakan jalankan seeder database terlebih dahulu (php artisan db:seed).']);
        }

        $selectedRoom = Room::find($roomId);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $dischargedAdmissions = Admission::where('current_room_id', $selectedRoom->id)
            ->whereIn('status', ['discharged', 'deceased'])
            ->whereYear('discharge_date', $year)
            ->whereMonth('discharge_date', $month)
            ->with('patient')
            ->get();

        return view('sensus.monthly', compact('selectedRoom', 'rooms', 'year', 'month', 'dischargedAdmissions', 'daysInMonth'));
    }

    public function exportMonthly(Request $request)
    {
        $user = auth()->user();
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));

        if ($user && $user->isAdminRuang() && $user->room_id) {
            $roomId = $user->room_id;
        } else {
            $roomId = $request->get('room_id');
        }

        $selectedRoom = $roomId ? Room::find($roomId) : null;

        $exportService = new MonthlyCensusExportService();
        return $exportService->download($year, $month, $selectedRoom);
    }
}
