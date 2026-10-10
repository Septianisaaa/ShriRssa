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
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        $firstRoom = $rooms->first();

        // 1. Seed Petugas SHRI (Admin)
        User::updateOrCreate(
            ['email' => 'shri@rssa.go.id'],
            [
                'name' => 'Petugas SHRI (Admin)',
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
        AuditTrail::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($rooms as $room) {
            // Variasikan kelas untuk ruangan ini agar setiap tab sheet kelas terisi data dummy
            $roomClasses = [$room->room_class];
            if ($room->installation === 'Grand Paviliun') {
                $roomClasses = ['VIP', 'VVIP', 'Kelas 1'];
            } elseif (str_contains($room->installation, 'IPJT')) {
                $roomClasses = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3'];
            } elseif (str_contains($room->installation, 'IPIT')) {
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

        // Seed 8-10 Mutasi Pindahan Pasien Antar Ruang (Pending, Accepted, Rejected)
        $allAdmissions = Admission::where('status', 'active')->get();
        if ($allAdmissions->count() >= 5) {
            $sampleAdmissions = $allAdmissions->random(min(10, $allAdmissions->count()));
            $index = 0;
            foreach ($sampleAdmissions as $adm) {
                $targetRoom = Room::where('id', '!=', $adm->current_room_id)->inRandomOrder()->first();
                if ($targetRoom) {
                    $statusType = $index % 3; // 0: pending, 1: accepted, 2: rejected
                    $status = ($statusType === 0) ? 'pending' : (($statusType === 1) ? 'accepted' : 'rejected');
                    $transferDate = Carbon::now()->subHours(rand(12, 72));
                    $confirmedAt = ($status === 'pending') ? null : $transferDate->copy()->addHours(rand(1, 8));

                    PatientTransfer::create([
                        'admission_id' => $adm->id,
                        'from_room_id' => $adm->current_room_id,
                        'to_room_id' => $targetRoom->id,
                        'transfer_date' => $transferDate,
                        'status' => $status,
                        'accepted_at' => $confirmedAt,
                        'notes' => 'Alih perawatan / perbaikan kondisi indikasi medis',
                    ]);

                    if ($status === 'accepted') {
                        $adm->current_room_id = $targetRoom->id;
                        $adm->save();
                    }
                    $index++;
                }
            }
        }

        // Seed Catatan Aktivitas Awal (Audit Trail)
        $superadmin = User::where('role', 'superadmin')->first();
        $adminRuang = User::where('role', 'admin')->first();

        $sampleLogs = [
            [
                'user' => $superadmin,
                'action' => 'login',
                'module' => 'Autentikasi',
                'desc' => 'Login berhasil sebagai Petugas SHRI (Admin)',
                'method' => 'POST',
                'url' => '/shri/login',
                'values' => ['login' => 'shri@rssa.go.id'],
                'time' => Carbon::now()->subMinutes(120),
            ],
            [
                'user' => $superadmin,
                'action' => 'create',
                'module' => 'Kelola Ruangan',
                'desc' => 'Menambahkan instalasi baru: IPIT (Infeksi Terpadu)',
                'method' => 'POST',
                'url' => '/shri/kelola-ruangan/instalasi',
                'values' => ['name' => 'IPIT (Infeksi Terpadu)'],
                'time' => Carbon::now()->subMinutes(115),
            ],
            [
                'user' => $superadmin,
                'action' => 'create',
                'module' => 'Kelola Ruangan',
                'desc' => 'Menambahkan ruangan rawat inap: ICU Infeksi Melati (IPIT-MEL-K1)',
                'method' => 'POST',
                'url' => '/shri/kelola-ruangan/ruangan',
                'values' => ['name' => 'ICU Infeksi Melati', 'code' => 'IPIT-MEL-K1', 'capacity' => 6],
                'time' => Carbon::now()->subMinutes(110),
            ],
            [
                'user' => $superadmin,
                'action' => 'create',
                'module' => 'Kelola Akun',
                'desc' => 'Mendaftarkan akun Admin Ruangan baru: Admin ICU Infeksi Melati (@adminruang)',
                'method' => 'POST',
                'url' => '/shri/users/admin-ruang',
                'values' => ['name' => 'Admin ICU Infeksi Melati', 'username' => 'adminruang', 'email' => 'admin.ruang@rssa.go.id'],
                'time' => Carbon::now()->subMinutes(105),
            ],
            [
                'user' => $adminRuang ?? $superadmin,
                'action' => 'login',
                'module' => 'Autentikasi',
                'desc' => 'Login berhasil sebagai Admin Ruangan (ICU Infeksi Melati)',
                'method' => 'POST',
                'url' => '/admin-ruang/login',
                'values' => ['login' => 'adminruang'],
                'time' => Carbon::now()->subMinutes(90),
            ],
            [
                'user' => $adminRuang ?? $superadmin,
                'action' => 'create',
                'module' => 'Sensus Harian',
                'desc' => 'Pendaftaran pasien rawat inap baru: Budi Santoso (No. RM: 12001001)',
                'method' => 'POST',
                'url' => '/admin-ruang/sensus/patient',
                'values' => ['name' => 'Budi Santoso', 'rm_number' => '12001001', 'room_class' => 'Kelas 1', 'diagnosis' => 'DHF (Demam Berdarah Dengue)'],
                'time' => Carbon::now()->subMinutes(75),
            ],
            [
                'user' => $adminRuang ?? $superadmin,
                'action' => 'create',
                'module' => 'Mutasi Pasien',
                'desc' => 'Pengajuan mutasi / pindah ruangan pasien antar bangsal',
                'method' => 'POST',
                'url' => '/admin-ruang/transfers',
                'values' => ['patient_name' => 'Budi Santoso', 'target_room' => 'Wijaya Kusuma'],
                'time' => Carbon::now()->subMinutes(50),
            ],
            [
                'user' => $superadmin,
                'action' => 'update',
                'module' => 'Mutasi Pasien',
                'desc' => 'Konfirmasi penerimaan (terima) mutasi pasien ke ruangan',
                'method' => 'POST',
                'url' => '/shri/transfers/1/accept',
                'values' => ['status' => 'accepted'],
                'time' => Carbon::now()->subMinutes(35),
            ],
            [
                'user' => $adminRuang ?? $superadmin,
                'action' => 'update',
                'module' => 'Sensus Harian',
                'desc' => 'Pemulangan pasien (KRS) dengan kondisi: Sembuh / Membaik',
                'method' => 'POST',
                'url' => '/admin-ruang/sensus/discharge/1',
                'values' => ['discharge_condition' => 'cured', 'status' => 'discharged'],
                'time' => Carbon::now()->subMinutes(20),
            ],
            [
                'user' => $superadmin,
                'action' => 'update',
                'module' => 'Profil Pengguna',
                'desc' => 'Pembaruan profil pengguna',
                'method' => 'POST',
                'url' => '/shri/profile',
                'values' => ['name' => 'Petugas SHRI (Admin)', 'phone' => '081234567890'],
                'time' => Carbon::now()->subMinutes(5),
            ],
        ];

        foreach ($sampleLogs as $log) {
            AuditTrail::create([
                'user_id' => $log['user']?->id,
                'username' => $log['user']?->username ?? $log['user']?->email,
                'user_name' => $log['user']?->name,
                'action' => $log['action'],
                'module' => $log['module'],
                'description' => $log['desc'],
                'old_values' => null,
                'new_values' => $log['values'],
                'method' => $log['method'],
                'url' => $log['url'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
                'created_at' => $log['time'],
                'updated_at' => $log['time'],
            ]);
        }
    }
}
