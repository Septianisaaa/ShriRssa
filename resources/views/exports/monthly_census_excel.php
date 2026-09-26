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
   <Font ss:FontName="Times New Roman" ss:Size="11"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="15" ss:Bold="1"/>
  </Style>
  <Style ss:ID="sHeaderSubtitleDoc">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#475569"/>
  </Style>
  <Style ss:ID="sHeaderBoxPeriod">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="13" ss:Bold="1" ss:Color="#1E40AF"/>
   <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#2563EB"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerSection">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="12" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#1E3A8A" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerInfo">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#0F172A"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="10" ss:Bold="1"/>
   <Interior ss:Color="#E2E8F0" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
  <Style ss:ID="sBannerPasienMasuk">
   <Alignment ss:Vertical="Center"/>
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#854D0E"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#166534"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#991B1B"/>
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
   <Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1"/>
   <Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
  </Style>
 </Styles>

 <!-- ========================================================= -->
 <!-- WORKSHEET 1: REKAP BULANAN & REKAP HARIAN PER KELAS      -->
 <!-- ========================================================= -->
 <Worksheet ss:Name="Rekap Sensus Bulanan">
  <Table ss:ExpandedColumnCount="21" ss:FullColumns="1" ss:FullRows="1" ss:DefaultRowHeight="20">
   <Column ss:Width="35"/>
   <Column ss:Width="160"/>
   <Column ss:Width="80"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="60"/>
   <Column ss:Width="65"/>
   <Column ss:Width="55"/>
   <Column ss:Width="55"/>
   <Column ss:Width="65"/>
   <Column ss:Width="60"/>
   <Column ss:Width="60"/>
   <Column ss:Width="65"/>
   <Column ss:Width="50"/>
   <Column ss:Width="50"/>
   <Column ss:Width="55"/>
   <Column ss:Width="55"/>

   <!-- Kop Surat RSUD Saiful Anwar -->
   <Row ss:Height="24">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderMain"><Data ss:Type="String">RUMAH SAKIT UMUM DAERAH DR. SAIFUL ANWAR MALANG</Data></Cell>
   </Row>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderSub"><Data ss:Type="String">TERAKREDITASI STARKES EDISI 1 INTERNASIONAL</Data></Cell>
   </Row>
   <Row ss:Height="16">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderAddress"><Data ss:Type="String">Jl. Jaksa Agung Suprapto No. 2 Malang 65111 Tlp. (0341) 362101 Fax. (0341) 369384 | Email: staf-rsu-srsulfulanwar@jatimprov.go.id</Data></Cell>
   </Row>
   <Row ss:Height="8"/>

   <Row ss:Height="24">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderTitleDoc"><Data ss:Type="String">REKAPITULASI SENSUS HARIAN &amp; BULANAN PENDERITA RAWAT INAP</Data></Cell>
   </Row>
   <Row ss:Height="20">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderSubtitleDoc"><Data ss:Type="String">INSTALASI REKAM MEDIS &amp; SENSUS HARIAN RAWAT INAP (SHRI)</Data></Cell>
   </Row>
   <Row ss:Height="22">
    <Cell ss:MergeAcross="20" ss:StyleID="sHeaderBoxPeriod"><Data ss:Type="String">PERIODE: <?= htmlspecialchars(strtoupper($monthName)) ?> <?= htmlspecialchars($year) ?> | RUANGAN: <?= htmlspecialchars(strtoupper($room->name)) ?> (<?= htmlspecialchars(strtoupper($room->category)) ?>)</Data></Cell>
   </Row>
   <Row ss:Height="10"/>

   <!-- ========================================================= -->
   <!-- TABEL 1: REKAP HARIAN PER KELAS PERAWATAN                 -->
   <!-- ========================================================= -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="20" ss:StyleID="sBannerSection"><Data ss:Type="String">TABEL 1: REKAP HARIAN PER KELAS PERAWATAN (SENSUS HARIAN)</Data></Cell>
   </Row>

   <!-- Header Tabel 1 -->
   <Row ss:Height="22">
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">NO</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">RUANG RAWAT INAP / KELAS</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">PENDERITA AWAL (SISA KEMARIN)</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">OB</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">DARI</Data></Cell>
    <Cell ss:MergeAcross="5" ss:StyleID="sTableHeader"><Data ss:Type="String">KELUAR HIDUP</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sTableHeader"><Data ss:Type="String">MATI</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">TOTAL KELUAR</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">SISA PASIEN</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">PULANG HARI SAMA (ODC)</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">HARI PERAWATAN (HP)</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">LD</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">JML TT</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <!-- Subheaders Keluar Hidup -->
    <Cell ss:Index="6" ss:StyleID="sTableHeader"><Data ss:Type="String">DP</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">PP</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">LARI</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">RJ</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">KE RUANG LAIN</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">JUMLAH</Data></Cell>
    <!-- Subheaders Mati -->
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&lt; 48 Jam</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&gt;= 48 Jam</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">JUMLAH</Data></Cell>
   </Row>

   <!-- Data Rows Tabel 1 -->
   <?php
       $noT1 = 1;
       $totAwal = $totOb = $totDari = 0;
       $totDp = $totPp = $totLari = $totRj = $totKeRuangLain = $totJmlHidup = 0;
       $totMatiUnder = $totMatiOver = $totJmlMati = $totTotalKeluar = 0;
       $totSisa = $totPulangSama = $totHp = $totLd = $totTt = 0;
   ?>
   <?php foreach ($dailySummaryByClass as $clsName => $cData): ?>
       <?php
           $totAwal += $cData['penderita_awal'];
           $totOb += $cData['ob'];
           $totDari += $cData['dari'];
           $totDp += $cData['keluar_dp'];
           $totPp += $cData['keluar_pp'];
           $totLari += $cData['keluar_lari'];
           $totRj += $cData['keluar_rj'];
           $totKeRuangLain += $cData['ke_ruang_lain'];
           $totJmlHidup += $cData['jumlah_keluar_hidup'];
           $totMatiUnder += $cData['mati_under_48'];
           $totMatiOver += $cData['mati_over_48'];
           $totJmlMati += $cData['jumlah_mati'];
           $totTotalKeluar += $cData['total_keluar'];
           $totSisa += $cData['sisa_pasien'];
           $totPulangSama += $cData['pulang_hari_sama'];
           $totHp += $cData['hari_perawatan'];
           $totLd += $cData['ld'];
           $totTt += $cData['jml_tt'];
       ?>
       <Row ss:Height="20">
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $noT1++ ?></Data></Cell>
        <Cell ss:StyleID="sCell"><Data ss:Type="String"><?= htmlspecialchars($clsName) ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['penderita_awal'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['ob'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['dari'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['keluar_dp'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['keluar_pp'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['keluar_lari'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['keluar_rj'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['ke_ruang_lain'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $cData['jumlah_keluar_hidup'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['mati_under_48'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['mati_over_48'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $cData['jumlah_mati'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $cData['total_keluar'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $cData['sisa_pasien'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['pulang_hari_sama'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $cData['hari_perawatan'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['ld'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $cData['jml_tt'] ?></Data></Cell>
       </Row>
   <?php endforeach; ?>

   <!-- Total Row Tabel 1 -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="1" ss:StyleID="sFooterTotal"><Data ss:Type="String">TOTAL REKAP HARIAN PER KELAS</Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totAwal ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totOb ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totDari ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totDp ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totPp ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totLari ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totRj ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totKeRuangLain ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totJmlHidup ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totMatiUnder ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totMatiOver ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totJmlMati ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totTotalKeluar ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totSisa ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totPulangSama ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totHp ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totLd ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $totTt ?></Data></Cell>
   </Row>
   <Row ss:Height="12"/>

   <!-- ========================================================= -->
   <!-- TABEL 2: REKAP BULANAN MATRIKS PER TANGGAL (1..31 & TOTAL) -->
   <!-- ========================================================= -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="20" ss:StyleID="sBannerSection"><Data ss:Type="String">TABEL 2: REKAP BULANAN MATRIKS PER TANGGAL (1 s/d <?= $monthlyMatrixData['days_in_month'] ?> &amp; TOTAL)</Data></Cell>
   </Row>

   <!-- Header Tabel 2 -->
   <Row ss:Height="22">
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">TGL</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sTableHeader"><Data ss:Type="String">PENDERITA</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sTableHeader"><Data ss:Type="String">KELUAR HIDUP</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">MATI</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">KE RUANG LAIN</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">SISA PASIEN</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">OB</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">HP</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">LD</Data></Cell>
    <Cell ss:MergeDown="1" ss:StyleID="sTableHeader"><Data ss:Type="String">TT</Data></Cell>
    <Cell ss:MergeAcross="4" ss:StyleID="sTableHeader"><Data ss:Type="String">CARA BAYAR</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <!-- Subheaders Penderita -->
    <Cell ss:Index="2" ss:StyleID="sTableHeader"><Data ss:Type="String">AWAL</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">BARU</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">DARI</Data></Cell>
    <!-- Subheaders Keluar Hidup -->
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">DIPULANGKAN</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">PULANG PAKSA</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">LARI</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">DIRUJUK</Data></Cell>
    <!-- Subheaders Mati -->
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&lt; 48 Jam</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&gt;= 48 Jam</Data></Cell>
    <!-- Subheaders Cara Bayar -->
    <Cell ss:Index="17" ss:StyleID="sTableHeader"><Data ss:Type="String">JKN</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">SPM</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">JKMM</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">PKS</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">UMUM</Data></Cell>
   </Row>

   <!-- Data Rows Tabel 2 (Days 1..daysInMonth) -->
   <?php foreach ($monthlyMatrixData['matrix'] as $dayNum => $m): ?>
       <Row ss:Height="20">
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $dayNum ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['penderita_awal'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['penderita_baru'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['penderita_dari'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['keluar_dipulangkan'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['keluar_pulang_paksa'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['keluar_lari'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['keluar_dirujuk'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['mati_under_48'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['mati_over_48'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['ke_ruang_lain'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $m['sisa_pasien'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['ob'] ?></Data></Cell>
        <Cell ss:StyleID="sCellBoldCenter"><Data ss:Type="Number"><?= $m['hp'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['ld'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['tt'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['jkn'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['spm'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['jkmm'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['pks'] ?></Data></Cell>
        <Cell ss:StyleID="sCellCenter"><Data ss:Type="Number"><?= $m['umum'] ?></Data></Cell>
       </Row>
   <?php endforeach; ?>

   <!-- Total Row Tabel 2 -->
   <?php $mt = $monthlyMatrixData['totals']; ?>
   <Row ss:Height="22">
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="String">JML</Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['penderita_awal'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['penderita_baru'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['penderita_dari'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['keluar_dipulangkan'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['keluar_pulang_paksa'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['keluar_lari'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['keluar_dirujuk'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['mati_under_48'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['mati_over_48'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['ke_ruang_lain'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['sisa_pasien'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['ob'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['hp'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['ld'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['tt'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['jkn'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['spm'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['jkmm'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['pks'] ?></Data></Cell>
    <Cell ss:StyleID="sFooterTotal"><Data ss:Type="Number"><?= $mt['umum'] ?></Data></Cell>
   </Row>
   <Row ss:Height="12"/>

   <!-- ========================================================= -->
   <!-- TABEL 3: INDIKATOR PELAYANAN RAWAT INAP                  -->
   <!-- ========================================================= -->
   <?php
       $days = $indicators['days_in_month'] ?? 30;
       $capacity = max(1, $indicators['capacity'] ?? 1);
       $totalHp = $indicators['total_care_days'] ?? 0;
       $totalLos = $indicators['total_length_of_stay'] ?? 0;
       $totalOut = max(1, $indicators['total_out'] ?? 1);
       $totalDeathsUnder = $indicators['total_deaths_under_48h'] ?? 0;
       $totalDeathsOver = $indicators['total_deaths_over_48h'] ?? 0;
       $totalDeaths = $indicators['total_deaths'] ?? 0;

       $bor = round(($totalHp / ($capacity * $days)) * 100, 2);
       $alos = round($totalLos / $totalOut, 2);
       $toi = round((($capacity * $days) - $totalHp) / $totalOut, 2);
       $bto = round($totalOut / $capacity, 2);
       $ndr = round(($totalDeathsOver / $totalOut) * 1000, 2);
       $gdr = round(($totalDeaths / $totalOut) * 1000, 2);
   ?>
   <Row ss:Height="22">
    <Cell ss:MergeAcross="20" ss:StyleID="sBannerSection"><Data ss:Type="String">TABEL 3: INDIKATOR EFISIENSI PELAYANAN RAWAT INAP (KEMENKES RI)</Data></Cell>
   </Row>
   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sTableHeader"><Data ss:Type="String">INDIKATOR</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sTableHeader"><Data ss:Type="String">RUMUS PENGHITUNGAN</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sTableHeader"><Data ss:Type="String">HASIL HARI INI / PERIODE</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sTableHeader"><Data ss:Type="String">STANDAR IDEAL KEMENKES</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sTableHeader"><Data ss:Type="String">EVALUASI EFISIENSI</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">BOR (Bed Occupancy Rate)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">(Total Hari Perawatan / (Kapasitas TT x Jumlah Hari)) x 100%</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $bor ?>%</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">60% - 85%</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String"><?= $bor >= 60 && $bor <= 85 ? 'Ideal &amp; Efisien' : ($bor < 60 ? 'Tingkat Okupansi Rendah' : 'Tingkat Okupansi Sangat Tinggi') ?></Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">ALOS (Average Length of Stay)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">Total Lama Dirawat Pasien Keluar / Total Pasien Keluar</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $alos ?> Hari</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">6 - 9 Hari</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String"><?= $alos >= 6 && $alos <= 9 ? 'Sesuai Standar Mutu' : 'Perlu Evaluasi Lama Dirawat' ?></Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">TOI (Turn Over Interval)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">((Kapasitas TT x Jumlah Hari) - Total HP) / Total Pasien Keluar</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $toi ?> Hari</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">1 - 3 Hari</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String"><?= $toi >= 1 && $toi <= 3 ? 'Efisiensi Penggunaan TT Bagus' : 'Interval TT Kosong Terlalu Lama' ?></Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">BTO (Bed Turn Over)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">Total Pasien Keluar / Kapasitas Tempat Tidur</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $bto ?> Kali</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">40 - 50 Kali / Tahun</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String">Frekuensi Pemakaian TT Efektif</Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">NDR (Net Death Rate)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">(Meninggal &gt;= 48 Jam / Total Pasien Keluar) x 1000</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $ndr ?> Permil</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">&lt; 25 Permil</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String"><?= $ndr < 25 ? 'Sangat Bagus (&lt; 25 Permil)' : 'Tinggi (&gt;= 25 Permil)' ?></Data></Cell>
   </Row>

   <Row ss:Height="20">
    <Cell ss:MergeAcross="1" ss:StyleID="sCellBoldCenter"><Data ss:Type="String">GDR (Gross Death Rate)</Data></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="sCell"><Data ss:Type="String">(Total Meninggal / Total Pasien Keluar) x 1000</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellBoldCenter"><Data ss:Type="String"><?= $gdr ?> Permil</Data></Cell>
    <Cell ss:MergeAcross="2" ss:StyleID="sCellCenter"><Data ss:Type="String">&lt; 45 Permil</Data></Cell>
    <Cell ss:MergeAcross="10" ss:StyleID="sCell"><Data ss:Type="String"><?= $gdr < 45 ? 'Sangat Bagus (&lt; 45 Permil)' : 'Tinggi (&gt;= 45 Permil)' ?></Data></Cell>
   </Row>

  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <DisplayGridlines/>
  </WorksheetOptions>
 </Worksheet>

 <!-- ========================================================= -->
 <!-- WORKSHEETS PER KELAS PERAWATAN (PASIEN AKTIF & DISCHARGED) -->
 <!-- ========================================================= -->
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
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderAddress"><Data ss:Type="String">Jl. Jaksa Agung Suprapto No. 2 Malang 65111 Tlp. (0341) 362101 Fax. (0341) 369384 | Email: staf-rsu-srsulfulanwar@jatimprov.go.id</Data></Cell>
   </Row>
   <!-- Row 4: Empty space -->
   <Row ss:Height="10"/>
   <!-- Row 5: Title Doc -->
   <Row ss:Height="26">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderTitleDoc"><Data ss:Type="String">RINCIAN PASIEN RAWAT INAP - KELAS <?= htmlspecialchars(strtoupper($currLabel)) ?></Data></Cell>
   </Row>
   <!-- Row 6: Subtitle Doc -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderSubtitleDoc"><Data ss:Type="String">INSTALASI REKAM MEDIS - RSUD DR. SAIFUL ANWAR</Data></Cell>
   </Row>
   <!-- Row 7: Period -->
   <Row ss:Height="24">
    <Cell ss:MergeAcross="18" ss:StyleID="sHeaderBoxPeriod"><Data ss:Type="String">PERIODE: <?= htmlspecialchars(strtoupper($monthName)) ?> <?= htmlspecialchars($year) ?></Data></Cell>
   </Row>
   <!-- Row 8: Empty space -->
   <Row ss:Height="10"/>
   <!-- Row 9: Banner Info -->
   <Row ss:Height="24">
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerInfo"><Data ss:Type="String">RUANGAN: <?= htmlspecialchars(strtoupper($room->name)) ?> | KATEGORI: <?= htmlspecialchars(strtoupper($room->category)) ?> | KELAS: <?= htmlspecialchars(strtoupper($currLabel)) ?> | KAPASITAS TT: <?= htmlspecialchars($room->capacity) ?> Beds</Data></Cell>
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
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">&lt; 48 / &gt;= 48</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">TGL KELUAR</Data></Cell>
    <Cell ss:StyleID="sTableHeader"><Data ss:Type="String">KE RUANG</Data></Cell>
   </Row>

   <!-- SECTION 1: PASIEN MASUK / AKTIF DIRAWAT -->
   <Row ss:Height="22">
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerPasienMasuk"><Data ss:Type="String">PASIEN MASUK / AKTIF DIRAWAT DI RUANGAN <?= htmlspecialchars(strtoupper($room->name)) ?> - <?= htmlspecialchars(strtoupper($currLabel)) ?> (TOTAL: <?= count($currActive) ?> PASIEN)</Data></Cell>
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
    <Cell ss:MergeAcross="18" ss:StyleID="sBannerPasienKeluar"><Data ss:Type="String">PASIEN KELUAR / KRS / MENINGGAL PERIODE <?= htmlspecialchars(strtoupper($monthName)) ?> <?= htmlspecialchars($year) ?> - <?= htmlspecialchars(strtoupper($currLabel)) ?> (TOTAL: <?= count($currDischarged) ?> PASIEN)</Data></Cell>
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
                   'cured' => 'Dipulangkan',
                   'improved' => 'Pulang Paksa',
                   'unimproved' => 'Lari',
                   'referred' => 'Dirujuk ke RS Lain',
                   'transfer' => 'Dipindahkan',
                   'deceased_under_48h' => 'Meninggal &lt; 48 Jam',
                   'deceased_over_48h' => 'Meninggal &gt;= 48 Jam',
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
         <Cell ss:StyleID="sCellCenter"><Data ss:Type="String"><?= $isDeceased ? ($adm->discharge_condition === 'deceased_under_48h' ? '&lt; 48 Jam' : '&gt;= 48 Jam') : '-' ?></Data></Cell>

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
    <Cell ss:MergeAcross="3" ss:StyleID="sFooterTotal"><Data ss:Type="String">TOTAL REKAPITULASI SENSUS (<?= htmlspecialchars(strtoupper($currLabel)) ?>)</Data></Cell>
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
