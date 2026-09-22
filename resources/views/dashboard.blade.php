@extends('layouts.app')

@section('title', 'Dashboard Utama - SHRI RSUD Dr. Saiful Anwar')
@section('header_title', 'Dashboard Sensus Ruangan')
@section('header_subtitle', 'Keterisian Tempat Tidur & Status Ruangan RSUD Dr. Saiful Anwar')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    
    .stat-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 0.85rem;
    }

    .room-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1rem;
        transition: border-color 0.15s ease;
    }

    .room-card:hover {
        border-color: var(--primary);
    }

    .progress-bar-container {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin: 0.65rem 0;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')

    <!-- Metrics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--primary-light); color: var(--primary-dark);">
                <i class="fa-solid fa-hospital"></i>
            </div>
            <div>
                <div class="stat-label">Total Ruangan</div>
                <div class="stat-value">{{ $totalRooms }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #15803d;">
                <i class="fa-solid fa-bed"></i>
            </div>
            <div>
                <div class="stat-label">Kapasitas Tempat Tidur</div>
                <div class="stat-value">{{ $totalBeds }} TT</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fffbeb; color: #b45309;">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <div class="stat-label">Pasien Aktif Dirawat</div>
                <div class="stat-value">{{ $activePatients }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fef2f2; color: #b91c1c;">
                <i class="fa-solid fa-right-left"></i>
            </div>
            <div>
                <div class="stat-label">Pending Mutasi</div>
                <div class="stat-value">{{ $pendingTransfers }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Category Bar -->
    <div class="card" style="padding: 0.75rem 1rem; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-dark);">
                <i class="fa-solid fa-filter" style="color: var(--primary); margin-right: 0.3rem;"></i>
                Kategori Ruangan:
            </div>
            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" class="btn {{ !$categoryFilter ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.25rem 0.65rem; font-size: 0.775rem;">Semua</a>
                @foreach($categories as $cat)
                    <a href="{{ route('dashboard', ['category' => $cat]) }}" class="btn {{ $categoryFilter === $cat ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.25rem 0.65rem; font-size: 0.775rem;">{{ $cat }}</a>
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
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--text-dark);">{{ $room->name }}</h4>
                        <span style="font-size: 0.725rem; color: var(--text-muted);">{{ $room->floor }} | Kode: {{ $room->code }}</span>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.7rem;">{{ $room->category }}</span>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: {{ $pct }}%; background: {{ $color }};"></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem;">
                    <div>Terisi: <strong>{{ $current }} / {{ $cap }} TT</strong></div>
                    <div style="font-size: 0.725rem; color: var(--text-muted);">{{ $cap - $current }} Kosong</div>
                </div>

                <div style="margin-top: 0.75rem;">
                    <a href="{{ route('census.index', ['room_id' => $room->id]) }}" class="btn btn-secondary" style="width: 100%; justify-content: center; padding: 0.3rem; font-size: 0.775rem;">
                        <i class="fa-solid fa-pen-to-square"></i> Sensus Ruangan
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
