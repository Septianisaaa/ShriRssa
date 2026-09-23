<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'; ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="Times New Roman" ss:Size="12"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="sHeaderMain">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="15" ss:Bold="1"/>
  </Style>
  <Style ss:ID="sHeaderSub">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#1E40AF"/>
  </Style>
  <Style ss:ID="sHeaderAddress">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="10" ss:Color="#334155"/>
  </Style>
  <Style ss:ID="sHeaderTitleDoc">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="16" ss:Bold="1"/>
  </Style>
  <Style ss:ID="sHeaderSubtitleDoc">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="13" ss:Bold="1" ss:Color="#475569"/>
  </Style>
  <Style ss:ID="sHeaderBoxPeriod">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="14" ss:Bold="1" ss:Color="#1E40AF"/>
   <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerInfo">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#0F172A"/>
   <Interior ss:Color="#F8FAFC" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sTableHeader">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1"/>
   <Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerPasienMasuk">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#2563EB" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerPasienKeluar">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#DC2626" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sCell">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="sCellCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="sCellBoldCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="sStatusActive">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#854D0E"/>
   <Interior ss:Color="#FEF9C3" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sStatusCured">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#166534"/>
   <Interior ss:Color="#DCFCE7" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sStatusDeceased">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#991B1B"/>
   <Interior ss:Color="#FEE2E2" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sFooterTotal">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1"/>
   <Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
 </Styles>

