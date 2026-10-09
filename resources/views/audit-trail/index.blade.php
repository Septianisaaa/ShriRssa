```blade
@extends('layouts.app')

@section('title', 'Audit Trail - SHRI RSSA')
@section('header_title', 'Audit Trail')
@section('header_subtitle', 'Monitoring aktivitas pengguna sistem SHRI')

@section('content')

<style>
    .audit-page {
        --audit-gold: #b89628;
        --audit-gold-light: #fbf6e5;
        --audit-ink: #283548;
        --audit-muted: #7a8493;
        --audit-border: #e9edf2;
        color: var(--audit-ink);
        padding-bottom: 24px;
    }

    .audit-page .audit-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }

    .audit-page .audit-title {
        font-size: 21px;
        font-weight: 750;
        letter-spacing: -.5px;
        margin: 0 0 5px;
        color: var(--audit-ink);
    }

    .audit-page .audit-subtitle {
        font-size: 13px;
        color: var(--audit-muted);
        margin: 0;
    }

    .audit-page .audit-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #e6ebdf;
        border-radius: 30px;
        padding: 9px 13px;
        background: #fff;
        color: #61705b;
        font-size: 12px;
        font-weight: 600;
    }

    .audit-page .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #49a579;
    }

    .audit-page .summary-card {
        background: #fff;
        border: 1px solid var(--audit-border);
        border-radius: 13px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 3px 12px rgba(28, 39, 56, .025);
    }

    .audit-page .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .audit-page .summary-label {
        color: var(--audit-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .audit-page .summary-icon {
        width: 37px;
        height: 37px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--audit-gold-light);
        color: var(--audit-gold);
        font-size: 15px;
    }

    .audit-page .summary-number {
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -.7px;
        margin-bottom: 6px;
        color: var(--audit-ink);
    }

    .audit-page .summary-note {
        font-size: 11px;
        color: var(--audit-muted);
    }

    .audit-page .audit-panel {
        border: 1px solid var(--audit-border);
        border-radius: 13px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(28, 39, 56, .025);
    }

    .audit-page .panel-heading {
        padding: 18px 21px;
        border-bottom: 1px solid var(--audit-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .audit-page .panel-title {
        font-size: 14px;
        font-weight: 750;
        margin: 0;
        color: var(--audit-ink);
    }

    .audit-page .panel-description {
        font-size: 12px;
        color: var(--audit-muted);
        margin: 5px 0 0;
    }

    .audit-page .filter-body {
        padding: 20px 21px;
    }

    .audit-page .form-label {
        font-size: 12px;
        font-weight: 650;
        color: #596577;
        margin-bottom: 7px;
    }

    .audit-page .form-control,
    .audit-page .form-select {
        min-height: 41px;
        border: 1px solid #e1e6ed;
        border-radius: 8px;
        font-size: 12px;
        color: var(--audit-ink);
        box-shadow: none;
    }

    .audit-page .form-control:focus,
    .audit-page .form-select:focus {
        border-color: #c5aa56;
        box-shadow: 0 0 0 3px rgba(184, 150, 40, .10);
    }

    .audit-page .btn-audit {
        background: var(--audit-gold);
        color: #fff;
        border: 1px solid var(--audit-gold);
        border-radius: 8px;
        padding: 10px 17px;
        font-size: 12px;
        font-weight: 650;
    }

    .audit-page .btn-audit:hover {
        background: #a58420;
        color: #fff;
    }

    .audit-page .btn-reset {
        background: #fff;
        border: 1px solid #e1e6ed;
        color: #596577;
        border-radius: 8px;
        padding: 10px 17px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .audit-page .btn-reset:hover {
        background: #f7f8fa;
    }

    .audit-page .record-count {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        background: var(--audit-gold-light);
        color: #8a6b16;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .audit-page .audit-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .audit-page .audit-table thead th {
        background: #f8f9fb;
        color: #788394;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .65px;
        font-weight: 750;
        padding: 14px 16px;
        border-bottom: 1px solid var(--audit-border);
        white-space: nowrap;
    }

    .audit-page .audit-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #eff1f4;
        vertical-align: middle;
        font-size: 12px;
    }

    .audit-page .audit-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .audit-page .audit-table tbody tr:hover td {
        background: #fdfcf8;
    }

    .audit-page .date-main {
        font-weight: 700;
        color: #374255;
        white-space: nowrap;
    }

    .audit-page .muted-text {
        color: var(--audit-muted);
        font-size: 11px;
        margin-top: 4px;
    }

    .audit-page .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 155px;
    }

    .audit-page .user-avatar {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f0df;
        color: #9a7b21;
        font-weight: 750;
        font-size: 12px;
    }

    .audit-page .user-name {
        font-weight: 700;
        color: #374255;
        overflow-wrap: anywhere;
    }

    .audit-page .action-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .audit-page .action-create {
        background: #e9f7ef;
        color: #278153;
    }

    .audit-page .action-update {
        background: #eaf2ff;
        color: #3569b7;
    }

    .audit-page .action-delete {
        background: #fff0ef;
        color: #c34b46;
    }

    .audit-page .action-default {
        background: #f0f2f5;
        color: #657184;
    }

    .audit-page .module-tag {
        display: inline-block;
        border: 1px solid #e7eaf0;
        background: #fff;
        color: #566174;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .audit-page .description-cell {
        min-width: 210px;
        max-width: 340px;
        color: #566174;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .audit-page .request-detail {
        margin-top: 5px;
        color: #929baa;
        font-size: 10px;
        overflow-wrap: anywhere;
    }

    .audit-page .ip-tag {
        font-size: 11px;
        color: #697587;
        white-space: nowrap;
    }

    .audit-page .empty-state {
        text-align: center;
        padding: 48px 20px;
    }

    .audit-page .empty-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #f6f3e8;
        color: #ae8d2d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 19px;
    }

    .audit-page .empty-title {
        color: #374255;
        font-weight: 750;
        font-size: 13px;
    }

    .audit-page .empty-description {
        color: var(--audit-muted);
        font-size: 12px;
        margin-top: 5px;
    }

    .audit-page .pagination-wrap {
        padding: 16px 21px;
        border-top: 1px solid var(--audit-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .audit-page .pagination {
        margin: 0;
    }

    .audit-page .pagination .page-link {
        color: #596577;
        border-color: #e5e9ef;
        font-size: 12px;
    }

    .audit-page .pagination .active .page-link {
        background: var(--audit-gold);
        border-color: var(--audit-gold);
        color: #fff;
    }

    @media (max-width: 767px) {
        .audit-page .summary-card {
            padding: 16px;
        }

        .audit-page .summary-number {
            font-size: 23px;
        }

        .audit-page .filter-body {
            padding: 16px;
        }

        .audit-page .panel-heading {
            padding: 16px;
        }

        .audit-page .pagination-wrap {
            padding: 14px 16px;
        }
    }
</style>

<div class="container-fluid audit-page">

    {{-- Header --}}
    <div class="audit-heading">
        <div>
            <h4 class="audit-title">Riwayat Aktivitas</h4>
            <p class="audit-subtitle">
                Pantau aktivitas pengguna dan perubahan pada sistem SHRI.
            </p>
        </div>

        <div class="audit-status">
            <span class="status-dot"></span>
            Pemantauan aktivitas
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="summary-card">
                <div class="summary-top">
                    <span class="summary-label">Total Aktivitas</span>
                    <span class="summary-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($totalActivities) }}
                </div>

                <div class="summary-note">
                    Seluruh catatan aktivitas
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="summary-card">
                <div class="summary-top">
                    <span class="summary-label">Aktivitas Hari Ini</span>
                    <span class="summary-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($todayActivities) }}
                </div>

                <div class="summary-note">
                    Catatan pada hari ini
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="summary-card">
                <div class="summary-top">
                    <span class="summary-label">Pengguna Hari Ini</span>
                    <span class="summary-icon">
                        <i class="fa-solid fa-users"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($activeUsersToday) }}
                </div>

                <div class="summary-note">
                    Pengguna berbeda yang beraktivitas
                </div>
            </div>
        </div>

    </div>

    {{-- Filter --}}
    <div class="audit-panel mb-4">
        <div class="panel-heading">
            <div>
                <h6 class="panel-title">Filter Aktivitas</h6>
                <p class="panel-description">
                    Cari catatan berdasarkan pengguna, tanggal, atau modul.
                </p>
            </div>
        </div>

        <div class="filter-body">
            <form method="GET"
                  action="{{ route('shri.audit_trail.index') }}">

                <div class="row g-3">

                    <div class="col-12 col-lg-4">
                        <label for="search" class="form-label">
                            Pencarian
                        </label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Nama, aktivitas, modul, IP..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="date_from" class="form-label">
                            Dari Tanggal
                        </label>
                        <input
                            type="date"
                            id="date_from"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="date_to" class="form-label">
                            Sampai Tanggal
                        </label>
                        <input
                            type="date"
                            id="date_to"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="action" class="form-label">
                            Aktivitas
                        </label>
                        <select id="action" name="action" class="form-select">
                            <option value="">Semua Aktivitas</option>
                            @foreach ($actions as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected(request('action') === $item)>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="module" class="form-label">
                            Modul
                        </label>
                        <select id="module" name="module" class="form-select">
                            <option value="">Semua Modul</option>
                            @foreach ($modules as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected(request('module') === $item)>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2 pt-1">
                        <button type="submit" class="btn btn-audit">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>
                            Terapkan Filter
                        </button>

                        <a href="{{ route('shri.audit_trail.index') }}"
                           class="btn-reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="audit-panel">

        <div class="panel-heading">
            <div>
                <h6 class="panel-title">Catatan Aktivitas Pengguna</h6>
                <p class="panel-description">
                    Detail aktivitas yang tercatat di dalam sistem.
                </p>
            </div>

            <span class="record-count">
                {{ number_format($activities->total()) }} catatan
            </span>
        </div>

        <div class="table-responsive">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Modul</th>
                        <th>Keterangan</th>
                        <th>Alamat IP</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($activities as $activity)
                        @php
                            $action = strtolower($activity->action ?? '');

                            $actionClass = match ($action) {
                                'create', 'created', 'tambah', 'store' =>
                                    'action-create',

                                'update', 'updated', 'ubah', 'edit' =>
                                    'action-update',

                                'delete', 'deleted', 'hapus', 'destroy' =>
                                    'action-delete',

                                default => 'action-default',
                            };

                            $displayName = $activity->user_name
                                ?: ($activity->user?->name ?: '-');

                            $initial = $displayName !== '-'
                                ? mb_strtoupper(mb_substr($displayName, 0, 1))
                                : '?';
                        @endphp

                        <tr>
                            <td>
                                <div class="date-main">
                                    {{ $activity->created_at
                                        ? $activity->created_at->format('d M Y')
                                        : '-' }}
                                </div>
                                <div class="muted-text">
                                    {{ $activity->created_at
                                        ? $activity->created_at->format('H:i:s')
                                        : '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ $initial }}
                                    </div>

                                    <div>
                                        <div class="user-name">
                                            {{ $displayName }}
                                        </div>
                                        <div class="muted-text">
                                            {{ $activity->username
                                                ?: ($activity->user?->username ?: '-') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="action-badge {{ $actionClass }}">
                                    {{ $activity->action ?: 'Aktivitas' }}
                                </span>
                            </td>

                            <td>
                                <span class="module-tag">
                                    {{ $activity->module ?: '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="description-cell">
                                    {{ $activity->description ?: '-' }}
                                </div>

                                @if ($activity->method || $activity->url)
                                    <div class="request-detail">
                                        {{ $activity->method }}
                                        {{ $activity->url }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <span class="ip-tag">
                                    {{ $activity->ip_address ?: '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-regular fa-clipboard"></i>
                                    </div>

                                    <div class="empty-title">
                                        Belum ada aktivitas ditemukan
                                    </div>

                                    <div class="empty-description">
                                        Coba ubah filter atau periksa kembali
                                        pencatatan aktivitas sistem.
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($activities->hasPages())
            <div class="pagination-wrap">
                <small class="text-secondary">
                    Menampilkan {{ $activities->firstItem() }}
                   –{{ $activities->lastItem() }}
                    dari {{ $activities->total() }} catatan
                </small>

                {{ $activities->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
