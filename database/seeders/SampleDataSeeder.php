<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\DailyCensus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure RoomSeeder runs first so all rooms exist with valid capacities (> 0 TT)
        $this->call(RoomSeeder::class);

        $rooms = Room::where('is_active', true)->get();
        if ($rooms->isEmpty()) {
            return;
        }

        // Disable FK constraints for clean truncation
        Schema::disableForeignKeyConstraints();
        DailyCensus::truncate();
        PatientTransfer::truncate();
        Admission::truncate();
        Patient::truncate();
        Schema::enableForeignKeyConstraints();

        $firstNamesL = ['Ahmad', 'Budi', 'Candra', 'Deni', 'Eko', 'Fajar', 'Giri', 'Hendra', 'Irfan', 'Joko', 'Kiki', 'Lukman', 'Marno', 'Nugroho', 'Oki', 'Prabowo', 'Rian', 'Slamet', 'Teguh', 'Utomo', 'Vina', 'Wahyu', 'Yudi', 'Zainal'];
        $firstNamesP = ['Ani', 'Bunga', 'Citra', 'Dewi', 'Endang', 'Fitri', 'Gita', 'Hani', 'Indah', 'Juli', 'Kartika', 'Laras', 'Maya', 'Nita', 'Olivia', 'Putri', 'Ratna', 'Siti', 'Titi', 'Utami', 'Vivi', 'Wulan', 'Yuni', 'Zahra'];
        $lastNames  = ['Santoso', 'Wijaya', 'Saputra', 'Hidayat', 'Kusuma', 'Nugraha', 'Pratama', 'Wibowo', 'Setiawan', 'Ramadhan', 'Laksana', 'Kurnianto', 'Purnama'];

        $paymentMethods = ['JKN', 'SPM', 'JKMM', 'PKS', 'UMUM'];
        $diagnoses = [
            'Diabetes Mellitus Tipe 2', 'Hipertensi Grade II', 'Stroke Infark', 'DHF (Demam Berdarah Dengue)',
            'Pneumonia Komunitas', 'Gastroenteritis Akut', 'Dyspepsia Syndrome', 'Congestive Heart Failure',
            'CKD Stage V', 'Asthma Bronchiale', 'Thypoid Fever', 'Appendicitis Akut'
        ];

        // Rentang Sensus Harian: 1 Januari 2026 s/d 26 September 2026 (9 Bulan)
        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 9, 26);

        $dailyCensusesData = [];
        $previousRemaining = [];
        foreach ($rooms as $r) {
            $cap = max(6, $r->capacity);
            $previousRemaining[$r->id] = rand(4, max(4, (int) floor($cap * 0.6)));
        }

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dStr = $current->toDateString();

            foreach ($rooms as $room) {
                $cap = max(6, $room->capacity);
                $initial = $previousRemaining[$room->id] ?? rand(4, max(4, (int) floor($cap * 0.6)));

                // NON-ZERO GUARANTEE FOR ALL CATEGORIES
                $admissionsToday = rand(1, 3);
                $transfersInToday = rand(1, 2);
                $transfersOutToday = rand(1, 2);

                $dischargesCured = rand(1, 3);
                $dischargesAps = rand(1, 2);
                $dischargesRun = rand(1, 2);
                $dischargesReferred = rand(1, 2);
                $deathsU48 = rand(1, 2);
                $deathsO48 = rand(1, 2);

                $totalDischargesHidup = $dischargesCured + $dischargesAps + $dischargesRun + $dischargesReferred;

                $remaining = $initial + $admissionsToday + $transfersInToday - $totalDischargesHidup - $transfersOutToday - $deathsU48 - $deathsO48;
                $remaining = max(3, min($cap - 1, $remaining));
                $previousRemaining[$room->id] = $remaining;

                $dailyCensusesData[] = [
                    'room_id' => $room->id,
                    'census_date' => $dStr,
                    'initial_patients' => $initial,
                    'admissions_count' => $admissionsToday,
                    'transfers_in_count' => $transfersInToday,
                    'transfers_out_count' => $transfersOutToday,
                    'discharges_count' => $totalDischargesHidup,
                    'discharges_cured_count' => $dischargesCured,
                    'discharges_aps_count' => $dischargesAps,
                    'discharges_run_count' => $dischargesRun,
                    'discharges_referred_count' => $dischargesReferred,
                    'deaths_under_48h' => $deathsU48,
                    'deaths_over_48h' => $deathsO48,
                    'remaining_patients' => $remaining,
                    'care_days' => $remaining,
                    'total_length_of_stay' => $totalDischargesHidup * rand(2, 5),
                    'created_at' => $current->toDateTimeString(),
                    'updated_at' => $current->toDateTimeString(),
                ];
            }

            $current->addDay();
        }

        // Bulk insert daily censuses (chunk of 1000)
        foreach (array_chunk($dailyCensusesData, 1000) as $chunk) {
            DailyCensus::insert($chunk);
        }

        // 2. Generate Active Patients for Today across ALL Rooms (~50-60% Occupancy)
        $patientsData = [];
        $admissionsData = [];
        $patientIdx = 1000;
        $admIdCounter = 1;

        foreach ($rooms as $room) {
            $cap = max(6, $room->capacity);
            $targetActive = max(3, (int) floor($cap * 0.55)); // ~55% capacity

            for ($k = 0; $k < $targetActive; $k++) {
                $patientIdx++;
                $gender = (rand(0, 1) === 1) ? 'L' : 'P';
                $fn = ($gender === 'L') ? $firstNamesL[array_rand($firstNamesL)] : $firstNamesP[array_rand($firstNamesP)];
                $ln = $lastNames[array_rand($lastNames)];

                $pId = $patientIdx - 999;
                $patientsData[] = [
                    'id' => $pId,
                    'rm_number' => sprintf('120%05d', $patientIdx),
                    'name' => "$fn $ln",
                    'gender' => $gender,
                    'address' => 'Kota Malang / Kab. Malang',
                    'phone' => '08' . rand(111111111, 999999999),
                    'created_at' => Carbon::now()->subDays(rand(1, 4))->toDateTimeString(),
                    'updated_at' => Carbon::now()->subDays(rand(1, 4))->toDateTimeString(),
                ];

                // Ensure all 5 payment methods are evenly distributed
                $payment = $paymentMethods[$k % count($paymentMethods)];
                $assignedClass = $room->room_class ?? 'Kelas 3';
                $admId = $admIdCounter++;

                $admissionsData[] = [
                    'id' => $admId,
                    'patient_id' => $pId,
                    'current_room_id' => $room->id,
                    'initial_room_id' => $room->id,
                    'room_class' => $assignedClass,
                    'admission_date' => Carbon::now()->subDays(rand(1, 4))->setHour(rand(7, 21))->toDateTimeString(),
                    'discharge_date' => null,
                    'discharge_condition' => null,
                    'status' => 'active',
                    'length_of_stay' => null,
                    'payment_method' => $payment,
                    'diagnosis' => $diagnoses[array_rand($diagnoses)],
                    'created_at' => Carbon::now()->subDays(rand(1, 4))->toDateTimeString(),
                    'updated_at' => Carbon::now()->subDays(rand(1, 4))->toDateTimeString(),
                ];
            }
        }

        foreach (array_chunk($patientsData, 500) as $chunk) {
            Patient::insert($chunk);
        }
        foreach (array_chunk($admissionsData, 500) as $chunk) {
            Admission::insert($chunk);
        }

        // 3. Create Pending & Accepted Transfers (8 Active Pending Requests)
        $activeAdmissions = Admission::where('status', 'active')->get();
        if ($activeAdmissions->count() >= 10) {
            $sampleAdmissions = $activeAdmissions->random(min(30, $activeAdmissions->count()));
            $transferIdx = 0;
            foreach ($sampleAdmissions as $adm) {
                $targetRoom = $rooms->where('id', '!=', $adm->current_room_id)->random();
                if ($targetRoom) {
                    $isPending = ($transferIdx < 8);
                    $status = $isPending ? 'pending' : 'accepted';
                    $transferDate = Carbon::now()->subHours(rand(2, 48));

                    PatientTransfer::create([
                        'admission_id' => $adm->id,
                        'from_room_id' => $adm->current_room_id,
                        'to_room_id' => $targetRoom->id,
                        'transfer_date' => $transferDate,
                        'status' => $status,
                        'accepted_at' => $isPending ? null : $transferDate->copy()->addHours(rand(1, 4)),
                        'notes' => $isPending ? 'Permohonan mutasi alih perawatan (Pending)' : 'Mutasi disetujui',
                    ]);

                    if (!$isPending) {
                        $adm->current_room_id = $targetRoom->id;
                        $adm->save();
                    }
                    $transferIdx++;
                }
            }
        }
    }
}
