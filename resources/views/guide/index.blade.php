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
            Modul Dashboard Utama
        </div>
        <ul class="rule-list">
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Setelah berhasil login, sistem akan menampilkan halaman <strong>Dashboard Utama</strong></div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Pada Dashboard Utama, dapat dilihat jumlah <strong>Total Ruangan</strong> yang terdaftar dalam sistem</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Pasien Aktif Dirawat</strong> menampilkan jumlah pasien yang sedang menjalani perawatan Kapasitas Tempat Tidur</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Pending Mutasi</strong> menampilkan jumlah data mutasi pasien yang masih dalam proses</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Seluruh informasi pada Dashboard Utama disajikan sebagai ringkasan data sensus dan kondisi ruangan sehingga informasi dapat dipantau dengan lebih mudah</div>
            </li>
        </ul>
    </div>

    <!-- Modul Steps -->
    <div class="guide-section" style="border-left: 4px solid var(--primary);">
        <div class="guide-title">
            Modul Sensus Harian & Export
        </div>
        <ul class="rule-list">
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Pilih ruangan dan tanggal sensus pada menu <strong>Sensus Harian</strong></div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Isi form <strong>Pasien Masuk (MRS)</strong> dengan Tanggal & Jam MRS lengkap (`YYYY-MM-DD HH:mm`)</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Untuk memproses pasien keluar, klik tombol <strong>Catat KRS</strong> pada tabel pasien aktif</div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Klik tombol <strong>Export Rekap Sensus (CSV/Excel)</strong> untuk mengunduh rekapitulasi bulanan ruangan</div>
            </li>
        </ul>
    </div>

    <div class="guide-section" style="border-left: 4px solid var(--primary);">
        <div class="guide-title">
            Modul Mutasi Pindahan (Transfer In / Out)
        </div>
        <ul class="rule-list">
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Ruangan asal mengisi form <strong>Permohonan Mutasi (Transfer Out)</strong></div>
            </li>
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Buka menu <strong>Mutasi Pasien</strong>, lalu pilih <strong>Terima Pasien</strong> untuk memproses penerimaan pasien</div>
            </li>
        </ul>
    </div>

    <div class="guide-section" style="border-left: 4px solid var(--primary);">
        <div class="guide-title">
            Modul Profil Akun Admin Ruangan
        </div>
        <ul class="rule-list">
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div>Akun Admin Ruangan didaftarkan langsung oleh <strong>Petugas SHRI (Superadmin)</strong>. <strong>Jika belum memiliki akun</strong>, silakan hubungi Tim Rekam Medis / Petugas SHRI</div>
            </li>    
            <li class="rule-item">
                <div class="bullet-dot"></div>
                <div><strong>Admin ruangan</strong> dapat melakukan perubahan data akun dan kata sandi</div>
            </li>    
        </ul>
    </div>

@endsection
