<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CensusCalculatorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_one_day_care_has_length_of_stay_of_1(): void
    {
        $room = Room::create([
            'name' => 'Ruang Barito',
            'code' => 'R-BRT',
            'category' => 'Perawatan Umum',
            'floor' => 'Lantai 2',
            'capacity' => 10,
        ]);

        $patient = Patient::create([
            'rm_number' => 'RM-001',
            'name' => 'Pasien ODC',
            'gender' => 'L',
        ]);

        // Same day admission and discharge
        $admission = Admission::create([
            'patient_id' => $patient->id,
            'current_room_id' => $room->id,
            'admission_date' => '2026-09-22 08:00:00',
            'discharge_date' => '2026-09-22 16:00:00',
            'status' => 'discharged',
        ]);

        $this->assertEquals(1, $admission->calculateLengthOfStay());
    }

    public function test_cross_month_length_of_stay_calculation(): void
    {
        $room = Room::create([
            'name' => 'Ruang Barito',
            'code' => 'R-BRT',
            'category' => 'Perawatan Umum',
            'floor' => 'Lantai 2',
            'capacity' => 10,
        ]);

        $patient = Patient::create([
            'rm_number' => 'RM-002',
            'name' => 'Pasien Lintas Bulan',
            'gender' => 'P',
        ]);

        // MRS 28 August, KRS 5 September -> 8 days
        $admission = Admission::create([
            'patient_id' => $patient->id,
            'current_room_id' => $room->id,
            'admission_date' => '2026-08-28 10:00:00',
            'discharge_date' => '2026-09-05 14:00:00',
            'status' => 'discharged',
        ]);

        $this->assertEquals(8, $admission->calculateLengthOfStay());
    }

    public function test_automatic_deceased_category_under_and_over_48h(): void
    {
        $room = Room::create([
            'name' => 'Ruang Barito',
            'code' => 'R-BRT',
            'category' => 'Perawatan Umum',
            'floor' => 'Lantai 2',
            'capacity' => 10,
        ]);

        $patient = Patient::create([
            'rm_number' => 'RM-003',
            'name' => 'Pasien Meninggal Test',
            'gender' => 'L',
        ]);

        // MRS 2026-09-22 08:00, KRS 2026-09-23 10:00 (26 hours < 48h)
        $admission1 = Admission::create([
            'patient_id' => $patient->id,
            'current_room_id' => $room->id,
            'admission_date' => '2026-09-22 08:00:00',
        ]);
        $this->assertEquals('deceased_under_48h', $admission1->determineDeceasedCategory('2026-09-23 10:00:00'));

        // MRS 2026-09-20 08:00, KRS 2026-09-23 10:00 (74 hours >= 48h)
        $admission2 = Admission::create([
            'patient_id' => $patient->id,
            'current_room_id' => $room->id,
            'admission_date' => '2026-09-20 08:00:00',
        ]);
        $this->assertEquals('deceased_over_48h', $admission2->determineDeceasedCategory('2026-09-23 10:00:00'));
    }
}
