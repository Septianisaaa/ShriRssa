@extends('layouts.app')

@section('title', 'Dashboard Utama - SHRI RSUD Dr. Saiful Anwar')
@section('header_title', 'Dashboard Ketersediaan Ruangan')
@section('header_subtitle', 'Pantau kapasitas tempat tidur dan status ruangan RSUD Dr. Saiful Anwar')

@push('styles')
<style>
    /* Metric Cards Grid */
    .metrics-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .metric-top {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .metric-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .icon-beds { background: #e6f4ea; color: #0f766e; }
    .icon-occupied { background: #e8f0fe; color: #1d4ed8; }
    .icon-available { background: #e6f4ea; color: #15803d; }
    .icon-pending-active { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .icon-pending-empty { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    .metric-info {
        display: flex;
        flex-direction: column;
    }

    .metric-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }

    .metric-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-top: 0.15rem;
    }

    .metric-progress-wrapper {
        margin-top: 0.75rem;
    }

    .mini-progress-bg {
        height: 6px;
        background: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .mini-progress-fill {
        height: 100%;
        border-radius: 3px;
    }

    .metric-subtext {
        font-size: 0.725rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .filter-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.85rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1.5fr;
        gap: 0.85rem;
        align-items: flex-end;
    }

    @media (max-width: 992px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Table Component */
    .table-container-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-header-bar {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .status-legend {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        font-size: 0.775rem;
        font-weight: 600;
        color: #64748b;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .dot-green { width: 9px; height: 9px; background: #22c55e; border-radius: 50%; }
    .dot-orange { width: 9px; height: 9px; background: #f59e0b; border-radius: 50%; }
    .dot-red { width: 9px; height: 9px; background: #ef4444; border-radius: 50%; }

    /* Custom Table Styling */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .custom-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.775rem;
        font-weight: 700;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .custom-table td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #1e293b;
        vertical-align: middle;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    .custom-table tr:hover td {
        background: #f8fafc;
    }

    /* Badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .pill-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .pill-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .pill-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Table Bar */
    .table-progress-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 130px;
    }

    .table-progress-bar {
        flex: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }

    .table-progress-fill {
        height: 100%;
        border-radius: 3px;
    }

    /* Pagination Footer */
    .table-footer {
        padding: 0.85rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #64748b;
        background: #ffffff;
    }

    .pagination-btns {
        display: flex;
        gap: 0.35rem;
    }

    .btn-page {
        padding: 0.3rem 0.65rem;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 6px;
        color: #334155;
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-page.active {
        background: #0f766e;
        color: #ffffff;
        border-color: #0f766e;
    }

    .btn-page:hover:not(.active) {
        background: #f8fafc;
    }
</style>
@endpush

@section('content')

    @php
        $rolePrefix = Auth::user()->isSuperAdmin() ? 'shri.' : 'admin_ruang.';
    @endphp

    <!-- Metrics Cards Grid -->
    <div class="metrics-row">
        <!-- Card 1: Total Tempat Tidur -->
        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon-box icon-beds">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Total Tempat Tidur</span>
                    <span class="metric-value">{{ number_format($totalBeds) }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">TT</span></span>
                </div>
            </div>
            <div class="metric-progress-wrapper">
                <div class="mini-progress-bg" style="background: transparent;"></div>
                <span class="metric-subtext" style="color: #64748b;">Kapasitas tempat tidur aktif</span>
            </div>
        </div>

        <!-- Card 2: Sedang Terisi -->
        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon-box icon-occupied">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Sedang Terisi</span>
                    <span class="metric-value">{{ number_format($activePatients) }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">TT</span></span>
                </div>
            </div>
            <div class="metric-progress-wrapper">
                <div class="mini-progress-bg">
                    <div class="mini-progress-fill" style="width: {{ $occupiedPct }}%; background: #2563eb;"></div>
                </div>
                <span class="metric-subtext">{{ $occupiedPct }}% terisi</span>
            </div>
        </div>

        <!-- Card 3: Tersedia -->
        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon-box icon-available">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Tersedia</span>
                    <span class="metric-value">{{ number_format($availableBeds) }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">TT</span></span>
                </div>
            </div>
            <div class="metric-progress-wrapper">
                <div class="mini-progress-bg">
                    <div class="mini-progress-fill" style="width: {{ $availablePct }}%; background: #16a34a;"></div>
                </div>
                <span class="metric-subtext">{{ $availablePct }}% tersedia</span>
            </div>
        </div>

        <!-- Card 4: Pending Mutasi -->
        <div class="metric-card">
            <div class="metric-top">
                <div class="metric-icon-box {{ $pendingTransfersCount > 0 ? 'icon-pending-active' : 'icon-pending-empty' }}">
                    <i class="{{ $pendingTransfersCount > 0 ? 'fa-solid fa-clock-rotate-left' : 'fa-regular fa-clock' }}"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Pending Mutasi</span>
                    <span class="metric-value" style="color: {{ $pendingTransfersCount > 0 ? '#dc2626' : '#0f172a' }};">
                        {{ $pendingTransfersCount }} <span style="font-size: 0.85rem; font-weight: 600; color: {{ $pendingTransfersCount > 0 ? '#dc2626' : '#64748b' }};">kali</span>
                    </span>
                </div>
            </div>
            <div class="metric-progress-wrapper">
                <div class="mini-progress-bg" style="background: {{ $pendingTransfersCount > 0 ? '#fecaca' : 'transparent' }};">
                    <div class="mini-progress-fill" style="width: {{ $pendingTransfersCount > 0 ? '100%' : '0%' }}; background: #dc2626;"></div>
                </div>
                @if($pendingTransfersCount > 0)
                    <span class="metric-subtext" style="color: #dc2626; font-weight: 700;">
                        <i class="fa-solid fa-circle-exclamation"></i> Ada {{ $pendingTransfersCount }} mutasi pending
                    </span>
                @else
                    <span class="metric-subtext" style="color: #64748b;">
                        Tidak ada permohonan mutasi
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Ruangan Bar -->
    <div class="filter-card">
        <div class="filter-title">Filter Ruangan</div>
        <form method="GET" action="{{ route($rolePrefix . 'dashboard') }}">
            <div class="filter-grid">
                <div>
                    <label class="form-label">Instalasi</label>
                    <select name="instalasi" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($allInstalasi as $inst)
                            <option value="{{ $inst }}" {{ $filterInstalasi === $inst ? 'selected' : '' }}>{{ $inst }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($allClasses as $cls)
                            <option value="{{ $cls }}" {{ $filterKelas === $cls ? 'selected' : '' }}>{{ $cls }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="Tersedia" {{ $filterStatus === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Hampir Penuh" {{ $filterStatus === 'Hampir Penuh' ? 'selected' : '' }}>Hampir Penuh</option>
                        <option value="Penuh" {{ $filterStatus === 'Penuh' ? 'selected' : '' }}>Penuh</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Cari Ruangan</label>
                    <div style="position: relative;">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama ruangan..." value="{{ $filterSearch }}" style="padding-left: 2.25rem;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Rooms Table Container -->
    <div class="table-container-card">
        <div class="table-header-bar">
            <div style="font-weight: 700; font-size: 0.95rem; color: #0f172a;">
                Daftar Ketersediaan Ruangan
            </div>
            
            <!-- Status Legend -->
            <div class="status-legend">
                <div class="legend-item">
                    <span class="dot-green"></span>
                    <span>Tersedia (&le; 70%)</span>
                </div>
                <div class="legend-item">
                    <span class="dot-orange"></span>
                    <span>Hampir Penuh (71–99%)</span>
                </div>
                <div class="legend-item">
                    <span class="dot-red"></span>
                    <span>Penuh (100%)</span>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="border: none; border-radius: 0;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Ruangan</th>
                        <th>Instalasi</th>
                        <th>Kelas</th>
                        <th>Kode Ruangan</th>
                        <th>Status</th>
                        <th>Terisi / Total TT</th>
                        <th>Persentase</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        @php
                            $pct = $room->occupancy_percentage;
                            $barColor = ($pct >= 100) ? '#ef4444' : (($pct >= 71) ? '#f59e0b' : '#22c55e');
                        @endphp
                        <tr>
                            <td style="color: #64748b; font-weight: 500;">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $room->name }}</strong>
                            </td>
                            <td style="color: #64748b; font-weight: 500;">
                                {{ $room->category }}
                            </td>
                            <td>
                                <span style="font-weight: 500; color: #334155;">{{ $room->room_class }}</span>
                            </td>
                            <td>
                                <span style="color: #64748b; font-weight: 600; font-size: 0.8rem;">{{ $room->code }}</span>
                            </td>
                            <td>
                                <span class="status-pill pill-{{ $room->status_badge_class }}">
                                    <span class="dot-{{ $room->status_badge_class === 'danger' ? 'red' : ($room->status_badge_class === 'warning' ? 'orange' : 'green') }}"></span>
                                    {{ $room->status_label }}
                                </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">
                                {{ $room->current_patients }} / {{ $room->capacity }}
                            </td>
                            <td>
                                <div class="table-progress-cell">
                                    <div class="table-progress-bar">
                                        <div class="table-progress-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                                    </div>
                                    <span style="font-weight: 600; font-size: 0.775rem; color: #475569;">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route($rolePrefix . 'sensus.index', ['room_id' => $room->id]) }}#tabel-pasien" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.775rem; font-weight: 700; border-radius: 6px;">
                                    Lihat Detail <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem; margin-left: 0.2rem;"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem; color: #64748b;">
                                Tidak ada data ruangan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div>
                Menampilkan 1 – {{ $rooms->count() }} dari {{ $rooms->count() }} ruangan
            </div>
            <div class="pagination-btns">
                <a href="#" class="btn-page">&lt;</a>
                <a href="#" class="btn-page active">1</a>
                <a href="#" class="btn-page">&gt;</a>
            </div>
        </div>
    </div>

@endsection