<?php foreach ($classGroups as $sheetKey => $groupData): ?>
    <?php
        $currActive = $groupData['active'];
        $currDischarged = $groupData['discharged'];
        $currLabel = $groupData['class_label'];
        $safeSheetName = htmlspecialchars(substr(str_replace([':', '?', '*', '/', '\\', '[', ']'], '', $sheetKey), 0, 31));
    ?>
 <Worksheet ss:Name="<?= $safeSheetName ?>">
  <Table ss:ExpandedColumnCount="19" ss:FullColumns="1" ss:FullRows="1" ss:DefaultRowHeight="20">
   <Column ss:Width="40"/>
   <Column ss:Width="140"/>
   <Column ss:Width="180"/>
   <Column ss:Width="110"/>
   <Column ss:Width="110"/>
   <Column ss:Width="100"/>
   <Column ss:Width="140"/>
   <Column ss:Width="60"/>
   <Column ss:Width="140"/>
   <Column ss:Width="130"/>
   <Column ss:Width="130"/>
   <Column ss:Width="140"/>
   <Column ss:Width="80"/>
   <Column ss:Width="140"/>
   <Column ss:Width="90"/>
   <Column ss:Width="140"/>
   <Column ss:Width="130"/>
   <Column ss:Width="180"/>
   <Column ss:Width="120"/>

   <!-- Row 1: Header Main -->
   <Row ss:Height="25">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderMain"><Data ss:Type="String">RUMAH SAKIT UMUM DAERAH DR. SAIFUL ANWAR MALANG</Data></Cell>
   </Row>
   <!-- Row 2: Header Sub -->
   <Row ss:Height="20">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderSub"><Data ss:Type="String">TERAKREDITASI STARKES EDISI 1 INTERNASIONAL</Data></Cell>
   </Row>
   <!-- Row 3: Address -->
   <Row ss:Height="18">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderAddress"><Data ss:Type="String">Jl. Jaksa Agung Suprapto No. 2 Malang 65111 Tlp. (0341) 362101 Fax. (0341) 369384 | Email: staf-rsu-srsulfulanwar@jatimprov.go.id | Website: www.rsusaifulanwar.jatimprov.go.id</Data></Cell>
   </Row>
   <!-- Row 4: Empty space -->
   <Row ss:Height="10"/>
   <!-- Row 5: Title Doc -->
   <Row ss:Height="26">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderTitleDoc"><Data ss:Type="String">CATATAN SENSUS HARIAN PENDERITA RAWAT INAP</Data></Cell>
   </Row>
   <!-- Row 6: Subtitle Doc -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderSubtitleDoc"><Data ss:Type="String">INSTALASI REKAM MEDIS - RSUD DR. SAIFUL ANWAR</Data></Cell>
   </Row>
   <!-- Row 7: Period -->
   <Row ss:Height="24">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderBoxPeriod"><Data ss:Type="String">PERIODE: <?= strtoupper(htmlspecialchars($monthName)) ?> <?= htmlspecialchars($year) ?></Data></Cell>
   </Row>
   <!-- Row 8: Empty space -->
   <Row ss:Height="10"/>
   <!-- Row 9: Banner Info -->
   <Row ss:Height="24">
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerInfo"><Data ss:Type="String">RUANGAN: <?= strtoupper(htmlspecialchars($room->name)) ?> | KATEGORI: <?= strtoupper(htmlspecialchars($room->category)) ?> | KELAS: <?= strtoupper(htmlspecialchars($currLabel)) ?> | KAPASITAS TT: <?= htmlspecialchars($room->capacity) ?> Beds</Data></Cell>
   </Row>
   <!-- Row 10: Empty space -->
   <Row ss:Height="10"/>

   <!-- Table Header Rows (11, 12, 13) -->
   <Row ss:Height="22">
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">NO</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">NOMOR REKAM MEDIS</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">NAMA PASIEN</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">JENIS KELAMIN</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">STATUS MASUK</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">KELAS RAWAT</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">TANGGAL &amp; JAM MRS</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sTableHeader"><Data ss:Type="String">PASIEN MASUK</Data></Cell>
    <Cell ss:MergeAcross="6" ss:StyleID="sTableHeader"><Data ss:Type="String">PASIEN KELUAR / KRS / MUTASI</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">DIAGNOSIS</Data></Cell>
    <Cell ss:MergeDown="2" ss:StyleID="sTableHeader"><Data ss:Type="String">TOTAL LAMA DIRAWAT (LD)</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:Index="8" ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">BARU</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">PINDAHAN</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">STATUS KELUAR</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">HIDUP</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">MATI</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">PINDAH KE RUANG LAIN</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:Index="9" ss:StyleID="sTableHeader"><Data ss:Type="String">TGL PINDAH</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">RUANG ASAL</Data></Cell>
    <Cell ss:Index="12" ss:StyleID="sTableHeader"><Data ss:Type="String">TGL KRS</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">LD HARI</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">TGL &amp; WAKTU MATI</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&lt; 48 / &ge; 48</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">TGL KELUAR</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">KE RUANG</Data></Cell>
   </Row>

   <!-- SECTION 1: PASIEN MASUK / AKTIF DIRAWAT -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerPasienMasuk"><Data ss:Type="String">PASIEN MASUK / AKTIF DIRAWAT DI RUANGAN <?= strtoupper(htmlspecialchars($room->name)) ?> - <?= strtoupper(htmlspecialchars($currLabel)) ?> (TOTAL: <?= count($currActive) ?> PASIEN)</Data></Cell>
   </Row>

   <?php $noActive = 1; ?>
   <?php if (count($currActive) > 0): ?>
       <?php foreach ($currActive as $adm): ?>
        <Row ss:Height="20">
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $noActive++ ?></Data></Cell>
         <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= htmlspecialchars($adm->patient->rm_number) ?></Data></Cell>
         <Cell ss:StyleID="sCell"><Data ss:Type="String"><?= htmlspecialchars($adm->patient->name) ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id == $room->id ? 'Baru' : 'Pindahan' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= htmlspecialchars($adm->room_class ?? $room->room_class) ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->admission_date ? $adm->admission_date->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id == $room->id ? 'v' : '' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id != $room->id ? $adm->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id != $room->id ? htmlspecialchars($adm->initialRoom?->name ?? '-') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sStatusActive"><Data ss:Type="String">Aktif Dirawat</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String">-</Data></Cell>
         <Cell ss:StyleID="sCell"><Data ss:Type="String"><?= htmlspecialchars($adm->diagnosis ?? '-') ?></Data></Cell>
         <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $adm->calculateLengthOfStay() ?> Hari</Data></Cell>
        </Row>
       <?php endforeach; ?>
   <?php else: ?>
        <Row ss:Height="20">
         <Cell ss:MergeAcross="18" ss:StyleID="sCellCenter"><Data ss:Type="String">Tidak ada pasien aktif di kelas ini pada periode sensus.</Data></Cell>
        </Row>
   <?php endif; ?>

   <!-- SECTION 2: PASIEN KELUAR / KRS / MENINGGAL -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerPasienKeluar"><Data ss:Type="String">PASIEN KELUAR / KRS / MENINGGAL PERIODE <?= strtoupper(htmlspecialchars($monthName)) ?> <?= htmlspecialchars($year) ?> - <?= strtoupper(htmlspecialchars($currLabel)) ?> (TOTAL: <?= count($currDischarged) ?> PASIEN)</Data></Cell>
   </Row>

   <?php 
       $noDischarged = 1; 
       $totalLosDischarged = 0;
   ?>
   <?php if (count($currDischarged) > 0): ?>
       <?php foreach ($currDischarged as $adm): ?>
           <?php
               $isDeceased = in_array($adm->discharge_condition, ['deceased', 'deceased_under_48h', 'deceased_over_48h']);
               $isTransferredOut = $adm->status === 'transferred_out';
               $los = (int) ($adm->length_of_stay ?? $adm->calculateLengthOfStay());
               $totalLosDischarged += $los;

               $conditionLabel = match($adm->discharge_condition) {
                   'cured' => 'Membaik',
                   'improved' => 'Membaik',
                   'unimproved' => 'Belum Sembuh',
                   'referred' => 'Dirujuk',
                   'aps' => 'APS',
                   'deceased_under_48h' => 'Meninggal < 48 Jam',
                   'deceased_over_48h' => 'Meninggal >= 48 Jam',
                   'deceased' => 'Meninggal',
                   default => 'KRS',
               };
               $statusStyle = $isDeceased ? 'sStatusDeceased' : 'sStatusCured';
           ?>
        <Row ss:Height="20">
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $noDischarged++ ?></Data></Cell>
         <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= htmlspecialchars($adm->patient->rm_number) ?></Data></Cell>
         <Cell ss:StyleID="sCell"><Data ss:Type="String"><?= htmlspecialchars($adm->patient->name) ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id == $room->id ? 'Baru' : 'Pindahan' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= htmlspecialchars($adm->room_class ?? $room->room_class) ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->admission_date ? $adm->admission_date->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id == $room->id ? 'v' : '' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id != $room->id ? $adm->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $adm->initial_room_id != $room->id ? htmlspecialchars($adm->initialRoom?->name ?? '-') : '-' ?></Data></Cell>
         <Cell ss:StyleID="<?= $statusStyle ?>"><Data ss:Type="String"><?= htmlspecialchars($conditionLabel) ?></Data></Cell>
         
         <!-- HIDUP -->
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= !$isDeceased && !$isTransferredOut && $adm->discharge_date ? $adm->discharge_date->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= !$isDeceased && !$isTransferredOut ? $los . ' Hari' : '-' ?></Data></Cell>

         <!-- MATI -->
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $isDeceased && $adm->discharge_date ? $adm->discharge_date->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $isDeceased ? ($adm->discharge_condition === 'deceased_under_48h' ? '< 48 Jam' : '>= 48 Jam') : '-' ?></Data></Cell>

         <!-- PINDAH RUANG LAIN -->
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $isTransferredOut && $adm->discharge_date ? $adm->discharge_date->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' ?></Data></Cell>
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $isTransferredOut ? htmlspecialchars($adm->currentRoom?->name ?? '-') : '-' ?></Data></Cell>

         <Cell ss:StyleID="sCell"><Data ss:Type="String"><?= htmlspecialchars($adm->diagnosis ?? '-') ?></Data></Cell>
         <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $los ?> Hari</Data></Cell>
        </Row>
       <?php endforeach; ?>
   <?php else: ?>
        <Row ss:Height="20">
         <Cell ss:MergeAcross="18" ss:StyleID="sCellCenter"><Data ss:Type="String">Tidak ada pasien keluar/KRS/meninggal di kelas ini pada periode sensus.</Data></Cell>
        </Row>
   <?php endif; ?>

   <!-- FOOTER TOTAL REKAP -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="3" ss:StyleID="sFooterTotal"><Data ss:Type="String">TOTAL REKAPITULASI SENSUS (<?= strtoupper(htmlspecialchars($currLabel)) ?>)</Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="String">AKTIF: <?= count($currActive) ?></Data></Cell>
    <Cell ss:MergeAcross="5" ss:StyleID="sFooterTotal"><Data ss:Type="String">PASIEN KELUAR / KRS / MENINGGAL: <?= count($currDischarged) ?> PASIEN</Data></Cell>
    <Cell ss:MergeAcross="6" ss:StyleID="sFooterTotal"><Data ss:Type="String">TOTAL LAMA DIRAWAT (LD) KELUAR:</Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="String"><?= $totalLosDischarged ?> HARI</Data></Cell>
   </Row>

  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <DisplayGridlines/>
  </WorksheetOptions>
 </Worksheet>
<?php endforeach; ?>
</Workbook>
