@extends('layouts.app')

@section('title', 'Mutasi Pindahan Pasien - SHRI RSSA')
@section('header_title', 'Mutasi Pasien Pindahan Antar Ruang (Transfer In / Transfer Out)')
@section('header_subtitle', 'Solusi Terinterkoneksi Otomatis Antar Ruangan Perawatan RSUD Dr. Saiful Anwar Malang')

@section('content')

    <!-- Pending Transfers Inbox -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; color: #f59e0b; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-inbox"></i> Inbox Permohonan Mutasi Masuk (Transfer In) - Pending Confirmation
        </h3>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Transfer</th>
                        <th>No. RM Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Dari Ruangan</th>
                        <th>Tujuan Ruangan</th>
                        <th>Catatan Pindah</th>
                        <th>Aksi Konfirmasi Transfer In</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingTransfers as $tf)
                        <tr>
                            <td>{{ $tf->transfer_date->format('d/m/Y H:i') }}</td>
                            <td><strong style="color: #60a5fa;">{{ $tf->admission->patient->rm_number }}</strong></td>
                            <td><strong>{{ $tf->admission->patient->name }}</strong></td>
                            <td><span class="badge badge-info">{{ $tf->fromRoom->name }}</span></td>
                            <td><span class="badge badge-warning">{{ $tf->toRoom->name }}</span></td>
                            <td>{{ $tf->notes ?? '-' }}</td>
                            <td style="display: flex; gap: 0.5rem;">
                                <form action="{{ route('transfers.accept', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-check"></i> Terima Pasien
                                    </button>
                                </form>

                                <form action="{{ route('transfers.reject', $tf->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; background: rgba(239, 68, 68, 0.2); color: #f87171;">
                                        <i class="fa-solid fa-circle-xmark"></i> Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
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
        <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-clock-rotate-left" style="color: var(--accent);"></i> Riwayat Mutasi Pindahan Pasien Terakhir
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
                        <th>Status Mutasi</th>
                        <th>Waktu Diterima</th>
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
                                    <span class="badge badge-success">Selesai (Transfer In Accepted)</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $ht->accepted_at ? $ht->accepted_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                Belum ada riwayat mutasi pasien.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
