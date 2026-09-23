<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\PatientTransfer;
use App\Models\DailyCensus;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        $firstRoom = $rooms->first();

        // 1. Seed Petugas SHRI (Superadmin)
        User::updateOrCreate(
            ['email' => 'shri@rssa.go.id'],
            [
                'name' => 'Petugas SHRI (Superadmin)',
                'username' => 'shri',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'phone' => '081234567890',
            ]
        );

        // 2. Seed Admin Ruangan (Admin)
        if ($firstRoom) {
            User::updateOrCreate(
                ['email' => 'admin.ruang@rssa.go.id'],
                [
                    'name' => 'Admin ' . $firstRoom->name,
                    'username' => 'adminruang',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'room_id' => $firstRoom->id,
                    'phone' => '089876543210',
                ]
            );
        }


        $firstNamesL = ['Budi', 'Ahmad', 'Eko', 'Slamet', 'Bambang', 'Hendra', 'Agus', 'Sugeng', 'Hartono', 'Bayu', 'Fajar', 'Dwi', 'Tri', 'Anang', 'Supriadi', 'Doni', 'Rudi', 'Heri', 'Joko', 'Wawan'];
        $firstNamesP = ['Siti', 'Dewi', 'Rina', 'Sri', 'Ratna', 'Endang', 'Maryam', 'Yuni', 'Indah', 'Maya', 'Nurul', 'Eka', 'Wati', 'Titin', 'Nining', 'Lilis', 'Diah', 'Eni', 'Retno', 'Sulastri'];
        $lastNames = ['Santoso', 'Aminah', 'Fauzi', 'Rahmawati', 'Prasetyo', 'Wijaya', 'Utomo', 'Subagyo', 'Kusuma', 'Putra', 'Putri', 'Hidayat', 'Saputra', 'Permana', 'Suryani', 'Mulyono', 'Wibowo', 'Nugroho', 'Handayani', 'Lestari'];

        $diagnoses = [
            'DHF (Demam Berdarah Dengue)',
            'Post Op Appendektomi',
            'Pneumonia Komunitas',
            'Gagal Jantung Kongestif (CHF)',
            'Stroke Iskemik',
            'Diabetes Mellitus Tipe 2',
            'CKD (Gagal Ginjal Kronis) Stage V',
            'Bronkopneumonia',
            'Post Op Cholecystectomy',
            'Infark Miokard Akut (IMA)',
            'Fraktur Femur Sinistra',
            'Asthma Bronkial Eksaserbasi',
            'Sepsis Severe',
            'Gastroenteritis Akut (GEA)',
            'Hypertension Heart Disease',
        ];

        $patientIndex = 1000;

        // Truncate tables for a clean seed
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        PatientTransfer::truncate();
        DailyCensus::truncate();
        Admission::truncate();
        Patient::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($rooms as $room) {
            // Variasikan kelas untuk ruangan ini agar setiap tab sheet kelas terisi data dummy
            $roomClasses = [$room->room_class];
            if ($room->category === 'Grand Paviliun') {
                $roomClasses = ['VIP', 'VVIP', 'Kelas 1'];
            } elseif (str_contains($room->category, 'IPJT')) {
                $roomClasses = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3'];
            } elseif (str_contains($room->category, 'IPIT')) {
                $roomClasses = ['Kelas 1', 'Kelas 2', 'Kelas 3'];
            } else {
                $roomClasses = array_unique([$room->room_class, 'Kelas 1', 'Kelas 2', 'Kelas 3', 'VIP']);
            }
            $roomClasses = array_values($roomClasses);

            // Seed 4-6 Pasien Aktif Dirawat di setiap ruangan (terbagi di berbagai kelas)
            $activeCount = max(4, count($roomClasses) * 2);
            for ($i = 0; $i < $activeCount; $i++) {
                $gender = rand(0, 1) ? 'L' : 'P';
                $fn = ($gender === 'L') ? $firstNamesL[array_rand($firstNamesL)] : $firstNamesP[array_rand($firstNamesP)];
                $ln = $lastNames[array_rand($lastNames)];
                $patientIndex++;

                $patient = Patient::create([
                    'rm_number' => sprintf('120%05d', $patientIndex),
                    'name' => "$fn $ln",
                    'gender' => $gender,
                    'address' => 'Kota Malang / Kab. Malang',
                    'phone' => '08' . rand(111111111, 999999999),
                ]);

                $assignedClass = $roomClasses[$i % count($roomClasses)];

                // Tanggal MRS: Sebagian MRS bulan lalu (Carried Over), sebagian bulan ini
                $isLastMonth = rand(0, 3) === 0;
                $admissionDate = $isLastMonth 
                    ? Carbon::now()->subMonth()->setDay(rand(1, 28))->setHour(rand(7, 21))->setMinute(rand(0, 59))
                    : Carbon::now()->subDays(rand(1, 15))->setHour(rand(7, 21))->setMinute(rand(0, 59));

                Admission::create([
                    'patient_id' => $patient->id,
                    'current_room_id' => $room->id,
                    'initial_room_id' => $room->id,
                    'room_class' => $assignedClass,
                    'admission_date' => $admissionDate,
                    'status' => 'active',
                    'diagnosis' => $diagnoses[array_rand($diagnoses)],
                ]);
            }

            // Seed 5-8 Pasien KRS (Keluar/Discharged/Deceased) di setiap ruangan (terbagi di berbagai kelas)
            $dischargedCount = max(5, count($roomClasses) * 2);
            for ($j = 0; $j < $dischargedCount; $j++) {
                $gender = rand(0, 1) ? 'L' : 'P';
                $fn = ($gender === 'L') ? $firstNamesL[array_rand($firstNamesL)] : $firstNamesP[array_rand($firstNamesP)];
                $ln = $lastNames[array_rand($lastNames)];
                $patientIndex++;

                $patient = Patient::create([
                    'rm_number' => sprintf('120%05d', $patientIndex),
                    'name' => "$fn $ln",
                    'gender' => $gender,
                    'address' => 'Kota Malang / Kab. Malang',
                    'phone' => '08' . rand(111111111, 999999999),
                ]);

                $assignedClass = $roomClasses[$j % count($roomClasses)];

                // Berbagai Kasus Spesifik:
                // 0: One Day Care (MRS & KRS hari yang sama)
                // 1: Pasien Meninggal < 48 jam
                // 2: Pasien Meninggal >= 48 jam
                // 3: Pasien Sembuh / Membaik (Biasa)
                // 4: Pasien Lintas Bulan (MRS bulan lalu, KRS bulan ini)
                $caseType = $j % 5;

                if ($caseType === 0) {
                    // One Day Care (ODC)
                    $admDate = Carbon::now()->subDays(rand(2, 20))->setHour(8)->setMinute(0);
                    $discDate = $admDate->copy()->setHour(17)->setMinute(30);
                    $condition = 'improved';
                    $status = 'discharged';
                    $los = 1;
                } elseif ($caseType === 1) {
                    // Meninggal < 48 jam (misal 20 jam)
                    $admDate = Carbon::now()->subDays(rand(2, 20))->setHour(10)->setMinute(0);
                    $discDate = $admDate->copy()->addHours(20);
                    $condition = 'deceased_under_48h';
                    $status = 'deceased';
                    $los = 1;
                } elseif ($caseType === 2) {
                    // Meninggal >= 48 jam (misal 72 jam / 3 hari)
                    $admDate = Carbon::now()->subDays(rand(5, 20))->setHour(9)->setMinute(0);
                    $discDate = $admDate->copy()->addDays(3)->setHour(11)->setMinute(0);
                    $condition = 'deceased_over_48h';
                    $status = 'deceased';
                    $los = 3;
                } elseif ($caseType === 3) {
                    // Sembuh biasa (misal 4 hari)
                    $admDate = Carbon::now()->subDays(rand(5, 20))->setHour(10)->setMinute(0);
                    $discDate = $admDate->copy()->addDays(4)->setHour(14)->setMinute(0);
                    $condition = 'cured';
                    $status = 'discharged';
                    $los = 4;
                } else {
                    // Pasien Lintas Bulan (MRS bulan lalu)
                    $admDate = Carbon::now()->subMonth()->setDay(rand(15, 25))->setHour(9)->setMinute(0);
                    $discDate = Carbon::now()->setDay(rand(2, 10))->setHour(11)->setMinute(0);
                    $condition = 'cured';
                    $status = 'discharged';
                    $los = (int) $admDate->copy()->startOfDay()->diffInDays($discDate->copy()->startOfDay());
                }

                Admission::create([
                    'patient_id' => $patient->id,
                    'current_room_id' => $room->id,
                    'initial_room_id' => $room->id,
                    'room_class' => $assignedClass,
                    'admission_date' => $admDate,
                    'discharge_date' => $discDate,
                    'discharge_condition' => $condition,
                    'status' => $status,
                    'length_of_stay' => $los,
                    'diagnosis' => $diagnoses[array_rand($diagnoses)],
                ]);
            }

            // Seed Daily Censuses (Rekap Sensus Harian) untuk bulan berjalan
            $today = Carbon::today();
            for ($day = 1; $day <= $today->day; $day++) {
                $cDate = Carbon::create($today->year, $today->month, $day)->toDateString();
                DailyCensus::updateOrCreate(
                    ['room_id' => $room->id, 'census_date' => $cDate],
                    [
                        'initial_patients' => rand(5, 12),
                        'admissions_count' => rand(0, 3),
                        'transfers_in_count' => rand(0, 2),
                        'transfers_out_count' => rand(0, 2),
                        'discharges_count' => rand(0, 2),
                        'deaths_under_48h' => rand(0, 1),
                        'deaths_over_48h' => rand(0, 1),
                        'remaining_patients' => rand(5, 12),
                        'care_days' => rand(5, 12),
                        'total_length_of_stay' => rand(5, 20),
                    ]
                );
            }
        }

        // Seed 5-8 Mutasi Pindahan Pasien Antar Ruang (Pending & Accepted)
        $allAdmissions = Admission::where('status', 'active')->get();
        if ($allAdmissions->count() >= 5) {
            $sampleAdmissions = $allAdmissions->random(min(8, $allAdmissions->count()));
            foreach ($sampleAdmissions as $adm) {
                $targetRoom = Room::where('id', '!=', $adm->current_room_id)->inRandomOrder()->first();
                if ($targetRoom) {
                    $isPending = rand(0, 1) === 1;
                    PatientTransfer::create([
                        'admission_id' => $adm->id,
                        'from_room_id' => $adm->current_room_id,
                        'to_room_id' => $targetRoom->id,
                        'transfer_date' => Carbon::now()->subHours(rand(1, 48)),
                        'status' => $isPending ? 'pending' : 'accepted',
                        'accepted_at' => $isPending ? null : Carbon::now()->subHours(rand(1, 10)),
                        'notes' => 'Alih perawatan / perbaikan kondisi indikasi medis',
                    ]);

                    if (!$isPending) {
                        $adm->current_room_id = $targetRoom->id;
                        $adm->save();
                    }
                }
            }
        }
    }
}
