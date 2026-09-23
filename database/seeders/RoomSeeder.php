<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $roomsData = [
            // A. INSTALASI PELAYANAN INFEKSI TERPADU (IPIT)
            ['name' => 'ICU Infeksi Melati', 'code' => 'IPIT-MEL-K1', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'HCU Infeksi Melati', 'code' => 'IPIT-MEL-K2', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Wijaya Kusuma (Kelas 1)', 'code' => 'IPIT-WK-K1', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'Wijaya Kusuma (Kelas 2)', 'code' => 'IPIT-WK-K2', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Mawar (Kelas 1)', 'code' => 'IPIT-MAW-K1', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 30],
            ['name' => 'Mawar (Kelas 2)', 'code' => 'IPIT-MAW-K2', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'Mawar (Kelas 3)', 'code' => 'IPIT-MAW-K3', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 3', 'capacity' => 8],
            ['name' => 'Dahlia', 'code' => 'IPIT-DHL-K1', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 30],
            ['name' => 'Bugenvile', 'code' => 'IPIT-BGV-K3', 'category' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 3', 'capacity' => 20],

            // B. INSTALASI RAWAT INAP 1 (IRNA 1)
            ['name' => 'Nusa Dua (Kelas 1)', 'code' => 'IRNA1-ND-K1', 'category' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 20],
            ['name' => 'Nusa Dua (Kelas 2)', 'code' => 'IRNA1-ND-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'Jimbaran (Kelas 1)', 'code' => 'IRNA1-JMB-K1', 'category' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 0],
            ['name' => 'Jimbaran (Kelas 2)', 'code' => 'IRNA1-JMB-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 20],
            ['name' => 'Bunaken (Kelas 2)', 'code' => 'IRNA1-BNK-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 6],
            ['name' => 'Bunaken (Kelas 3)', 'code' => 'IRNA1-BNK-K3', 'category' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 16],
            ['name' => 'Gili Trawangan (Kelas 1)', 'code' => 'IRNA1-GT-K1', 'category' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 2],
            ['name' => 'Gili Trawangan (Kelas 2)', 'code' => 'IRNA1-GT-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'Gili Trawangan (Kelas 3)', 'code' => 'IRNA1-GT-K3', 'category' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 9],
            ['name' => 'HCU Ciliwung', 'code' => 'IRNA1-CLW-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 28],
            ['name' => 'HCU Cisadane', 'code' => 'IRNA1-CSD-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 40],
            ['name' => 'HCU Mahakam', 'code' => 'IRNA1-MHK-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 20],
            ['name' => 'HCU Brantas', 'code' => 'IRNA1-BRT-K2', 'category' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 9],
            ['name' => 'Pangandaran', 'code' => 'IRNA1-PGD-K3', 'category' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 35],
            ['name' => 'Parangtritis', 'code' => 'IRNA1-PRG-K3', 'category' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 30],
            ['name' => 'Losari', 'code' => 'IRNA1-LSR-K3', 'category' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 14],

            // C. INSTALASI RAWAT INAP 2 (IRNA 2)
            ['name' => 'Semeru', 'code' => 'IRNA2-SMR-K3', 'category' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 43],
            ['name' => 'Rinjani (Kelas 1)', 'code' => 'IRNA2-RNJ-K1', 'category' => 'IRNA 2', 'room_class' => 'Kelas 1', 'capacity' => 2],
            ['name' => 'Rinjani (Kelas 2)', 'code' => 'IRNA2-RNJ-K2', 'category' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 2],
            ['name' => 'Rinjani (Kelas 3)', 'code' => 'IRNA2-RNJ-K3', 'category' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 18],
            ['name' => 'HCU Kawi', 'code' => 'IRNA2-KWI-K2', 'category' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 9],
            ['name' => 'Kerinci (Kelas 2)', 'code' => 'IRNA2-KRC-K2', 'category' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Kerinci (Kelas 3)', 'code' => 'IRNA2-KRC-K3', 'category' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 12],
            ['name' => 'Galunggung', 'code' => 'IRNA2-GLG-K3', 'category' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 16],
            ['name' => 'Bromo', 'code' => 'IRNA2-BRM-K3', 'category' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 42],

            // D. INSTALASI RAWAT INAP 3 (IRNA 3)
            ['name' => 'Toba Ibu (Kelas 1)', 'code' => 'IRNA3-TOB-IBU-K1', 'category' => 'IRNA 3', 'room_class' => 'Kelas 1', 'capacity' => 10],
            ['name' => 'Toba Ibu (Kelas 2)', 'code' => 'IRNA3-TOB-IBU-K2', 'category' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Toba Ibu (Kelas 3)', 'code' => 'IRNA3-TOB-IBU-K3', 'category' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 0],
            ['name' => 'Toba Bayi (Kelas 1)', 'code' => 'IRNA3-TOB-BYI-K1', 'category' => 'IRNA 3', 'room_class' => 'Kelas 1', 'capacity' => 1],
            ['name' => 'Toba Bayi (Kelas 2)', 'code' => 'IRNA3-TOB-BYI-K2', 'category' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 1],
            ['name' => 'Toba Bayi (Kelas 3)', 'code' => 'IRNA3-TOB-BYI-K3', 'category' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 0],
            ['name' => 'HCU Ranu Grati', 'code' => 'IRNA3-RNG-K2', 'category' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Singkarak', 'code' => 'IRNA3-SGK-K3', 'category' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 30],
            ['name' => 'Ranukumbolo Bayi', 'code' => 'IRNA3-RNK-BYI-K3', 'category' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 1],
            ['name' => 'Ranukumbolo Ibu', 'code' => 'IRNA3-RNK-IBU-K3', 'category' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 18],

            // E. INSTALASI RAWAT INAP 4 (IRNA 4)
            ['name' => 'Kelimutu (Kelas 1)', 'code' => 'IRNA4-KLM-K1', 'category' => 'IRNA 4', 'room_class' => 'Kelas 1', 'capacity' => 16],
            ['name' => 'Kelimutu (Kelas 2)', 'code' => 'IRNA4-KLM-K2', 'category' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'HCU Sarangan', 'code' => 'IRNA4-SRG-K2', 'category' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 11],
            ['name' => 'Tondano', 'code' => 'IRNA4-TDN-K3', 'category' => 'IRNA 4', 'room_class' => 'Kelas 3', 'capacity' => 50],
            ['name' => 'HCU Ranu Pani', 'code' => 'IRNA4-RNP-K2', 'category' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 38],
            ['name' => 'Maninjau', 'code' => 'IRNA4-MNJ-K1', 'category' => 'IRNA 4', 'room_class' => 'Kelas 1', 'capacity' => 12],

            // F. INSTALASI ANESTESIOLOGI DAN TERAPI INTENSIF
            ['name' => 'ICU Kapuas A', 'code' => 'INT-KPA-K1', 'category' => 'Anestesiologi & Intensif', 'room_class' => 'Kelas 1', 'capacity' => 16],
            ['name' => 'ICU Kapuas B', 'code' => 'INT-KPB-K1', 'category' => 'Anestesiologi & Intensif', 'room_class' => 'Kelas 1', 'capacity' => 9],
            ['name' => 'ICU Kapuas C', 'code' => 'INT-KPC-K1', 'category' => 'Anestesiologi & Intensif', 'room_class' => 'Kelas 1', 'capacity' => 14],
            ['name' => 'PICU Krakatau', 'code' => 'INT-KRK-K1', 'category' => 'Anestesiologi & Intensif', 'room_class' => 'Kelas 1', 'capacity' => 17],

            // G. INSTALASI PELAYANAN JANTUNG TERPADU (IPJT)
            ['name' => 'Barito (VIP)', 'code' => 'IPJT-BRT-VIP', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'VIP', 'capacity' => 2],
            ['name' => 'Barito (Kelas 1)', 'code' => 'IPJT-BRT-K1', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'Barito (Kelas 2)', 'code' => 'IPJT-BRT-K2', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'Barito (Kelas 3)', 'code' => 'IPJT-BRT-K3', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 3', 'capacity' => 11],
            ['name' => 'Bengawan Solo (Kelas 1)', 'code' => 'IPJT-BWS-K1', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 1', 'capacity' => 0],
            ['name' => 'Bengawan Solo (Kelas 2)', 'code' => 'IPJT-BWS-K2', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'HCU Bengawan Solo', 'code' => 'IPJT-HCU-BWS-K2', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'CVCU Musi', 'code' => 'IPJT-MUSI-K1', 'category' => 'Pelayanan Jantung (IPJT)', 'room_class' => 'Kelas 1', 'capacity' => 13],

            // H. INSTALASI GAWAT DARURAT (IGD)
            ['name' => 'ROE', 'code' => 'IGD-ROE-K2', 'category' => 'Gawat Darurat (IGD)', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'HCU Membramo', 'code' => 'IGD-MBR-K2', 'category' => 'Gawat Darurat (IGD)', 'room_class' => 'Kelas 2', 'capacity' => 10],

            // I. GRAND PAVILIUN
            ['name' => 'Paviliun Lantai 2 (VIP)', 'code' => 'PAV-L2-VIP', 'category' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 5],
            ['name' => 'Paviliun Lantai 2 (Kelas 1)', 'code' => 'PAV-L2-K1', 'category' => 'Grand Paviliun', 'room_class' => 'Kelas 1', 'capacity' => 24],
            ['name' => 'Paviliun Lantai 3 (VIP)', 'code' => 'PAV-L3-VIP', 'category' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 24],
            ['name' => 'Paviliun Lantai 4 (VIP)', 'code' => 'PAV-L4-VIP', 'category' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 0],
            ['name' => 'Paviliun Lantai 6 (VVIP)', 'code' => 'PAV-L6-VVIP', 'category' => 'Grand Paviliun', 'room_class' => 'VVIP', 'capacity' => 3],
            ['name' => 'Paviliun Lantai 6 (VIP)', 'code' => 'PAV-L6-VIP', 'category' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 10],
            ['name' => 'Paviliun Lantai 7 (VIP)', 'code' => 'PAV-L7-VIP', 'category' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 3],
        ];

        foreach ($roomsData as $data) {
            Room::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'room_class' => $data['room_class'],
                    'capacity' => $data['capacity'],
                    'is_active' => true,
                ]
            );
        }
    }
}
