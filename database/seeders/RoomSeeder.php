<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // Lantai Perawatan
            ['name' => 'Ruang Perawatan Lantai 2', 'code' => 'LT2', 'category' => 'Lantai', 'floor' => 'Lantai 2', 'capacity' => 20],
            ['name' => 'Ruang Perawatan Lantai 3', 'code' => 'LT3', 'category' => 'Lantai', 'floor' => 'Lantai 3', 'capacity' => 24],
            ['name' => 'Ruang Perawatan Lantai 4', 'code' => 'LT4', 'category' => 'Lantai', 'floor' => 'Lantai 4', 'capacity' => 24],
            ['name' => 'Ruang Perawatan Lantai 6', 'code' => 'LT6', 'category' => 'Lantai', 'floor' => 'Lantai 6', 'capacity' => 28],
            ['name' => 'Ruang Perawatan Lantai 7', 'code' => 'LT7', 'category' => 'Lantai', 'floor' => 'Lantai 7', 'capacity' => 28],
            ['name' => 'Ruang Perawatan Lantai 8', 'code' => 'LT8', 'category' => 'Lantai', 'floor' => 'Lantai 8', 'capacity' => 30],

            // Perawatan Intensif (ICU & HCU)
            ['name' => 'ICU Kapuas A', 'code' => 'ICU-KPA', 'category' => 'Intensive', 'floor' => 'Gedung Intensif', 'capacity' => 8],
            ['name' => 'ICU Kapuas B', 'code' => 'ICU-KPB', 'category' => 'Intensive', 'floor' => 'Gedung Intensif', 'capacity' => 8],
            ['name' => 'ICU Kapuas C', 'code' => 'ICU-KPC', 'category' => 'Intensive', 'floor' => 'Gedung Intensif', 'capacity' => 8],
            ['name' => 'ICU Infeksi Melati', 'code' => 'ICU-MEL', 'category' => 'Intensive', 'floor' => 'Gedung Melati', 'capacity' => 6],
            ['name' => 'CVCU Musi', 'code' => 'CVCU-MUSI', 'category' => 'Intensive', 'floor' => 'Gedung Jantung', 'capacity' => 10],
            ['name' => 'PICU Krakatau', 'code' => 'PICU-KRAK', 'category' => 'Intensive', 'floor' => 'Gedung Anak', 'capacity' => 8],
            ['name' => 'HCU Begawan Solo', 'code' => 'HCU-BS', 'category' => 'HCU', 'floor' => 'Lantai 3', 'capacity' => 10],
            ['name' => 'HCU Membramo', 'code' => 'HCU-MBR', 'category' => 'HCU', 'floor' => 'Lantai 4', 'capacity' => 10],
            ['name' => 'HCU Mahakam', 'code' => 'HCU-MHK', 'category' => 'HCU', 'floor' => 'Lantai 4', 'capacity' => 10],
            ['name' => 'HCU Cisadane', 'code' => 'HCU-CSD', 'category' => 'HCU', 'floor' => 'Lantai 6', 'capacity' => 10],
            ['name' => 'HCU Ciliwung', 'code' => 'HCU-CLW', 'category' => 'HCU', 'floor' => 'Lantai 6', 'capacity' => 10],
            ['name' => 'HCU Brantas', 'code' => 'HCU-BRN', 'category' => 'HCU', 'floor' => 'Lantai 7', 'capacity' => 10],
            ['name' => 'HCU Kawi', 'code' => 'HCU-KAWI', 'category' => 'HCU', 'floor' => 'Lantai 7', 'capacity' => 8],
            ['name' => 'HCU Ranu Grati', 'code' => 'HCU-RGR', 'category' => 'HCU', 'floor' => 'Lantai 8', 'capacity' => 8],
            ['name' => 'HCU Ranu Pani', 'code' => 'HCU-RPA', 'category' => 'HCU', 'floor' => 'Lantai 8', 'capacity' => 8],
            ['name' => 'HCU Sarangan', 'code' => 'HCU-SRG', 'category' => 'HCU', 'floor' => 'Lantai 8', 'capacity' => 8],
            ['name' => 'HCU Infeksi Melati', 'code' => 'HCU-MEL', 'category' => 'HCU', 'floor' => 'Gedung Melati', 'capacity' => 8],

            // Paviliun & VVIP (Pantai & Pulau)
            ['name' => 'Paviliun Nusa Dua', 'code' => 'PAV-ND', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 15],
            ['name' => 'Paviliun Bunaken', 'code' => 'PAV-BNK', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 12],
            ['name' => 'Paviliun Pangandaran', 'code' => 'PAV-PGD', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 12],
            ['name' => 'Paviliun Losari', 'code' => 'PAV-LSR', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 12],
            ['name' => 'Paviliun Jimbaran', 'code' => 'PAV-JMB', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 15],
            ['name' => 'Paviliun Gili Trawangan', 'code' => 'PAV-GLT', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 12],
            ['name' => 'Paviliun Parangtritis', 'code' => 'PAV-PRT', 'category' => 'Paviliun', 'floor' => 'Gedung Paviliun', 'capacity' => 12],

            // Ruang Perawatan Umum (Gunung & Danau)
            ['name' => 'Ruang Semeru', 'code' => 'R-SMR', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 3', 'capacity' => 20],
            ['name' => 'Ruang Rinjani', 'code' => 'R-RNJ', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 3', 'capacity' => 20],
            ['name' => 'Ruang Kerinci', 'code' => 'R-KRC', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 4', 'capacity' => 20],
            ['name' => 'Ruang Galunggung', 'code' => 'R-GLG', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 4', 'capacity' => 20],
            ['name' => 'Ruang Bromo', 'code' => 'R-BRM', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 6', 'capacity' => 20],
            ['name' => 'Ruang Ranu Kumbolo Ibu', 'code' => 'R-RKI', 'category' => 'Perawatan Ibu', 'floor' => 'Lantai 7', 'capacity' => 16],
            ['name' => 'Ruang Ranu Kumbolo Bayi', 'code' => 'R-RKB', 'category' => 'Perawatan Bayi', 'floor' => 'Lantai 7', 'capacity' => 16],
            ['name' => 'Ruang Toba Ibu', 'code' => 'R-TBI', 'category' => 'Perawatan Ibu', 'floor' => 'Lantai 7', 'capacity' => 16],
            ['name' => 'Ruang Toba Bayi', 'code' => 'R-TBB', 'category' => 'Perawatan Bayi', 'floor' => 'Lantai 7', 'capacity' => 16],
            ['name' => 'Ruang Singkarak', 'code' => 'R-SGK', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 8', 'capacity' => 18],
            ['name' => 'Ruang Kelimutu', 'code' => 'R-KLM', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 8', 'capacity' => 18],
            ['name' => 'Ruang Maninjau', 'code' => 'R-MNJ', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 8', 'capacity' => 18],
            ['name' => 'Ruang Tondano', 'code' => 'R-TDN', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 8', 'capacity' => 18],
            ['name' => 'Ruang Bugenvile', 'code' => 'R-BGV', 'category' => 'Perawatan Umum', 'floor' => 'Gedung Bugenvile', 'capacity' => 16],
            ['name' => 'Ruang Barito', 'code' => 'R-BRT', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 2', 'capacity' => 20],
            ['name' => 'Ruang Begawan Solo', 'code' => 'R-BGS', 'category' => 'Perawatan Umum', 'floor' => 'Lantai 3', 'capacity' => 20],
            ['name' => 'Ruang ROE', 'code' => 'R-ROE', 'category' => 'Khusus', 'floor' => 'Gedung Khusus', 'capacity' => 12],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['code' => $room['code']], $room);
        }
    }
}
