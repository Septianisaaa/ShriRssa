@extends('layouts.app')

@section('title', 'Dashboard Utama - SHRI RSUD Dr. Saiful Anwar')
@section('header_title', 'Dashboard Sensus Ruangan')
@section('header_subtitle', 'Keterisian Tempat Tidur & Status Ruangan RSUD Dr. Saiful Anwar')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.15rem 1.25rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-top: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1rem;
    }

    .room-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.15rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
    }

    .room-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    }

    .progress-bar-container {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin: 0.75rem 0;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')

    @php
        $rolePrefix = Auth::user()->isSuperAdmin() ? 'shri.' : 'admin_ruang.';
    @endphp

    <!-- Metrics Cards -->
    <div class="stats-grid">
        <div class="stat-card" style="border-top: 3px solid var(--primary);">
            <div class="stat-label">Total Ruangan</div>
            <div class="stat-value">{{ $totalRooms }}</div>
        </div>

        <div class="stat-card" style="border-top: 3px solid #16a34a;">
            <div class="stat-label">Kapasitas Tempat Tidur</div>
            <div class="stat-value">{{ $totalBeds }} TT</div>
        </div>

        <div class="stat-card" style="border-top: 3px solid #d97706;">
            <div class="stat-label">Pasien Aktif Dirawat</div>
            <div class="stat-value">{{ $activePatients }}</div>
        </div>

        <div class="stat-card" style="border-top: 3px solid #dc2626;">
            <div class="stat-label">Pending Mutasi</div>
            <div class="stat-value">{{ $pendingTransfers }}</div>
        </div>
    </div>

    <!-- Filter Category Bar -->
    <div class="card" style="padding: 0.85rem 1.25rem; margin-bottom: 1.25rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-weight: 700; font-size: 0.875rem; color: var(--text-dark);">
                Kategori Ruangan:
            </div>
            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                <a href="{{ route($rolePrefix . 'dashboard') }}" class="btn {{ !$categoryFilter ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.3rem 0.75rem; font-size: 0.775rem;">Semua</a>
                @foreach($categories as $cat)
                    <a href="{{ route($rolePrefix . 'dashboard', ['category' => $cat]) }}" class="btn {{ $categoryFilter === $cat ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.3rem 0.75rem; font-size: 0.775rem;">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Room Status Cards -->
    <div class="room-grid">
        @foreach($rooms as $room)
            @php
                $current = $room->current_patients;
                $cap = $room->capacity;
                $pct = ($cap > 0) ? min(100, round(($current / $cap) * 100)) : 0;
                $color = ($pct > 85) ? '#dc2626' : (($pct > 65) ? '#d97706' : '#16a34a');
            @endphp
            <div class="room-card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-dark); letter-spacing: -0.01em;">{{ $room->name }}</h4>
                        <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">{{ $room->floor }} | Kode: {{ $room->code }}</span>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.7rem;">{{ $room->category }}</span>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $pct }}%; background: {{ $color }};"></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; font-weight: 500;">
                    <div>Terisi: <strong>{{ $current }} / {{ $cap }} TT</strong></div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">{{ $cap - $current }} Kosong</div>
                </div>

                <div style="margin-top: 0.85rem;">
                    <a href="{{ route($rolePrefix . 'census.index', ['room_id' => $room->id]) }}" class="btn btn-secondary" style="width: 100%; justify-content: center; padding: 0.35rem; font-size: 0.775rem;">
                        Sensus Ruangan
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection

