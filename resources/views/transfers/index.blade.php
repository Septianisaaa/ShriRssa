@extends('layouts.app')

@section('title', 'Mutasi Pindahan Pasien - SHRI RSSA')
@section('header_title', 'Mutasi Pasien Pindahan (Transfer In / Out)')
@section('header_subtitle', 'Konfirmasi Mutasi Pasien Antar Ruangan Perawatan RSUD Dr. Saiful Anwar')

@push('styles')
<style>
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

    .btn-page:hover:not(.active):not(.disabled) {
        background: #f8fafc;
    }

    .btn-page.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
        background: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
    }
</style>
@endpush

@section('content')

    @php
        $rolePrefix = Auth::user()->isSuperAdmin() ? 'shri.' : 'admin_ruang.';
    @endphp

    <!-- Pending Transfers Inbox -->
    <div class="card">
        <h3 class="section-title" style="color: #b45309;">
            Permohonan Mutasi Masuk (Transfer In) - Pending
        </h3>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Mutasi</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Dari Ruangan</th>
                        <th>Tujuan Ruangan</th>
                        <th>Catatan</th>
                        <th>Aksi Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingTransfers as $tf)
                        <tr>
                            <td>{{ $tf->transfer_date->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $tf->admission->patient->rm_number }}</strong></td>
                            <td><strong>{{ $tf->admission->patient->name }}</strong></td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $tf->fromRoom->name }} ({{ $tf->fromRoom->category }})
                                </span>
                                <br>
                                <small style="color: #64748b;">
                                    {{ $tf->fromRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ $tf->toRoom->name }} ({{ $tf->toRoom->category }})
                                </span>
                                <br>
                                <small style="color: #64748b;">
                                    {{ $tf->toRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>{{ $tf->notes ?? '-' }}</td>
                            <td>
                                @php
                                    $user = Auth::user();

                                    $isSuper = $user->isSuperAdmin();

                                    $isToRoom = $user->isAdminRuang()
                                        && $user->room_id == $tf->to_room_id;

                                    $isFromRoom = $user->isAdminRuang()
                                        && $user->room_id == $tf->from_room_id;
                                @endphp

                                @if($isSuper || $isToRoom)

                                    <div style="display: flex; gap: 0.4rem;">
                                        <form action="{{ route($rolePrefix . 'transfers.accept', $tf->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-success"
                                                    style="padding: 0.3rem 0.75rem; font-size: 0.775rem;">
                                                Terima Pasien
                                            </button>
                                        </form>

                                        <form action="{{ route($rolePrefix . 'transfers.reject', $tf->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-secondary"
                                                    style="padding: 0.3rem 0.75rem; font-size: 0.775rem; color: #991b1b; border-color: #fecaca; background: #fef2f2;">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>

                                @elseif($isFromRoom)

                                    <span class="badge badge-warning">
                                        Menunggu Konfirmasi Ruangan Tujuan
                                    </span>

                                @else

                                    <span style="color: var(--text-muted);">
                                        Menunggu Konfirmasi
                                    </span>

                                @endif
                            </td>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                Tidak ada permohonan mutasi pindahan pasien yang pending saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transfer History -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1.25rem 1.25rem 0.75rem 1.25rem;">
            <h3 class="section-title" style="margin-bottom: 0;">Riwayat Mutasi Pindahan</h3>
        </div>

        <div class="table-responsive" style="border: none;">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Mutasi</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Dari Ruang</th>
                        <th>Ke Ruang</th>
                        <th>Status</th>
                        <th>Waktu Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyTransfers as $ht)
                        <tr>
                            <td>{{ $ht->transfer_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $ht->admission->patient->rm_number }}</td>
                            <td>{{ $ht->admission->patient->name }}</td>
                            <td>
                                {{ $ht->fromRoom->name }} ({{ $ht->fromRoom->category }})
                                <br>
                                <small style="color: #64748b;">
                                    {{ $ht->fromRoom->room_class ?? '-' }}
                                </small>
                            </td>

                            <td>
                                {{ $ht->toRoom->name }} ({{ $ht->toRoom->category }})
                                <br>
                                <small style="color: #64748b;">
                                    {{ $ht->toRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>
                                @if($ht->status === 'accepted')
                                    <span class="badge badge-success">Diterima</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                {{ $ht->accepted_at ? $ht->accepted_at->format('d/m/Y H:i') : ($ht->updated_at ? $ht->updated_at->format('d/m/Y H:i') : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                Belum ada riwayat mutasi pasien.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div>
                @if($historyTransfers->total() > 0)
                    Menampilkan {{ $historyTransfers->firstItem() }} – {{ $historyTransfers->lastItem() }} dari {{ $historyTransfers->total() }} riwayat mutasi
                @else
                    Menampilkan 0 riwayat mutasi
                @endif
            </div>
            @if($historyTransfers->hasPages())
                <div class="pagination-btns">
                    {{-- Previous Page Link --}}
                    @if($historyTransfers->onFirstPage())
                        <span class="btn-page disabled">&lt;</span>
                    @else
                        <a href="{{ $historyTransfers->previousPageUrl() }}" class="btn-page">&lt;</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($historyTransfers->getUrlRange(1, $historyTransfers->lastPage()) as $page => $url)
                        @if($page == $historyTransfers->currentPage())
                            <span class="btn-page active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="btn-page">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($historyTransfers->hasMorePages())
                        <a href="{{ $historyTransfers->nextPageUrl() }}" class="btn-page">&gt;</a>
                    @else
                        <span class="btn-page disabled">&gt;</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

@endsection


