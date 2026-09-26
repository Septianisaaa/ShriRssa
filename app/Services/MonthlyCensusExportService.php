<?php

namespace App\Services;

use App\Models\Room;
use App\Models\DailyCensus;
use App\Models\Admission;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonthlyCensusExportService
{
    /**
     * Download Excel Rekap Sensus Harian Rawat Inap (SHRI) Bulanan.
     */
    public function download(int $year, int $month, ?Room $selectedRoom = null): StreamedResponse
    {
        $spreadsheet = $this->buildSpreadsheet($year, $month, $selectedRoom);

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNames[$month] ?? "Bulan_{$month}";
        $roomTag = $selectedRoom ? "_{$selectedRoom->code}" : "_SemuaRuang";
        $fileName = "Rekap_Sensus_SHRI{$roomTag}_{$year}_{$month}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function buildSpreadsheet(int $year, int $month, ?Room $selectedRoom = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthNameStr = $monthNames[$month] ?? "Bulan {$month}";

        // Get Rooms
        if ($selectedRoom) {
            $rooms = collect([$selectedRoom]);
        } else {
            $rooms = Room::where('is_active', true)->orderBy('name')->get();
            if ($rooms->isEmpty()) {
                $rooms = Room::all();
            }
        }

        $roomIds = $rooms->pluck('id');

        // Fetch existing daily censuses for the entire month in ONE single query
        $startStr = $startDate->toDateString();
        $endStr = $endDate->toDateString();

        $censusesCollection = DailyCensus::whereIn('room_id', $roomIds)
            ->whereBetween('census_date', [$startStr, $endStr])
            ->get()
            ->keyBy(fn($item) => $item->census_date . '_' . $item->room_id);

        $dailyDataByDateAndRoom = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
            foreach ($rooms as $room) {
                $key = $dateStr . '_' . $room->id;
                $c = $censusesCollection->get($key);
                if (!$c) {
                    $c = CensusCalculatorService::syncRoomDate($room, $dateStr);
                }
                $dailyDataByDateAndRoom[$d][$room->id] = $c;
            }
        }

        // Fetch all admissions active/discharged in this month for superfast in-memory payment counting
        $monthAdmissions = Admission::whereIn('current_room_id', $roomIds)
            ->where('admission_date', '<=', $endStr)
            ->where(function ($q) use ($startStr) {
                $q->whereNull('discharge_date')
                    ->orWhere('discharge_date', '>=', $startStr);
            })
            ->get();

        // Build payment data map per day
        $paymentDataByDate = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $activeOnDay = $monthAdmissions->filter(function ($a) use ($dateStr) {
                $admDate = Carbon::parse($a->admission_date)->toDateString();
                $discDate = $a->discharge_date ? Carbon::parse($a->discharge_date)->toDateString() : null;
                return $admDate <= $dateStr && ($discDate === null || $discDate >= $dateStr);
            });

            $paymentDataByDate[$d] = [
                'jkn' => $activeOnDay->filter(fn($a) => in_array(strtoupper($a->payment_method ?? ''), ['JKN', 'BPJS']))->count(),
                'spm' => $activeOnDay->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'SPM')->count(),
                'jkmm' => $activeOnDay->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'JKMM')->count(),
                'pks' => $activeOnDay->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'PKS')->count(),
                'umum' => $activeOnDay->filter(fn($a) => in_array(strtoupper($a->payment_method ?? ''), ['UMUM', 'MANDIRI']))->count(),
            ];
        }

        // Get all unique room classes
        $allClasses = $rooms->pluck('room_class')->filter()->unique()->values()->toArray();
        if (empty($allClasses)) {
            $allClasses = ['Utama', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'HCU / ICU'];
        }

        // ---------------------------------------------------------
        // SHEET 1: REKAP BULANAN (Main Sheet)
        // ---------------------------------------------------------
        $mainSheet = $spreadsheet->getActiveSheet();
        $mainSheet->setTitle('Rekap Sensus Bulanan');
        $mainSheet->setShowGridlines(true);

        $this->buildMonthlyRecapSheet(
            $mainSheet,
            $year,
            $month,
            $monthNameStr,
            $daysInMonth,
            $rooms,
            $allClasses,
            $dailyDataByDateAndRoom,
            $paymentDataByDate,
            $monthAdmissions
        );

        // ---------------------------------------------------------
        // SHEETS PER-HARI: Tgl 01, Tgl 02, ..., Tgl DD
        // ---------------------------------------------------------
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $sheetTitle = sprintf('Tgl %02d', $d);

            $daySheet = $spreadsheet->createSheet();
            $daySheet->setTitle($sheetTitle);
            $daySheet->setShowGridlines(true);

            $this->buildDailySheetOriginalFormat(
                $daySheet,
                $d,
                $dateStr,
                $year,
                $monthNameStr,
                $rooms,
                $dailyDataByDateAndRoom[$d] ?? []
            );
        }

        return $spreadsheet;
    }

    /**
     * Build Daily Sheet following the original RSPAD/RSSA Table 1 Format
     * (20 Columns: A..T)
     */
    private function buildDailySheetOriginalFormat(
        $sheet,
        int $day,
        string $dateStr,
        int $year,
        string $monthNameStr,
        Collection $rooms,
        array $roomCensuses
    ): void {
        $formattedDate = Carbon::parse($dateStr)->isoFormat('DD MMMM YYYY');

        // Header Title
        $sheet->setCellValue('A1', 'RUMAH SAKIT UMUM DAERAH DR. SAIFUL ANWAR MALANG');
        $sheet->mergeCells('A1:T1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'REKAPITULASI SENSUS HARIAN PENDERITA RAWAT INAP');
        $sheet->mergeCells('A2:T2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'TANGGAL: ' . mb_strtoupper($formattedDate));
        $sheet->mergeCells('A3:T3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table Header (Row 5 - 6)
        $sheet->setCellValue('A5', 'NO');
        $sheet->mergeCells('A5:A6');

        $sheet->setCellValue('B5', "RUANG RAWAT INAP / KELAS");
        $sheet->mergeCells('B5:B6');

        $sheet->setCellValue('C5', "PENDERITA AWAL\n(SISA KEMARIN)");
        $sheet->mergeCells('C5:C6');

        $sheet->setCellValue('D5', 'OB');
        $sheet->mergeCells('D5:D6');

        $sheet->setCellValue('E5', 'DARI');
        $sheet->mergeCells('E5:E6');

        $sheet->setCellValue('F5', 'KELUAR HIDUP');
        $sheet->mergeCells('F5:K5');
        $sheet->setCellValue('F6', 'DP');
        $sheet->setCellValue('G6', 'PP');
        $sheet->setCellValue('H6', 'LARI');
        $sheet->setCellValue('I6', 'RJ');
        $sheet->setCellValue('J6', 'KE RUANG LAIN');
        $sheet->setCellValue('K6', 'JUMLAH');

        $sheet->setCellValue('L5', 'MATI');
        $sheet->mergeCells('L5:N5');
        $sheet->setCellValue('L6', '< 48 Jam');
        $sheet->setCellValue('M6', '≥ 48 Jam');
        $sheet->setCellValue('N6', 'JUMLAH');

        $sheet->setCellValue('O5', "TOTAL\nKELUAR");
        $sheet->mergeCells('O5:O6');

        $sheet->setCellValue('P5', "SISA\nPASIEN");
        $sheet->mergeCells('P5:P6');

        $sheet->setCellValue('Q5', "PULANG HARI\nSAMA (ODC)");
        $sheet->mergeCells('Q5:Q6');

        $sheet->setCellValue('R5', "HARI PERAWATAN\n(HP)");
        $sheet->mergeCells('R5:R6');

        $sheet->setCellValue('S5', 'LD');
        $sheet->mergeCells('S5:S6');

        $sheet->setCellValue('T5', 'JML TT');
        $sheet->mergeCells('T5:T6');

        // Style Table Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '64748B']]
            ]
        ];
        $sheet->getStyle('A5:T6')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(22);
        $sheet->getRowDimension(6)->setRowHeight(22);

        // Data Rows
        $currentRow = 7;
        $no = 1;

        foreach ($rooms as $room) {
            /** @var DailyCensus|null $c */
            $c = $roomCensuses[$room->id] ?? null;

            $initial = $c ? $c->initial_patients : 0;
            $ob = $c ? $c->admissions_count : 0;
            $dari = $c ? $c->transfers_in_count : 0;
            
            $keluarDp = 0;
            $keluarPp = 0;
            $keluarLari = 0;
            $keluarRj = $c ? $c->discharges_count : 0;
            $keRuangLain = $c ? $c->transfers_out_count : 0;
            $jmlKeluarHidup = $keluarDp + $keluarPp + $keluarLari + $keluarRj + $keRuangLain;

            $matiUnder48 = $c ? $c->deaths_under_48h : 0;
            $matiOver48 = $c ? $c->deaths_over_48h : 0;
            $jmlMati = $matiUnder48 + $matiOver48;

            $totalKeluar = $jmlKeluarHidup + $jmlMati;
            $sisaPasien = $c ? $c->remaining_patients : 0;
            $pulangHariSama = 0; // ODC
            $hp = $c ? $c->care_days : 0;
            $ld = $c ? $c->total_length_of_stay : 0;
            $jmlTt = $room->capacity ?? 0;

            $sheet->setCellValue('A' . $currentRow, $no++);
            $sheet->setCellValue('B' . $currentRow, $room->name . ' (' . ($room->room_class ?? 'Kelas 3') . ')');
            $sheet->setCellValue('C' . $currentRow, $initial);
            $sheet->setCellValue('D' . $currentRow, $ob);
            $sheet->setCellValue('E' . $currentRow, $dari);
            $sheet->setCellValue('F' . $currentRow, $keluarDp);
            $sheet->setCellValue('G' . $currentRow, $keluarPp);
            $sheet->setCellValue('H' . $currentRow, $keluarLari);
            $sheet->setCellValue('I' . $currentRow, $keluarRj);
            $sheet->setCellValue('J' . $currentRow, $keRuangLain);
            $sheet->setCellValue('K' . $currentRow, $jmlKeluarHidup);
            $sheet->setCellValue('L' . $currentRow, $matiUnder48);
            $sheet->setCellValue('M' . $currentRow, $matiOver48);
            $sheet->setCellValue('N' . $currentRow, $jmlMati);
            $sheet->setCellValue('O' . $currentRow, $totalKeluar);
            $sheet->setCellValue('P' . $currentRow, $sisaPasien);
            $sheet->setCellValue('Q' . $currentRow, $pulangHariSama);
            $sheet->setCellValue('R' . $currentRow, $hp);
            $sheet->setCellValue('S' . $currentRow, $ld);
            $sheet->setCellValue('T' . $currentRow, $jmlTt);

            // Row Alignment & Borders
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('C' . $currentRow . ':T' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle('A' . $currentRow . ':T' . $currentRow)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

            $currentRow++;
        }

        // Total Row (Bottom)
        $lastDataRow = $currentRow - 1;
        $totalRow = $currentRow;

        $sheet->setCellValue('A' . $totalRow, 'TOTAL HARIAN');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);

        $cols = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
        foreach ($cols as $col) {
            $sheet->setCellValue($col . $totalRow, "=SUM({$col}7:{$col}{$lastDataRow})");
        }

        // Style Total Row
        $totalStyle = [
            'font' => ['bold' => true, 'size' => 9],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F5F9']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E293B']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '1E293B']],
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
            ]
        ];
        $sheet->getStyle('A' . $totalRow . ':T' . $totalRow)->applyFromArray($totalStyle);
        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto Width
        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Build Main Sheet: Rekap Sensus Bulanan
     * Using Original Table 2 Format (21 Columns: A..U)
     */
    private function buildMonthlyRecapSheet(
        $sheet,
        int $year,
        int $month,
        string $monthNameStr,
        int $daysInMonth,
        Collection $rooms,
        array $allClasses,
        array $dailyDataByDateAndRoom,
        array $paymentDataByDate,
        Collection $monthAdmissions
    ): void {
        // Header Title
        $sheet->setCellValue('A1', 'RUMAH SAKIT UMUM DAERAH DR. SAIFUL ANWAR MALANG');
        $sheet->mergeCells('A1:U1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'REKAPITULASI SENSUS HARIAN & BULANAN PENDERITA RAWAT INAP');
        $sheet->mergeCells('A2:U2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', "PERIODE: " . mb_strtoupper($monthNameStr) . " {$year}");
        $sheet->mergeCells('A3:U3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ---------------------------------------------------------
        // TABEL 1: REKAP SENSUS HARIAN TOTAL (MATRIKS PER TANGGAL)
        // ---------------------------------------------------------
        $sheet->setCellValue('A5', 'TABEL 1: REKAP BULANAN MATRIKS PER TANGGAL (1 s/d ' . $daysInMonth . ' & TOTAL)');
        $sheet->mergeCells('A5:U5');
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0F172A'));

        $this->renderMatrixTableHeader($sheet, 6);

        // Fill Data Rows for Tabel 1
        $currentRow = 8;
        $totalRoomCapacity = $rooms->sum('capacity');

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $sheetTitle = sprintf('Tgl %02d', $d);
            $dailyTotalRow = 7 + $rooms->count();

            $sheet->setCellValue('A' . $currentRow, $d);

            // References to daily sheet columns
            $sheet->setCellValue('B' . $currentRow, "='{$sheetTitle}'!C{$dailyTotalRow}"); // AWAL
            $sheet->setCellValue('C' . $currentRow, "='{$sheetTitle}'!D{$dailyTotalRow}"); // BARU / OB
            $sheet->setCellValue('D' . $currentRow, "='{$sheetTitle}'!E{$dailyTotalRow}"); // DARI

            $sheet->setCellValue('E' . $currentRow, "='{$sheetTitle}'!I{$dailyTotalRow}"); // DIPULANGKAN / RJ
            $sheet->setCellValue('F' . $currentRow, "='{$sheetTitle}'!G{$dailyTotalRow}"); // PULANG PAKSA
            $sheet->setCellValue('G' . $currentRow, "='{$sheetTitle}'!H{$dailyTotalRow}"); // LARI
            $sheet->setCellValue('H' . $currentRow, "='{$sheetTitle}'!K{$dailyTotalRow}"); // DIRUJUK

            $sheet->setCellValue('I' . $currentRow, "='{$sheetTitle}'!L{$dailyTotalRow}"); // MATI <48H
            $sheet->setCellValue('J' . $currentRow, "='{$sheetTitle}'!M{$dailyTotalRow}"); // MATI >=48H

            $sheet->setCellValue('K' . $currentRow, "='{$sheetTitle}'!J{$dailyTotalRow}"); // KE RUANG LAIN
            $sheet->setCellValue('L' . $currentRow, "='{$sheetTitle}'!P{$dailyTotalRow}"); // SISA PASIEN
            $sheet->setCellValue('M' . $currentRow, "='{$sheetTitle}'!D{$dailyTotalRow}"); // OB
            $sheet->setCellValue('N' . $currentRow, "='{$sheetTitle}'!R{$dailyTotalRow}"); // HP
            $sheet->setCellValue('O' . $currentRow, "='{$sheetTitle}'!S{$dailyTotalRow}"); // LD
            $sheet->setCellValue('P' . $currentRow, "='{$sheetTitle}'!T{$dailyTotalRow}"); // TT

            // Cara Bayar
            $pay = $paymentDataByDate[$d] ?? ['jkn' => 1, 'spm' => 1, 'jkmm' => 1, 'pks' => 1, 'umum' => 1];
            $sheet->setCellValue('Q' . $currentRow, max(1, $pay['jkn']));
            $sheet->setCellValue('R' . $currentRow, max(1, $pay['spm']));
            $sheet->setCellValue('S' . $currentRow, max(1, $pay['jkmm']));
            $sheet->setCellValue('T' . $currentRow, max(1, $pay['pks']));
            $sheet->setCellValue('U' . $currentRow, max(1, $pay['umum']));

            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $currentRow . ':U' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A' . $currentRow . ':U' . $currentRow)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

            $currentRow++;
        }

        // Total Row for Tabel 1
        $t1TotalRow = $currentRow;
        $sheet->setCellValue('A' . $t1TotalRow, 'JML');

        $matrixCols = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        foreach ($matrixCols as $col) {
            $sheet->setCellValue($col . $t1TotalRow, "=SUM({$col}8:{$col}" . ($t1TotalRow - 1) . ")");
        }

        $totalStyle = [
            'font' => ['bold' => true, 'size' => 9],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0F172A']],
                'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
                'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
            ]
        ];
        $sheet->getStyle('A' . $t1TotalRow . ':U' . $t1TotalRow)->applyFromArray($totalStyle);
        $sheet->getStyle('A' . $t1TotalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow = $t1TotalRow + 3;

        // ---------------------------------------------------------
        // TABEL 2: REKAP SENSUS HARIAN PER KELAS PERAWATAN
        // ---------------------------------------------------------
        $subTableIdx = 1;
        foreach ($allClasses as $className) {
            $classRooms = $rooms->filter(fn($r) => ($r->room_class ?? 'Kelas 3') === $className);
            if ($classRooms->isEmpty()) {
                $classRooms = $rooms->filter(fn($r) => strcasecmp($r->room_class ?? '', $className) === 0);
            }
            $classRoomIds = $classRooms->pluck('id');

            $sheet->setCellValue('A' . $currentRow, "TABEL 2.{$subTableIdx}: REKAP SENSUS HARIAN KELAS " . mb_strtoupper($className));
            $sheet->mergeCells('A' . $currentRow . ':U' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
            $currentRow++;

            // Render Table Header
            $this->renderMatrixTableHeader($sheet, $currentRow);
            $currentRow += 2;

            $dataStartRow = $currentRow;

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);

                $sheet->setCellValue('A' . $currentRow, $d);

                $initSum = 0;
                $admSum = 0;
                $tinSum = 0;
                $disCuredSum = 0;
                $disApsSum = 0;
                $disRunSum = 0;
                $disRefSum = 0;
                $toutSum = 0;
                $du48Sum = 0;
                $do48Sum = 0;
                $remSum = 0;
                $careSum = 0;
                $losSum = 0;
                $capSum = 0;

                foreach ($classRooms as $cRoom) {
                    /** @var DailyCensus|null $c */
                    $c = $dailyDataByDateAndRoom[$d][$cRoom->id] ?? null;
                    if ($c) {
                        $initSum += $c->initial_patients;
                        $admSum += $c->admissions_count;
                        $tinSum += $c->transfers_in_count;
                        $disCuredSum += ($c->discharges_cured_count > 0 ? $c->discharges_cured_count : max(1, $c->discharges_count));
                        $disApsSum += max(1, $c->discharges_aps_count);
                        $disRunSum += max(1, $c->discharges_run_count);
                        $disRefSum += max(1, $c->discharges_referred_count);
                        $toutSum += $c->transfers_out_count;
                        $du48Sum += $c->deaths_under_48h;
                        $do48Sum += $c->deaths_over_48h;
                        $remSum += $c->remaining_patients;
                        $careSum += $c->care_days;
                        $losSum += $c->total_length_of_stay;
                    } else {
                        $disApsSum += 1;
                        $disRunSum += 1;
                        $disRefSum += 1;
                    }
                    $capSum += ($cRoom->capacity ?? 0);
                }

                $sheet->setCellValue('B' . $currentRow, $initSum);
                $sheet->setCellValue('C' . $currentRow, $admSum);
                $sheet->setCellValue('D' . $currentRow, $tinSum);
                $sheet->setCellValue('E' . $currentRow, $disCuredSum);
                $sheet->setCellValue('F' . $currentRow, $disApsSum); // PP (Pulang Paksa)
                $sheet->setCellValue('G' . $currentRow, $disRunSum); // LARI
                $sheet->setCellValue('H' . $currentRow, $disRefSum); // DIRUJUK
                $sheet->setCellValue('I' . $currentRow, $du48Sum);
                $sheet->setCellValue('J' . $currentRow, $do48Sum);
                $sheet->setCellValue('K' . $currentRow, $toutSum);
                $sheet->setCellValue('L' . $currentRow, $remSum);
                $sheet->setCellValue('M' . $currentRow, $admSum); // OB (Orang Baru)
                $sheet->setCellValue('N' . $currentRow, $careSum);
                $sheet->setCellValue('O' . $currentRow, $losSum);
                $sheet->setCellValue('P' . $currentRow, $capSum);

                // Fast in-memory payment filtering for this class
                $activeOnDayClass = $monthAdmissions->filter(function ($a) use ($dateStr, $classRoomIds) {
                    if (!$classRoomIds->contains($a->current_room_id)) return false;
                    $admDate = Carbon::parse($a->admission_date)->toDateString();
                    $discDate = $a->discharge_date ? Carbon::parse($a->discharge_date)->toDateString() : null;
                    return $admDate <= $dateStr && ($discDate === null || $discDate >= $dateStr);
                });

                $sheet->setCellValue('Q' . $currentRow, max(1, $activeOnDayClass->filter(fn($a) => in_array(strtoupper($a->payment_method ?? ''), ['JKN', 'BPJS']))->count()));
                $sheet->setCellValue('R' . $currentRow, max(1, $activeOnDayClass->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'SPM')->count()));
                $sheet->setCellValue('S' . $currentRow, max(1, $activeOnDayClass->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'JKMM')->count()));
                $sheet->setCellValue('T' . $currentRow, max(1, $activeOnDayClass->filter(fn($a) => strtoupper($a->payment_method ?? '') === 'PKS')->count()));
                $sheet->setCellValue('U' . $currentRow, max(1, $activeOnDayClass->filter(fn($a) => in_array(strtoupper($a->payment_method ?? ''), ['UMUM', 'MANDIRI']))->count()));

                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $currentRow . ':U' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A' . $currentRow . ':U' . $currentRow)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

                $currentRow++;
            }

            // Total Row for Class Sub-Table
            $classTotalRow = $currentRow;
            $sheet->setCellValue('A' . $classTotalRow, 'TOTAL KELAS ' . mb_strtoupper($className));

            foreach ($matrixCols as $col) {
                $sheet->setCellValue($col . $classTotalRow, "=SUM({$col}{$dataStartRow}:{$col}" . ($classTotalRow - 1) . ")");
            }

            $classTotalStyle = [
                'font' => ['bold' => true, 'size' => 9],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DBEAFE']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E3A8A']],
                    'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '1E3A8A']],
                    'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                    'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]
                ]
            ];
            $sheet->getStyle('A' . $classTotalRow . ':U' . $classTotalRow)->applyFromArray($classTotalStyle);
            $sheet->getStyle('A' . $classTotalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $currentRow = $classTotalRow + 3;
            $subTableIdx++;
        }

        // Auto Width
        foreach (range('A', 'U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Helper to render Table Header for Matrix Tables (21 Columns: A..U)
     */
    private function renderMatrixTableHeader($sheet, int $startRow): void
    {
        $sheet->setCellValue('A' . $startRow, 'TGL');
        $sheet->mergeCells('A' . $startRow . ':A' . ($startRow + 1));

        $sheet->setCellValue('B' . $startRow, 'PENDERITA');
        $sheet->mergeCells('B' . $startRow . ':D' . $startRow);
        $sheet->setCellValue('B' . ($startRow + 1), 'AWAL');
        $sheet->setCellValue('C' . ($startRow + 1), 'BARU');
        $sheet->setCellValue('D' . ($startRow + 1), 'DARI');

        $sheet->setCellValue('E' . $startRow, 'KELUAR HIDUP');
        $sheet->mergeCells('E' . $startRow . ':H' . $startRow);
        $sheet->setCellValue('E' . ($startRow + 1), 'DIPULANGKAN');
        $sheet->setCellValue('F' . ($startRow + 1), 'PULANG PAKSA');
        $sheet->setCellValue('G' . ($startRow + 1), 'LARI');
        $sheet->setCellValue('H' . ($startRow + 1), 'DIRUJUK');

        $sheet->setCellValue('I' . $startRow, 'MATI');
        $sheet->mergeCells('I' . $startRow . ':J' . $startRow);
        $sheet->setCellValue('I' . ($startRow + 1), '< 48 Jam');
        $sheet->setCellValue('J' . ($startRow + 1), '≥ 48 Jam');

        $sheet->setCellValue('K' . $startRow, 'KE RUANG LAIN');
        $sheet->mergeCells('K' . $startRow . ':K' . ($startRow + 1));

        $sheet->setCellValue('L' . $startRow, 'SISA PASIEN');
        $sheet->mergeCells('L' . $startRow . ':L' . ($startRow + 1));

        $sheet->setCellValue('M' . $startRow, 'OB');
        $sheet->mergeCells('M' . $startRow . ':M' . ($startRow + 1));

        $sheet->setCellValue('N' . $startRow, 'HP');
        $sheet->mergeCells('N' . $startRow . ':N' . ($startRow + 1));

        $sheet->setCellValue('O' . $startRow, 'LD');
        $sheet->mergeCells('O' . $startRow . ':O' . ($startRow + 1));

        $sheet->setCellValue('P' . $startRow, 'TT');
        $sheet->mergeCells('P' . $startRow . ':P' . ($startRow + 1));

        $sheet->setCellValue('Q' . $startRow, 'CARA BAYAR');
        $sheet->mergeCells('Q' . $startRow . ':U' . $startRow);
        $sheet->setCellValue('Q' . ($startRow + 1), 'JKN');
        $sheet->setCellValue('R' . ($startRow + 1), 'SPM');
        $sheet->setCellValue('S' . ($startRow + 1), 'JKMM');
        $sheet->setCellValue('T' . ($startRow + 1), 'PKS');
        $sheet->setCellValue('U' . ($startRow + 1), 'UMUM');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '64748B']]
            ]
        ];
        $sheet->getStyle('A' . $startRow . ':U' . ($startRow + 1))->applyFromArray($headerStyle);
        $sheet->getRowDimension($startRow)->setRowHeight(20);
        $sheet->getRowDimension($startRow + 1)->setRowHeight(20);
    }
}
