@extends('layouts.app')

@section('title', 'Buku Panduan - SHRI RSSA')
@section('header_title', 'Buku Panduan Pengguna')
@section('header_subtitle', 'Panduan Penggunaan Sistem Sensus Harian Rawat Inap RSUD Dr. Saiful Anwar Malang')

@push('styles')
<style>
    .guide-section {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .guide-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.85rem;
        letter-spacing: -0.01em;
    }

    .rule-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.875rem;
        color: var(--text-dark);
        line-height: 1.5;
    }

    .bullet-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: var(--primary);
        margin-top: 0.55rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

    <!-- Quick Rules Summary Card -->
    <div class="guide-section" style="border-left: 4px solid var(--primary);">
        <div class="guide-title">
            Aturan Medis & Formula Otomatis Sistem
        </div>
        <ul class="rule-list">
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Rumus Lama Dirawat (LD)</strong>: $\text{LD} = \text{Tanggal KRS} - \text{Tanggal MRS}$. Mendukung pencatatan LD lintas bulan.</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Pasien One Day Care (ODC)</strong>: Pasien MRS & KRS pada hari yang sama otomatis memiliki $\text{LD} = 1\text{ hari}$ (bukan 0 hari).</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Otomatisasi Pasien Meninggal</strong>: Pembacaan otomatis status meninggal $< 48\text{ jam}$ atau $\ge 48\text{ jam}$ berdasarkan selisih jam MRS hingga KRS.</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Mutasi Pindah Ruangan</strong>: Pasien pindah ruangan otomatis terakumulasi di ruangan baru saat permohonan dikonfirmasi.</div>
            </li>
        </ul>
    </div>

    <!-- Modul Steps -->
    <div class="guide-section">
        <div class="guide-title">
            Modul Sensus Harian & Export
        </div>
        <ol style="margin-left: 1.25rem; font-size: 0.875rem; color: var(--text-dark); display: flex; flex-direction: column; gap: 0.65rem;">
            <li>Pilih ruangan dan tanggal sensus pada menu <strong>Sensus Harian</strong>.</li>
            <li>Isi form <strong>Pasien Masuk (MRS)</strong> dengan Tanggal & Jam MRS lengkap (`YYYY-MM-DD HH:mm`).</li>
            <li>Untuk memproses pasien keluar, klik tombol <strong>Catat KRS</strong> pada tabel pasien aktif.</li>
            <li>Klik tombol <strong>Export Rekap Sensus (CSV/Excel)</strong> untuk mengunduh rekapitulasi bulanan ruangan.</li>
        </ol>
    </div>

    <div class="guide-section">
        <div class="guide-title">
            Modul Mutasi Pindahan (Transfer In / Out)
        </div>
        <ol style="margin-left: 1.25rem; font-size: 0.875rem; color: var(--text-dark); display: flex; flex-direction: column; gap: 0.65rem;">
            <li>Ruangan asal mengisi form <strong>Permohonan Mutasi (Transfer Out)</strong>.</li>
            <li>Petugas ruangan tujuan membuka menu <strong>Mutasi Pindahan</strong> dan mengeklik <strong>Terima Pasien</strong>.</li>
        </ol>
    </div>

@endsection

