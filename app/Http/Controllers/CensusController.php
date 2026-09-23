<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\DailyCensus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CensusController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $defaultRoomId = ($user && $user->isAdminRuang() && $user->room_id) ? $user->room_id : Room::first()?->id;
        $selectedRoomId = $request->get('room_id', $defaultRoomId);
        $selectedDate = $request->get('date', Carbon::today()->toDateString());

        $room = Room::findOrFail($selectedRoomId);
        $rooms = Room::where('is_active', true)->get();

        // Real-Time auto sync sensus harian untuk tanggal pilihan
        \App\Services\CensusCalculatorService::syncRoomDate($room, $selectedDate);

        // Data sensus harian pada tanggal pilihan
        $census = DailyCensus::where('room_id', $room->id)
            ->where('census_date', $selectedDate)
            ->first();

        // Pasien aktif di ruangan ini saat ini
        $activeAdmissions = Admission::where('current_room_id', $room->id)
            ->where('status', 'active')
            ->with('patient')
            ->get();

        return view('census.index', compact('room', 'rooms', 'selectedDate', 'census', 'activeAdmissions'));
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'rm_number' => 'required|string',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'room_id' => 'required|exists:rooms,id',
            'room_class' => 'nullable|string|max:50',
            'admission_date' => 'required|date', // Mendukung tanggal & jam lengkap (bisa bulan lalu)
            'diagnosis' => 'nullable|string',
        ]);

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
                'status' => 'active',
                'diagnosis' => $validated['diagnosis'] ?? null,
            ]);

            // Sync sensus harian secara real-time
            if ($selectedRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($selectedRoom, Carbon::parse($validated['admission_date'])->toDateString());
            }
        });

        return redirect()->back()->with('success', 'Data Pasien Masuk berhasil dicatat dengan tanggal & jam MRS lengkap!');
    }

    public function dischargePatient(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'discharge_date' => 'required|date|after_or_equal:' . $admission->admission_date->toDateString(),
            'discharge_condition' => 'required|in:cured,improved,unimproved,referred,aps,deceased,deceased_under_48h,deceased_over_48h',
        ]);

        DB::transaction(function () use ($admission, $validated) {
            $admission->discharge_date = $validated['discharge_date'];
            
            $condition = $validated['discharge_condition'];
            // Pembacaan otomatis meninggal < 48 jam vs >= 48 jam
            if ($condition === 'deceased') {
                $condition = $admission->determineDeceasedCategory($validated['discharge_date']);
            }

            $admission->discharge_condition = $condition;
            $admission->status = in_array($condition, ['deceased_under_48h', 'deceased_over_48h', 'deceased']) ? 'deceased' : 'discharged';
            $admission->length_of_stay = $admission->calculateLengthOfStay();
            $admission->save();

            // Sync sensus harian secara real-time
            if ($admission->currentRoom) {
                \App\Services\CensusCalculatorService::syncRoomDate($admission->currentRoom, Carbon::parse($validated['discharge_date'])->toDateString());
            }
        });

        $conditionLabel = str_contains($admission->discharge_condition, 'under_48h') 
            ? 'Meninggal < 48 Jam (Otomatis Terbaca)' 
            : (str_contains($admission->discharge_condition, 'over_48h') ? 'Meninggal ≥ 48 Jam (Otomatis Terbaca)' : 'Keluar');

        return redirect()->back()->with('success', "Pasien KRS berhasil diproses ({$conditionLabel})! Lama Dirawat (LD): {$admission->length_of_stay} hari.");
    }

    /**
     * Tampilan Rekap Sensus Bulanan
     */
    public function monthly(Request $request)
    {
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));
        $roomId = $request->get('room_id', Room::first()?->id);

        $selectedRoom = Room::findOrFail($roomId);
        $rooms = Room::where('is_active', true)->get();

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        // Ambil data pasien yang KRS di bulan ini
        $dischargedAdmissions = Admission::where('current_room_id', $selectedRoom->id)
            ->whereIn('status', ['discharged', 'deceased'])
            ->whereYear('discharge_date', $year)
            ->whereMonth('discharge_date', $month)
            ->with('patient')
            ->get();

        return view('census.monthly', compact('selectedRoom', 'rooms', 'year', 'month', 'dischargedAdmissions', 'daysInMonth'));
    }

    /**
     * Export Rekap Sensus Bulanan ke format Excel (.xls) Resmi RSUD Dr. Saiful Anwar
     */
    public function exportMonthly(Request $request)
    {
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));
        $roomId = $request->get('room_id', Room::first()?->id);

        $room = Room::findOrFail($roomId);
        $fileName = "Rekap_Sensus_SHRI_{$room->code}_{$year}_{$month}.xls";

        // 1. Pasien Aktif Dirawat di Ruangan Ini
        $activeAdmissions = Admission::where('current_room_id', $room->id)
            ->where('status', 'active')
            ->with(['patient', 'initialRoom', 'currentRoom'])
            ->get();

        // 2. Pasien Keluar / KRS / Meninggal di Bulan Ini
        $dischargedAdmissions = Admission::where('current_room_id', $room->id)
            ->whereIn('status', ['discharged', 'deceased', 'transferred_out'])
            ->whereYear('discharge_date', $year)
            ->whereMonth('discharge_date', $month)
            ->with(['patient', 'initialRoom', 'currentRoom'])
            ->get();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNames[$month] ?? "Bulan {$month}";

        // 3. Hitung Indikator RS (BOR, ALOS, TOI, BTO, NDR, GDR)
        $indicators = \App\Services\CensusCalculatorService::calculateIndicators($room, $year, $month);

        // 4. Read Logo RSSA as Base64 for Excel Header
        $logoPath = public_path('logo-rssa.jpg');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // 5. Kelompokkan Data Berdasarkan Kelas Perawatan untuk Multi-Sheet Export
        $presentClasses = collect([])
            ->concat($activeAdmissions->map(fn($a) => $a->room_class ?? $room->room_class))
            ->concat($dischargedAdmissions->map(fn($a) => $a->room_class ?? $room->room_class))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($presentClasses) && $room->room_class) {
            $presentClasses[] = $room->room_class;
        }

        $allStandardClasses = ['Kelas 1', 'Kelas 2', 'Kelas 3', 'VIP', 'VVIP', 'Non Kelas / Khusus'];
        $sortedClasses = array_values(array_intersect($allStandardClasses, $presentClasses));
        foreach ($presentClasses as $pc) {
            if (!in_array($pc, $sortedClasses)) {
                $sortedClasses[] = $pc;
            }
        }

        $classGroups = [
            'Semua Kelas' => [
                'active' => $activeAdmissions,
                'discharged' => $dischargedAdmissions,
                'class_label' => 'Semua Kelas (Gabungan)',
            ]
        ];

        foreach ($sortedClasses as $cls) {
            $filteredActive = $activeAdmissions->filter(fn($a) => ($a->room_class ?? $room->room_class) === $cls)->values();
            $filteredDischarged = $dischargedAdmissions->filter(fn($a) => ($a->room_class ?? $room->room_class) === $cls)->values();

            $classGroups[$cls] = [
                'active' => $filteredActive,
                'discharged' => $filteredDischarged,
                'class_label' => $cls,
            ];
        }

        return response()->view('exports.monthly_census_excel', compact(
            'room',
            'year',
            'month',
            'monthName',
            'activeAdmissions',
            'dischargedAdmissions',
            'indicators',
            'logoBase64',
            'classGroups'
        ))->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
          ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"")
          ->header('Pragma', 'no-cache')
          ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
          ->header('Expires', '0');
    }
}
