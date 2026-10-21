<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $roomsData = [

            // A. Instalasi Pelayanan Infeksi Terpadu (IPIT)
            ['name' => 'ICU Infeksi Melati', 'code' => 'IPIT-MEL-K1', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'HCU Infeksi Melati', 'code' => 'IPIT-MEL-K2', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Wijaya Kusuma', 'code' => 'IPIT-WK-K1', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'Wijaya Kusuma', 'code' => 'IPIT-WK-K2', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Mawar', 'code' => 'IPIT-MAW-K1', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 30],
            ['name' => 'Mawar', 'code' => 'IPIT-MAW-K2', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'Mawar', 'code' => 'IPIT-MAW-K3', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 3', 'capacity' => 8],
            ['name' => 'Dahlia', 'code' => 'IPIT-DHL-K1', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 1', 'capacity' => 38],
            ['name' => 'Bugenvile', 'code' => 'IPIT-BGV-K3', 'installation' => 'IPIT (Infeksi Terpadu)', 'room_class' => 'Kelas 3', 'capacity' => 20],

            // B. Instalasi Rawat Inap 1 (IRNA 1)
            ['name' => 'Nusa Dua', 'code' => 'IRNA1-ND-K1', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 20],
            ['name' => 'Nusa Dua', 'code' => 'IRNA1-ND-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'Jimbaran', 'code' => 'IRNA1-JMB-K1', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 0],
            ['name' => 'Jimbaran', 'code' => 'IRNA1-JMB-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 28],
            ['name' => 'Bunaken', 'code' => 'IRNA1-BNK-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 6],
            ['name' => 'Bunaken', 'code' => 'IRNA1-BNK-K3', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 16],
            ['name' => 'Gili Trawangan', 'code' => 'IRNA1-GT-K1', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 1', 'capacity' => 2],
            ['name' => 'Gili Trawangan', 'code' => 'IRNA1-GT-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'Gili Trawangan', 'code' => 'IRNA1-GT-K3', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 9],
            ['name' => 'HCU Ciliwung', 'code' => 'IRNA1-CLW-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 28],
            ['name' => 'HCU Cisadane', 'code' => 'IRNA1-CSD-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 40],
            ['name' => 'HCU Mahakam', 'code' => 'IRNA1-MHK-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 20],
            ['name' => 'HCU Brantas', 'code' => 'IRNA1-BRT-K2', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 2', 'capacity' => 9],
            ['name' => 'Pangandaran', 'code' => 'IRNA1-PGD-K3', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 35],
            ['name' => 'Parangtritis', 'code' => 'IRNA1-PRG-K3', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 30],
            ['name' => 'Losari', 'code' => 'IRNA1-LSR-K3', 'installation' => 'IRNA 1', 'room_class' => 'Kelas 3', 'capacity' => 14],

            // C. Instalasi Rawat Inap 2 (IRNA 2)
            ['name' => 'Semeru', 'code' => 'IRNA2-SMR-K3', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 43],
            ['name' => 'Rinjani', 'code' => 'IRNA2-RNJ-K1', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 1', 'capacity' => 2],
            ['name' => 'Rinjani', 'code' => 'IRNA2-RNJ-K2', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 2],
            ['name' => 'Rinjani', 'code' => 'IRNA2-RNJ-K3', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 18],
            ['name' => 'HCU Kawi', 'code' => 'IRNA2-KWI-K2', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 9],
            ['name' => 'Kerinci', 'code' => 'IRNA2-KRC-K2', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Kerinci', 'code' => 'IRNA2-KRC-K3', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 18],
            ['name' => 'Galunggung', 'code' => 'IRNA2-GLG-K3', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 16],
            ['name' => 'Bromo', 'code' => 'IRNA2-BRM-K3', 'installation' => 'IRNA 2', 'room_class' => 'Kelas 3', 'capacity' => 42],

            // D. Instalasi Rawat Inap 3 (IRNA 3)
            ['name' => 'Toba (Ibu)', 'code' => 'IRNA3-TOB-IBU-K1', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 1', 'capacity' => 10],
            ['name' => 'Toba (Ibu)', 'code' => 'IRNA3-TOB-IBU-K2', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Toba (Ibu)', 'code' => 'IRNA3-TOB-IBU-K3', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 0],
            ['name' => 'Toba (Bayi)', 'code' => 'IRNA3-TOB-BYI-K1', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 1', 'capacity' => 1],
            ['name' => 'Toba (Bayi)', 'code' => 'IRNA3-TOB-BYI-K2', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 1],
            ['name' => 'Toba (Bayi)', 'code' => 'IRNA3-TOB-BYI-K3', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 0],
            ['name' => 'HCU Ranu Grati', 'code' => 'IRNA3-RNG-K2', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'Singkarak', 'code' => 'IRNA3-SGK-K3', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 30],
            ['name' => 'Ranukumbolo (Bayi)', 'code' => 'IRNA3-RNK-BYI-K3', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 1],
            ['name' => 'Ranukumbolo (Ibu)', 'code' => 'IRNA3-RNK-IBU-K3', 'installation' => 'IRNA 3', 'room_class' => 'Kelas 3', 'capacity' => 18],

            // E. Instalasi Rawat Inap 4 (IRNA 4)
            ['name' => 'Kelimutu', 'code' => 'IRNA4-KLM-K1', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 1', 'capacity' => 16],
            ['name' => 'Kelimutu', 'code' => 'IRNA4-KLM-K2', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 8],
            ['name' => 'HCU Sarangan', 'code' => 'IRNA4-SRG-K2', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 11, 'code' => 'IRNA4-SRG-K2'],
            ['name' => 'Tondano', 'code' => 'IRNA4-TDN-K3', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 3', 'capacity' => 50],
            ['name' => 'HCU Ranu Pani', 'code' => 'IRNA4-RNP-K2', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 2', 'capacity' => 38],
            ['name' => 'Maninjau', 'code' => 'IRNA4-MNJ-K1', 'installation' => 'IRNA 4', 'room_class' => 'Kelas 1', 'capacity' => 12],

            // F. Instalasi Anestesiologi dan Terapi Intensif
            ['name' => 'ICU Kapuas A', 'code' => 'INT-KPA-K1', 'installation' => 'ITI (Terapi Intensif)', 'room_class' => 'Kelas 1', 'capacity' => 16],
            ['name' => 'ICU Kapuas B', 'code' => 'INT-KPB-K1', 'installation' => 'ITI (Terapi Intensif)', 'room_class' => 'Kelas 1', 'capacity' => 9],
            ['name' => 'ICU Kapuas C', 'code' => 'INT-KPC-K1', 'installation' => 'ITI (Terapi Intensif)', 'room_class' => 'Kelas 1', 'capacity' => 14],
            ['name' => 'PICU Krakatau', 'code' => 'INT-KRK-K1', 'installation' => 'ITI (Terapi Intensif)', 'room_class' => 'Kelas 1', 'capacity' => 17],

            // G. Instalasi Pelayanan Jantung Terpadu (IPJT)
            ['name' => 'Barito', 'code' => 'IPJT-BRT-VIP', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'VIP', 'capacity' => 2],
            ['name' => 'Barito', 'code' => 'IPJT-BRT-K1', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 1', 'capacity' => 6],
            ['name' => 'Barito', 'code' => 'IPJT-BRT-K2', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'Barito', 'code' => 'IPJT-BRT-K3', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 3', 'capacity' => 11],
            ['name' => 'Bengawan Solo', 'code' => 'IPJT-BWS-K1', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 1', 'capacity' => 0],
            ['name' => 'Bengawan Solo', 'code' => 'IPJT-BWS-K2', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'HCU Bengawan Solo', 'code' => 'IPJT-HCU-BWS-K2', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 2', 'capacity' => 4],
            ['name' => 'CVCU Musi', 'code' => 'IPJT-MUSI-K1', 'installation' => 'IPJT (Pelayanan Jantung)', 'room_class' => 'Kelas 1', 'capacity' => 13],

            // H. Instalasi Gawat Darurat (IGD)
            ['name' => 'ROE', 'code' => 'IGD-ROE-K2', 'installation' => 'IGD (Gawat Darurat)', 'room_class' => 'Kelas 2', 'capacity' => 0],
            ['name' => 'HCU Membramo', 'code' => 'IGD-MBR-K2', 'installation' => 'IGD (Gawat Darurat)', 'room_class' => 'Kelas 2', 'capacity' => 10],

            // I. Grand Paviliun
            ['name' => 'Lantai 2', 'code' => 'PAV-L2-VIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 5],
            ['name' => 'Lantai 2', 'code' => 'PAV-L2-K1', 'installation' => 'Grand Paviliun', 'room_class' => 'Kelas 1', 'capacity' => 24],
            ['name' => 'Lantai 3', 'code' => 'PAV-L3-VIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 24],
            ['name' => 'Lantai 4', 'code' => 'PAV-L4-VIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 0],
            ['name' => 'Lantai 6', 'code' => 'PAV-L6-VVIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VVIP', 'capacity' => 3],
            ['name' => 'Lantai 6', 'code' => 'PAV-L6-VIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 18],
            ['name' => 'Lantai 7', 'code' => 'PAV-L7-VIP', 'installation' => 'Grand Paviliun', 'room_class' => 'VIP', 'capacity' => 3],
        ];

        foreach ($roomsData as $data) {
            Room::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'installation' => $data['installation'],
                    'room_class' => $data['room_class'],
                    'capacity' => $data['capacity'],
                    'is_active' => true,
                ]
            );
        }
    }
}