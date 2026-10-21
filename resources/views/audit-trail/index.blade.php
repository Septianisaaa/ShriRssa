@extends('layouts.app')

@section('title', 'Audit Trail & Log Aktivitas - SHRI RSSA')
@section('header_title', 'Audit Trail & Log Aktivitas')
@section('header_subtitle', 'Pemantauan riwayat aktivitas sistem, perubahan data, dan rekam jejak pengguna')

@push('styles')
<style>
    /* Metric Cards Grid */
    .audit-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.15rem;
        margin-bottom: 1.5rem;
    }

    .audit-metric-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .audit-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .audit-metric-top {
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .audit-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .icon-total { background: #fefce8; color: #b89628; border: 1px solid #fde047; }
    .icon-today { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .icon-users { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .icon-module { background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff; }

    .audit-metric-label {
        font-size: 0.775rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .audit-metric-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.2;
        margin-top: 0.2rem;
    }

    .audit-metric-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.65rem;
        padding-top: 0.65rem;
        border-top: 1px dashed var(--border-light);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-light);
    }

    .filter-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
        gap: 0.85rem;
        align-items: flex-end;
    }

    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    .filter-input-wrap {
        position: relative;
    }

    .filter-input-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .filter-input-has-icon {
        padding-left: 2.35rem !important;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* Table & Log Rows */
    .log-table {
        width: 100%;
        border-collapse: collapse;
    }

    .log-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .log-table td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .log-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .log-table tbody tr:hover {
        background-color: #fafbfc;
    }

    /* User Cell */
    .user-profile-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 180px;
    }

    .user-avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fefce8, #ebd06c);
        color: #854d0e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
        border: 1px solid #fde047;
    }

    .user-meta-name {
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.25;
    }

    .user-meta-username {
        font-size: 0.725rem;
        color: var(--text-muted);
    }

    /* Action Badges */
    .badge-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .badge-action-create {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .badge-action-update {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .badge-action-delete {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .badge-action-login {
        background: #faf5ff;
        color: #7e22ce;
        border-color: #e9d5ff;
    }

    .badge-action-logout {
        background: #f1f5f9;
        color: #475569;
        border-color: #cbd5e1;
    }

    .badge-action-default {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    /* Module Badge */
    .badge-module {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #ffffff;
        color: #334155;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    /* Method & URL Badge */
    .endpoint-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.7rem;
        color: #64748b;
        background: #f8fafc;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        margin-top: 0.3rem;
    }

    .method-tag {
        font-weight: 700;
        font-size: 0.65rem;
        padding: 0.05rem 0.25rem;
        border-radius: 3px;
    }

    .method-post { background: #dcfce7; color: #166534; }
    .method-put, .method-patch { background: #dbeafe; color: #1e40af; }
    .method-delete { background: #fee2e2; color: #991b1b; }
    .method-get { background: #f1f5f9; color: #475569; }

    .ip-badge {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.75rem;
        color: #475569;
        background: #f8fafc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .time-main {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.825rem;
        white-space: nowrap;
    }

    .time-sub {
        font-size: 0.725rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
        white-space: nowrap;
    }

    /* Action Buttons in Row */
    .btn-detail {
        background: #ffffff;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.15s ease;
    }

    .btn-detail:hover {
        background: var(--bg-app);
        border-color: var(--primary-dark);
        color: #854d0e;
    }

    /* Modal Styles */
    .audit-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .audit-modal-backdrop.show {
        display: flex;
        opacity: 1;
    }

    .audit-modal-content {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border-light);
        transform: scale(0.96);
        transition: transform 0.2s ease;
    }

    .audit-modal-backdrop.show .audit-modal-content {
        transform: scale(1);
    }

    .audit-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .audit-modal-body {
        padding: 1.5rem;
    }

    .audit-modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        background: #f8fafc;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 0.75rem 1rem;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .detail-label {
        color: var(--text-muted);
        font-weight: 600;
    }

    .detail-value {
        color: var(--text-dark);
        font-weight: 500;
        word-break: break-word;
    }

    .json-box {
        background: #0f172a;
        color: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.775rem;
        overflow-x: auto;
        max-height: 250px;
        line-height: 1.5;
    }

    /* Pagination */
    .pagination-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-light);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .pagination-bar nav {
        display: flex;
        gap: 0.25rem;
    }

    /* Empty state */
    .empty-state-box {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fefce8;
        color: #b89628;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #fde047;
    }
</style>
@endpush

@section('content')

    <!-- 1. Ringkasan Statistik -->
    <div class="audit-metrics-grid">
        <div class="audit-metric-card">
            <div class="audit-metric-top">
                <div class="audit-metric-icon icon-total">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="audit-metric-label">Total Aktivitas</div>
                    <div class="audit-metric-value">{{ number_format($totalActivities) }}</div>
                </div>
            </div>
            <div class="audit-metric-desc">
                <i class="fa-solid fa-database"></i> Seluruh riwayat transaksi log
            </div>
        </div>

        <div class="audit-metric-card">
            <div class="audit-metric-top">
                <div class="audit-metric-icon icon-today">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <div class="audit-metric-label">Aktivitas Hari Ini</div>
                    <div class="audit-metric-value">{{ number_format($todayActivities) }}</div>
                </div>
            </div>
            <div class="audit-metric-desc">
                <i class="fa-solid fa-calendar-day"></i> Transaksi terekam hari ini
            </div>
        </div>

        <div class="audit-metric-card">
            <div class="audit-metric-top">
                <div class="audit-metric-icon icon-users">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="audit-metric-label">Pengguna Aktif</div>
                    <div class="audit-metric-value">{{ number_format($activeUsersToday) }}</div>
                </div>
            </div>
            <div class="audit-metric-desc">
                <i class="fa-solid fa-user-check"></i> Petugas yang beraktivitas hari ini
            </div>
        </div>
    </div>

    <!-- 2. Form Pencarian & Filter -->
    <div class="filter-card">
        <div class="filter-header">
            <div class="filter-title">
                <i class="fa-solid fa-filter" style="color: var(--primary-dark);"></i>
                <span>Filter & Pencarian Log</span>
            </div>
            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'action', 'module']))
                <a href="{{ route('shri.audit_trail.index') }}" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.65rem;">
                    <i class="fa-solid fa-xmark"></i> Hapus Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('shri.audit_trail.index') }}">
            <div class="filter-grid">
                <!-- Search Input -->
                <div>
                    <label class="form-label">Pencarian Keyword</label>
                    <div class="filter-input-wrap">
                        <i class="fa-solid fa-magnifying-glass filter-input-icon"></i>
                        <input type="text" name="search" class="form-control filter-input-has-icon" placeholder="Nama, username, aktivitas, IP..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Date From -->
                <div>
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <!-- Date To -->
                <div>
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <!-- Filter Aksi -->
                <div>
                    <label class="form-label">Jenis Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua Aksi</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" @selected(request('action') === $act)>
                                {{ match(strtolower($act)) {
                                    'create' => 'Tambah Data',
                                    'update' => 'Ubah Data',
                                    'delete' => 'Hapus Data',
                                    'login' => 'Login (Masuk)',
                                    'logout' => 'Logout (Keluar)',
                                    default => ucfirst($act)
                                } }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Modul -->
                <div>
                    <label class="form-label">Modul Sistem</label>
                    <select name="module" class="form-select">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" @selected(request('module') === $mod)>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary" title="Terapkan Filter">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                    <a href="{{ route('shri.audit_trail.index') }}" class="btn btn-secondary" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Tabel Riwayat Aktivitas -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.15rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-light); flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h3 class="section-title" style="margin-bottom: 0.15rem;">Catatan Aktivitas Pengguna</h3>
                <p style="font-size: 0.775rem; color: var(--text-muted);">
                    Rekaman audit otomatis transaksi perubahan data dan akses sistem
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <span class="badge badge-info" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                    <i class="fa-solid fa-shield-halved" style="margin-right: 0.35rem;"></i>
                    {{ number_format($activities->total()) }} Aktivitas Ditemukan
                </span>
            </div>
        </div>

        <div class="table-responsive" style="border: none; border-radius: 0;">
            <table class="log-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Waktu & Tanggal</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th>Keterangan Aktivitas</th>
                        <th>Alamat IP</th>
                        <th style="text-align: center; width: 90px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $item)
                        @php
                            $actionLabel = $item->action_label ?? ucfirst($item->action ?? 'Aktivitas');
                            $actionClass = $item->action_badge_class ?? 'badge-action-default';
                            $actionIcon = $item->action_icon ?? 'fa-solid fa-circle-dot';
                            $moduleIcon = $item->module_icon ?? 'fa-solid fa-layer-group';
                            $displayName = $item->user_name ?: ($item->user?->name ?: 'Sistem');
                            $displayUser = $item->username ?: ($item->user?->username ?: '-');
                            $initial = mb_strtoupper(mb_substr($displayName, 0, 1));
                            $isToday = $item->created_at ? $item->created_at->isToday() : false;
                        @endphp
                        <tr>
                            <!-- Waktu -->
                            <td>
                                <div class="time-main">
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                    @if($isToday)
                                        <span class="badge badge-success" style="font-size: 0.65rem; padding: 0.1rem 0.35rem; margin-left: 0.25rem;">Hari Ini</span>
                                    @endif
                                </div>
                                <div class="time-sub">
                                    <i class="fa-regular fa-clock" style="margin-right: 0.2rem;"></i>
                                    {{ $item->created_at ? $item->created_at->format('H:i:s') : '-' }} WIB
                                </div>
                            </td>

                            <!-- Pengguna -->
                            <td>
                                <div class="user-profile-cell">
                                    <div class="user-avatar-circle">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <div class="user-meta-name">{{ $displayName }}</div>
                                        <div class="user-meta-username">
                                            @if($item->user && $item->user->isSuperAdmin())
                                                <span style="color: #b89628; font-weight: 600;">@ {{ $displayUser }} (Admin SHRI)</span>
                                            @elseif($item->user && $item->user->isAdminRuang())
                                                <span style="color: #2563eb; font-weight: 500;">@ {{ $displayUser }} (Admin Ruang)</span>
                                            @else
                                                <span>@ {{ $displayUser }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td>
                                <span class="badge-action {{ $actionClass }}">
                                    <i class="{{ $actionIcon }}"></i>
                                    {{ $actionLabel }}
                                </span>
                            </td>

                            <!-- Modul -->
                            <td>
                                <span class="badge-module">
                                    <i class="{{ $moduleIcon }}" style="color: #64748b;"></i>
                                    {{ $item->module ?: '-' }}
                                </span>
                            </td>

                            <!-- Keterangan & URL Endpoint -->
                            <td>
                                <div style="font-weight: 500; color: var(--text-dark); line-height: 1.4;">
                                    {{ $item->description ?: '-' }}
                                </div>
                                @if($item->method || $item->url)
                                    <div class="endpoint-pill">
                                        @if($item->method)
                                            <span class="method-tag method-{{ strtolower($item->method) }}">
                                                {{ $item->method }}
                                            </span>
                                        @endif
                                        <span>{{ $item->url }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Alamat IP -->
                            <td>
                                <span class="ip-badge">
                                    <i class="fa-solid fa-network-wired" style="color: #94a3b8; font-size: 0.7rem;"></i>
                                    {{ $item->ip_address ?: '-' }}
                                </span>
                            </td>

                            <!-- Tombol Detail -->
                            <td style="text-align: center;">
                                <button type="button" 
                                    class="btn-detail" 
                                    onclick="openAuditDetail({{ json_encode([
                                        'id' => $item->id,
                                        'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i:s') . ' WIB' : '-',
                                        'user_name' => $displayName,
                                        'username' => $displayUser,
                                        'role' => $item->user ? ($item->user->isSuperAdmin() ? 'Petugas SHRI (Admin)' : 'Admin Ruangan') : 'Sistem',
                                        'action' => $actionLabel,
                                        'action_class' => $actionClass,
                                        'module' => $item->module,
                                        'description' => $item->description,
                                        'method' => $item->method,
                                        'url' => $item->url,
                                        'ip_address' => $item->ip_address,
                                        'user_agent' => $item->user_agent,
                                        'new_values' => $item->new_values,
                                        'old_values' => $item->old_values,
                                    ]) }})"
                                    title="Lihat Detail Transaksi">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Detail</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state-box">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-clipboard-list"></i>
                                    </div>
                                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.35rem;">
                                        Tidak Ada Log Aktivitas Ditemukan
                                    </h4>
                                    <p style="font-size: 0.825rem; color: var(--text-muted); max-width: 420px; margin: 0 auto 1.25rem;">
                                        Tidak ada catatan yang sesuai dengan kriteria filter pencarian Anda saat ini.
                                    </p>
                                    @if(request()->anyFilled(['search', 'date_from', 'date_to', 'action', 'module']))
                                        <a href="{{ route('shri.audit_trail.index') }}" class="btn btn-secondary">
                                            <i class="fa-solid fa-rotate-left"></i> Reset Filter Pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($activities->total() > 0)
            <div class="pagination-bar">
                <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Menampilkan <strong>{{ $activities->firstItem() ?? 0 }}</strong> – <strong>{{ $activities->lastItem() ?? 0 }}</strong> dari <strong>{{ $activities->total() }}</strong> catatan
                </div>
                <div>
                    {{ $activities->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- 4. Interactive Detail Modal -->
    <div id="auditDetailModal" class="audit-modal-backdrop" onclick="handleBackdropClick(event)">
        <div class="audit-modal-content">
            <div class="audit-modal-header">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #fefce8; color: #b89628; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-dark); margin: 0;">
                            Detail Log Aktivitas #<span id="modalLogId"></span>
                        </h4>
                        <div id="modalTimestamp" style="font-size: 0.75rem; color: var(--text-muted);"></div>
                    </div>
                </div>
                <button type="button" onclick="closeAuditModal()" style="background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 1.15rem; padding: 0.25rem;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="audit-modal-body">
                <!-- Info Ringkasan -->
                <div class="detail-grid">
                    <div class="detail-label">Pengguna:</div>
                    <div class="detail-value">
                        <strong id="modalUserName"></strong> 
                        <span id="modalUserRole" style="font-size: 0.775rem; color: var(--text-muted); margin-left: 0.35rem;"></span>
                    </div>

                    <div class="detail-label">Jenis Aksi:</div>
                    <div class="detail-value">
                        <span id="modalActionBadge" class="badge-action"></span>
                    </div>

                    <div class="detail-label">Modul:</div>
                    <div class="detail-value" id="modalModule"></div>

                    <div class="detail-label">Deskripsi:</div>
                    <div class="detail-value" id="modalDescription" style="font-weight: 600; color: #1e293b;"></div>

                    <div class="detail-label">Endpoint URL:</div>
                    <div class="detail-value">
                        <span id="modalMethod" class="method-tag method-post"></span>
                        <span id="modalUrl" style="font-family: monospace; font-size: 0.8rem; margin-left: 0.35rem;"></span>
                    </div>

                    <div class="detail-label">Alamat IP:</div>
                    <div class="detail-value">
                        <span class="ip-badge" id="modalIp"></span>
                    </div>

                    <div class="detail-label">Perangkat / Browser:</div>
                    <div class="detail-value" id="modalUserAgent" style="font-size: 0.775rem; color: var(--text-muted); line-height: 1.4;"></div>
                </div>

                <!-- Box Payload / Parameter Perubahan Data -->
                <div id="modalPayloadContainer" style="display: none; margin-top: 1rem;">
                    <label class="form-label" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>Data Input / Parameter Perubahan (Payload)</span>
                        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: normal;">Format JSON</span>
                    </label>
                    <pre id="modalJsonBox" class="json-box"></pre>
                </div>
            </div>

            <div class="audit-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAuditModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openAuditDetail(data) {
        document.getElementById('modalLogId').innerText = data.id;
        document.getElementById('modalTimestamp').innerText = data.created_at;
        document.getElementById('modalUserName').innerText = data.user_name + ' (@' + data.username + ')';
        document.getElementById('modalUserRole').innerText = '• ' + data.role;
        
        var actionBadge = document.getElementById('modalActionBadge');
        actionBadge.className = 'badge-action ' + (data.action_class || 'badge-action-default');
        actionBadge.innerText = data.action;

        document.getElementById('modalModule').innerText = data.module || '-';
        document.getElementById('modalDescription').innerText = data.description || '-';

        var methodEl = document.getElementById('modalMethod');
        var method = (data.method || 'GET').toUpperCase();
        methodEl.innerText = method;
        methodEl.className = 'method-tag method-' + method.toLowerCase();

        document.getElementById('modalUrl').innerText = data.url || '-';
        document.getElementById('modalIp').innerText = data.ip_address || '-';
        document.getElementById('modalUserAgent').innerText = data.user_agent || '-';

        var payloadBox = document.getElementById('modalPayloadContainer');
        var jsonBox = document.getElementById('modalJsonBox');

        if (data.new_values && Object.keys(data.new_values).length > 0) {
            jsonBox.textContent = JSON.stringify(data.new_values, null, 2);
            payloadBox.style.display = 'block';
        } else {
            payloadBox.style.display = 'none';
        }

        var modal = document.getElementById('auditDetailModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeAuditModal() {
        var modal = document.getElementById('auditDetailModal');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    function handleBackdropClick(event) {
        if (event.target === document.getElementById('auditDetailModal')) {
            closeAuditModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAuditModal();
        }
    });
</script>
@endpush
