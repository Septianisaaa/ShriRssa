@extends('layouts.app')

@section('title', 'Mutasi Pindahan Pasien - SHRI RSSA')
@section('header_title', 'Mutasi Pasien Pindahan (Transfer In / Out)')
@section('header_subtitle', 'Konfirmasi Mutasi Pasien Antar Ruangan Perawatan RSUD Dr. Saiful Anwar')

@section('content')

    <!-- Pending Transfers Inbox -->
    <div class="card">
        <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.85rem; color: #b45309; display: flex; align-items: center; gap: 0.4rem;">
            <i class="fa-solid fa-inbox"></i> Permohonan Mutasi Masuk (Transfer In) - Pending
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
                                <form action="{{ route('transfers.accept', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                                        <i class="fa-solid fa-check"></i> Terima Pasien
                                    </button>
                                </form>

                                <form action="{{ route('transfers.reject', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; color: #991b1b; border-color: #fecaca; background: #fef2f2;">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 1.25rem; color: var(--text-muted);">
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
        <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.85rem; color: var(--text-dark); display: flex; align-items: center; gap: 0.4rem;">
            <i class="fa-solid fa-history" style="color: var(--primary);"></i> Riwayat Mutasi Pindahan
        </h3>

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
                            <td colspan="7" style="text-align: center; padding: 1.25rem; color: var(--text-muted);">
                                Belum ada riwayat mutasi pasien.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
