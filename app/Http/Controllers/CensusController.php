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

            Admission::create([
                'patient_id' => $patient->id,
                'current_room_id' => $validated['room_id'],
                'initial_room_id' => $validated['room_id'],
                'admission_date' => $validated['admission_date'],
                'status' => 'active',
                'diagnosis' => $validated['diagnosis'] ?? null,
            ]);
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
     * Export Rekap Bulanan ke CSV / Excel
     */
    public function exportMonthly(Request $request): StreamedResponse
    {
        $year = (int) $request->get('year', date('Y'));
        $month = (int) $request->get('month', date('n'));
        $roomId = $request->get('room_id', Room::first()?->id);

        $room = Room::findOrFail($roomId);
        $fileName = "Rekap_Sensus_SHRI_{$room->code}_{$year}_{$month}.csv";

        $dischargedAdmissions = Admission::where('current_room_id', $room->id)
            ->whereIn('status', ['discharged', 'deceased'])
            ->whereYear('discharge_date', $year)
            ->whereMonth('discharge_date', $month)
            ->with('patient')
            ->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($dischargedAdmissions, $room, $month, $year) {
            $file = fopen('php://output', 'w');
            
            // Header Info
            fputcsv($file, ["REKAPITULASI SENSUS HARIAN RAWAT INAP (SHRI)"]);
            fputcsv($file, ["RSUD DR. SAIFUL ANWAR MALANG"]);
            fputcsv($file, ["Ruangan", $room->name, "Lantai", $room->floor]);
            fputcsv($file, ["Periode", "Bulan " . $month . " Tahun " . $year]);
            fputcsv($file, []);

            // Column Titles
            fputcsv($file, [
                'No',
                'No. Rekam Medis',
                'Nama Pasien',
                'JK',
                'Tanggal & Jam MRS',
                'Tanggal & Jam KRS',
                'Keadaan KRS',
                'Lama Dirawat (LD) Hari'
            ]);

            $no = 1;
            foreach ($dischargedAdmissions as $adm) {
                $conditionText = match($adm->discharge_condition) {
                    'cured' => 'Sembuh',
                    'improved' => 'Membaik',
                    'unimproved' => 'Belum Sembuh',
                    'referred' => 'Dirujuk',
                    'aps' => 'APS',
                    'deceased_under_48h' => 'Meninggal < 48 Jam',
                    'deceased_over_48h' => 'Meninggal >= 48 Jam',
                    default => 'Keluar',
                };

                fputcsv($file, [
                    $no++,
                    $adm->patient->rm_number,
                    $adm->patient->name,
                    $adm->patient->gender,
                    $adm->admission_date ? $adm->admission_date->format('d/m/Y H:i') : '-',
                    $adm->discharge_date ? $adm->discharge_date->format('d/m/Y H:i') : '-',
                    $conditionText,
                    $adm->length_of_stay
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
