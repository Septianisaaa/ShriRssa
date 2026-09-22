@extends('layouts.app')

@section('title', 'Mutasi Pindahan Pasien - SHRI RSSA')
@section('header_title', 'Mutasi Pasien Pindahan (Transfer In / Out)')
@section('header_subtitle', 'Konfirmasi Mutasi Pasien Antar Ruangan Perawatan RSUD Dr. Saiful Anwar')

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
                            <td><span class="badge badge-info">{{ $tf->fromRoom->name }}</span></td>
                            <td><span class="badge badge-warning">{{ $tf->toRoom->name }}</span></td>
                            <td>{{ $tf->notes ?? '-' }}</td>
                            <td style="display: flex; gap: 0.4rem;">
                                <form action="{{ route($rolePrefix . 'transfers.accept', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 0.3rem 0.75rem; font-size: 0.775rem;">
                                        Terima Pasien
                                    </button>
                                </form>

                                <form action="{{ route($rolePrefix . 'transfers.reject', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.3rem 0.75rem; font-size: 0.775rem; color: #991b1b; border-color: #fecaca; background: #fef2f2;">
                                        Tolak
                                    </button>
                                </form>
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
    <div class="card">
        <h3 class="section-title">Riwayat Mutasi Pindahan</h3>

        <div class="table-responsive">
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
                            <td>{{ $ht->fromRoom->name }}</td>
                            <td>{{ $ht->toRoom->name }}</td>
                            <td>
                                @if($ht->status === 'accepted')
                                    <span class="badge badge-success">Diterima</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $ht->accepted_at ? $ht->accepted_at->format('d/m/Y H:i') : '-' }}</td>
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
    </div>

@endsection


