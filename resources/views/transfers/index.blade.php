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
            <table class="tabel-mutasi-filter" data-table-type="pending">
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
                        <tr class="baris-mutasi"
                            data-tanggal="{{ $tf->transfer_date ? \Carbon\Carbon::parse($tf->transfer_date)->format('Y-m-d') : '' }}"
                            data-status="{{ strtolower($tf->status) }}"
                            data-rm="{{ $tf->admission->patient->rm_number ?? '' }}"
                            data-nama="{{ $tf->admission->patient->name ?? '' }}"
                            data-asal="{{ $tf->from_room_id }}"
                            data-tujuan="{{ $tf->to_room_id }}">
                            <td>{{ $tf->transfer_date->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $tf->admission->patient->rm_number }}</strong></td>
                            <td><strong>{{ $tf->admission->patient->name }}</strong></td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $tf->fromRoom->name }} ({{ $tf->fromRoom->installation }})
                                </span>
                                <br>
                                <small style="color: #64748b;">
                                    {{ $tf->fromRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ $tf->toRoom->name }} ({{ $tf->toRoom->installation }})
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

                                        <form action="{{ route($rolePrefix . 'transfers.reject', $tf->id) }}"
                                            method="POST"
                                            class="form-tolak">
                                            @csrf

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    style="padding: 0.3rem 0.75rem; font-size: 0.775rem; color: #991b1b; border-color: #fecaca; background: #fef2f2;"
                                                    onclick="bukaModalTolak(this)">
                                                Tolak
                                            </button>

                                            <input type="hidden" name="rejection_reason" class="input-alasan-tolak">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                Tidak ada permohonan mutasi pindahan pasien yang pending saat ini.
                            </td>
                        </tr>
                    @endforelse
                    <tr class="baris-kosong-filter" style="display: none;">
                        <td colspan="7" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                            Tidak ada permohonan mutasi yang sesuai dengan filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

        {{-- Filter berlaku pada data yang sedang ditampilkan di kedua tabel. --}}
    <div class="card" style="margin-bottom: 1rem;">
        <h3 class="section-title">Filter Mutasi</h3>
        <div class="filter-mutasi-grid">
            <div>
                <label for="filterPencarian">Cari pasien</label>
                <input id="filterPencarian" type="search" placeholder="Nama pasien atau No. RM">
            </div>
            <div>
                <label for="filterTanggal">Tanggal mutasi</label>
                <input id="filterTanggal" type="date">
            </div>
            <div>
                <label for="filterStatus">Status</label>
                <select id="filterStatus">
                    <option value="">Semua status</option>
                    <option value="pending">Menunggu konfirmasi</option>
                    <option value="accepted">Diterima</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div>
                <label for="filterRuangAsal">Ruang Asal</label>
                <select id="filterRuangAsal">
                    <option value="">Semua ruang asal</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->installation }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterRuangTujuan">Ruang Tujuan</label>
                <select id="filterRuangTujuan">
                    <option value="">Semua ruang tujuan</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->installation }})</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-mutasi-actions">
                <button type="button" class="btn btn-secondary" id="resetFilterMutasi">Reset Filter</button>
            </div>
        </div>
        <small id="hasilFilterMutasi" style="display:block; margin-top:.65rem; color:var(--text-muted);"></small>
    </div>
    
    <!-- Transfer History -->
    <div class="card">
        <h3 class="section-title">Riwayat Mutasi Pindahan</h3>

        <div class="table-responsive">
            <table class="tabel-mutasi-filter" data-table-type="history">
                <thead>
                    <tr>
                        <th>Tanggal Mutasi</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Dari Ruang</th>
                        <th>Ke Ruang</th>
                        <th>Catatan Mutasi</th>
                        <th>Status</th>
                        <th>Alasan Penolakan</th>
                        <th>Waktu Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyTransfers as $ht)
                        <tr class="baris-mutasi"
                            data-tanggal="{{ $ht->transfer_date ? \Carbon\Carbon::parse($ht->transfer_date)->format('Y-m-d') : '' }}"
                            data-status="{{ strtolower($ht->status) }}"
                            data-rm="{{ $ht->admission->patient->rm_number ?? '' }}"
                            data-nama="{{ $ht->admission->patient->name ?? '' }}"
                            data-asal="{{ $ht->from_room_id }}"
                            data-tujuan="{{ $ht->to_room_id }}">
                            <td>{{ $ht->transfer_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $ht->admission->patient->rm_number }}</td>
                            <td>{{ $ht->admission->patient->name }}</td>
                            <td>
                                {{ $ht->fromRoom->name }} ({{ $ht->fromRoom->installation }})
                                <br>
                                <small style="color: #64748b;">
                                    {{ $ht->fromRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>
                                {{ $ht->toRoom->name }} ({{ $ht->toRoom->installation }})
                                <br>
                                <small style="color: #64748b;">
                                    {{ $ht->toRoom->room_class ?? '-' }}
                                </small>
                            </td>
                            <td>{{ $ht->notes ?? '-' }}</td>
                            <td>
                                @if ($ht->status === 'accepted')
                                    <span class="badge badge-success">Diterima</span>
                                @elseif ($ht->status === 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($ht->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($ht->status === 'rejected')
                                    {{ $ht->notes ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $waktuKonfirmasi = $ht->confirmed_at ?? $ht->updated_at ?? null;
                                @endphp
                                {{ $waktuKonfirmasi
                                    ? \Carbon\Carbon::parse($waktuKonfirmasi)->format('d/m/Y H:i')
                                    : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                Belum ada riwayat mutasi pasien.
                            </td>
                        </tr>
                    @endforelse
                    <tr class="baris-kosong-filter" style="display: none;">
                        <td colspan="9" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                            Tidak ada riwayat mutasi yang sesuai dengan filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

        <!-- Modal Alasan Penolakan -->
<div id="modalTolak" class="modal-tolak">
    <div class="modal-tolak-content">

        <h3>Alasan Penolakan</h3>

        <p>
            Silakan masukkan alasan mengapa permohonan mutasi pasien ini ditolak.
        </p>

        <textarea
            id="alasanTolak"
            rows="4"
            placeholder="Contoh: Ruangan tujuan penuh atau pasien belum memenuhi kriteria pemindahan..."
        ></textarea>

        <div class="modal-tolak-actions">

            <button type="button"
                    class="btn-batal-tolak"
                    onclick="tutupModalTolak()">
                Batal
            </button>

            <button type="button"
                    class="btn-konfirmasi-tolak"
                    onclick="konfirmasiTolak()">
                Tolak Pasien
            </button>

        </div>

    </div>
</div>

<style>
    .filter-mutasi-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.3fr 1.3fr auto;
        gap: 0.85rem;
        align-items: end;
    }
    .filter-mutasi-grid label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    .filter-mutasi-grid input,
    .filter-mutasi-grid select {
        width: 100%;
        min-height: 40px;
        padding: 0.5rem 0.65rem;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #fff;
        box-sizing: border-box;
        font: inherit;
    }
    .filter-mutasi-actions { display: flex; align-items: end; }
    @media (max-width: 1200px) {
        .filter-mutasi-grid { grid-template-columns: repeat(3, minmax(140px, 1fr)); }
    }
    @media (max-width: 768px) {
        .filter-mutasi-grid { grid-template-columns: repeat(2, minmax(140px, 1fr)); }
    }
    @media (max-width: 520px) {
        .filter-mutasi-grid { grid-template-columns: 1fr; }
    }
    .modal-tolak {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 9999;

        align-items: center;
        justify-content: center;
    }

    .modal-tolak-content {
        width: 450px;
        max-width: 90%;
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-tolak-content h3 {
        margin: 0 0 8px 0;
        color: #991b1b;
    }

    .modal-tolak-content p {
        margin-bottom: 15px;
        color: #64748b;
        font-size: 0.9rem;
    }

    #alasanTolak {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        resize: vertical;
        font-family: inherit;
        font-size: 0.9rem;
    }

    #alasanTolak:focus {
        outline: none;
        border-color: #dc2626;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
    }

    .modal-tolak-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-batal-tolak {
        padding: 9px 16px;
        border: 1px solid #cbd5e1;
        background: white;
        color: #475569;
        border-radius: 7px;
        cursor: pointer;
    }

    .btn-batal-tolak:hover {
        background: #f8fafc;
    }

    .btn-konfirmasi-tolak {
        padding: 9px 16px;
        border: none;
        background: #dc2626;
        color: white;
        border-radius: 7px;
        cursor: pointer;
    }

    .btn-konfirmasi-tolak:hover {
        background: #b91c1c;
    }
</style>

<script>
    // Filter sisi browser untuk kedua tabel yang sedang ditampilkan.
    (function () {
        const pencarian = document.getElementById('filterPencarian');
        const tanggal = document.getElementById('filterTanggal');
        const status = document.getElementById('filterStatus');
        const ruangAsal = document.getElementById('filterRuangAsal');
        const ruangTujuan = document.getElementById('filterRuangTujuan');
        const reset = document.getElementById('resetFilterMutasi');
        const hasil = document.getElementById('hasilFilterMutasi');

        function terapkanFilterMutasi() {
            const kata = (pencarian.value || '').trim().toLocaleLowerCase();
            const tanggalDipilih = tanggal.value;
            const statusDipilih = status.value;
            const asalDipilih = ruangAsal.value;
            const tujuanDipilih = ruangTujuan.value;
            let terlihat = 0;
            let total = 0;

            document.querySelectorAll('.baris-mutasi').forEach(function (baris) {
                total++;
                const cocokCari = !kata ||
                    (baris.dataset.nama || '').toLocaleLowerCase().includes(kata) ||
                    (baris.dataset.rm || '').toLocaleLowerCase().includes(kata);
                const cocokTanggal = !tanggalDipilih || baris.dataset.tanggal === tanggalDipilih;
                const cocokStatus = !statusDipilih || baris.dataset.status === statusDipilih;
                const cocokAsal = !asalDipilih || baris.dataset.asal === asalDipilih;
                const cocokTujuan = !tujuanDipilih || baris.dataset.tujuan === tujuanDipilih;
                const cocok = cocokCari && cocokTanggal && cocokStatus && cocokAsal && cocokTujuan;
                baris.hidden = !cocok;
                baris.style.display = cocok ? '' : 'none';
                if (cocok) terlihat++;
            });

            document.querySelectorAll('.tabel-mutasi-filter').forEach(function (tabel) {
                const barisDiTabel = tabel.querySelectorAll('.baris-mutasi');
                if (barisDiTabel.length > 0) {
                    const adaVisible = Array.from(barisDiTabel).some(b => !b.hidden && b.style.display !== 'none');
                    const barisKosong = tabel.querySelector('.baris-kosong-filter');
                    if (barisKosong) {
                        barisKosong.style.display = adaVisible ? 'none' : '';
                        barisKosong.hidden = adaVisible;
                    }
                }
            });
            hasil.textContent = 'Menampilkan ' + terlihat + ' dari ' + total + ' baris mutasi pada kedua tabel.';
        }

        [pencarian, tanggal, status, ruangAsal, ruangTujuan].forEach(function (elemen) {
            elemen.addEventListener('input', terapkanFilterMutasi);
            elemen.addEventListener('change', terapkanFilterMutasi);
        });
        reset.addEventListener('click', function () {
            pencarian.value = '';
            tanggal.value = '';
            status.value = '';
            ruangAsal.value = '';
            ruangTujuan.value = '';
            terapkanFilterMutasi();
        });
        terapkanFilterMutasi();
    })();

    let formTolakAktif = null;

    function bukaModalTolak(button) {

        formTolakAktif = button.closest('.form-tolak');

        document.getElementById('alasanTolak').value = '';

        document.getElementById('modalTolak').style.display = 'flex';

        setTimeout(function () {
            document.getElementById('alasanTolak').focus();
        }, 100);
    }

    function tutupModalTolak() {

        document.getElementById('modalTolak').style.display = 'none';

        formTolakAktif = null;
    }

    function konfirmasiTolak() {

        const alasan = document
            .getElementById('alasanTolak')
            .value
            .trim();

        if (alasan === '') {

            alert('Alasan penolakan wajib diisi.');

            document.getElementById('alasanTolak').focus();

            return;
        }

        formTolakAktif
            .querySelector('.input-alasan-tolak')
            .value = alasan;

        formTolakAktif.submit();
    }

    // Klik area luar modal untuk menutup
    document.getElementById('modalTolak').addEventListener('click', function (event) {

        if (event.target === this) {
            tutupModalTolak();
        }

    });
</script>
@endsection


