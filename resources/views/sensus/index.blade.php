@extends('layouts.app')

@section('title', 'Sensus Harian Ruangan - SHRI RSSA')
@section('header_title', 'Sensus Harian Ruangan: ' . $room->name)
@section('header_subtitle', 'Pencatatan Pasien Masuk (MRS), Pasien Keluar (KRS), dan Mutasi Ruangan')

@section('content')

    @php
        $authUser = Auth::user();
        $isSuper = $authUser->isSuperAdmin();
        $rolePrefix = $isSuper ? 'shri.' : 'admin_ruang.';
    @endphp

    <!-- Selector Bar & Export Button -->
    <div class="card" style="padding: 1rem 1.25rem; margin-bottom: 1.25rem;">
        <form method="GET" action="{{ route($rolePrefix . 'sensus.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px;">
                <label class="form-label">Pilih Ruangan:</label>
                @if($isSuper)
                    <select name="room_id" class="form-select" onchange="this.form.submit()">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ $r->id == $room->id ? 'selected' : '' }}>
                                {{ $r->name }} ({{ $r->category }})
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="form-control" value="{{ $room->name }} ({{ $room->category }})" readonly style="background: #f1f5f9; cursor: not-allowed;">
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                @endif
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label class="form-label">Tanggal Sensus:</label>
                <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()">
            </div>

            <button type="button" class="btn btn-success" style="height: 38px; font-weight: 700;" onclick="openExportModal()">
                <i class="fa-solid fa-file-excel"></i> Export Rekap Sensus (Excel)
            </button>
        </form>
    </div>

    <!-- Forms Section Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
        
        <!-- Form Pasien Masuk (MRS) -->
        <div class="card">
            <h3 class="section-title">Form Pasien Masuk (MRS)</h3>

            <form action="{{ route($rolePrefix . 'sensus.patient.store') }}" method="POST">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                <div class="form-group">
                    <label class="form-label">No. Rekam Medis (No. RM):</label>
                    <input type="text" name="rm_number" class="form-control" placeholder="Contoh: 12001001" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Pasien:</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama Pasien" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.85rem;">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin:</label>
                        <select name="gender" class="form-select" required>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas Perawatan:</label>
                        <select name="room_class" class="form-select" required>
                            <option value="Kelas 1" {{ $room->room_class == 'Kelas 1' ? 'selected' : '' }}>Kelas 1</option>
                            <option value="Kelas 2" {{ $room->room_class == 'Kelas 2' ? 'selected' : '' }}>Kelas 2</option>
                            <option value="Kelas 3" {{ $room->room_class == 'Kelas 3' ? 'selected' : '' }}>Kelas 3</option>
                            <option value="VIP" {{ $room->room_class == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="VVIP" {{ $room->room_class == 'VVIP' ? 'selected' : '' }}>VVIP</option>
                            <option value="Non Kelas" {{ str_contains($room->room_class, 'Khusus') || str_contains($room->room_class, 'Intensif') || $room->room_class == 'Non Kelas' ? 'selected' : '' }}>Non Kelas / Khusus</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal & Jam MRS:</label>
                        <input type="datetime-local" name="admission_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Diagnosa Masuk:</label>
                    <input type="text" name="diagnosis" class="form-control" placeholder="Diagnosa">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Simpan Pasien Masuk
                </button>
            </form>
        </div>

        <!-- Form Transfer Out -->
        <div class="card">
            <h3 class="section-title">Permohonan Mutasi (Transfer Out)</h3>

            <form action="{{ route($rolePrefix . 'transfers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Pilih Pasien Aktif:</label>
                    <select name="admission_id" class="form-select" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($activeAdmissions as $adm)
                            <option value="{{ $adm->id }}">
                                {{ $adm->patient->name }} (RM: {{ $adm->patient->rm_number }}) - MRS: {{ $adm->admission_date->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ruangan Tujuan:</label>
                    <select name="to_room_id" class="form-select" required>
                        <option value="">-- Pilih Ruangan Tujuan --</option>
                        @foreach($allRooms as $r)
                            @if($r->id != $room->id)
                                <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->category }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal & Jam Transfer:</label>
                    <input type="datetime-local" name="transfer_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Pindah:</label>
                    <input type="text" name="notes" class="form-control" placeholder="Alasan pindah">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                    Kirim Transfer Out
                </button>
            </form>
        </div>

    </div>

    <!-- Active Patients Table -->
    <div class="card" id="tabel-pasien">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 class="section-title" style="margin-bottom: 0;">Pasien Dirawat di {{ $room->name }} ({{ $activeAdmissions->count() }})</h3>
            <span class="badge badge-success">{{ $room->capacity - $activeAdmissions->count() }} Bed Kosong</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>JK</th>
                        <th>Kelas</th>
                        <th>Tanggal & Jam MRS</th>
                        <th>Diagnosa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeAdmissions as $adm)
                        <tr>
                            <td><strong>{{ $adm->patient->rm_number }}</strong></td>
                            <td><strong>{{ $adm->patient->name }}</strong></td>
                            <td>{{ $adm->patient->gender }}</td>
                            <td><span class="badge badge-warning" style="font-size: 0.75rem;">{{ $adm->room_class ?? $room->room_class }}</span></td>
                            <td>
                                {{ $adm->admission_date->format('d/m/Y H:i') }}
                                @if($adm->admission_date->month != now()->month)
                                    <span class="badge badge-warning" style="font-size: 0.65rem;">Lintas Bulan</span>
                                @endif
                            </td>
                            <td>{{ $adm->diagnosis ?? '-' }}</td>
                            <td><span class="badge badge-info">Dirawat</span></td>
                            <td>
                                <button type="button" class="btn btn-primary" style="padding: 0.3rem 0.75rem; font-size: 0.775rem;" onclick="openDischargeModal('{{ $adm->id }}', '{{ $adm->patient->name }}')">
                                    Catat KRS
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                Tidak ada pasien aktif di ruangan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Discharge -->
    <div id="dischargeModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; align-items: center; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 440px; padding: 1.5rem; border-radius: 8px;">
            <h3 class="section-title" id="modalPatientTitle" style="margin-bottom: 1rem;">
                Proses Pasien KRS
            </h3>

            <form id="dischargeForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tanggal & Jam KRS:</label>
                    <input type="datetime-local" name="discharge_date" id="modalDischargeDate" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Keadaan KRS:</label>
                    <select name="discharge_condition" class="form-select" required>
                        <option value="cured">Sembuh</option>
                        <option value="improved">Membaik</option>
                        <option value="unimproved">Belum Sembuh</option>
                        <option value="referred">Dirujuk ke RS Lain</option>
                        <option value="aps">Atas Permintaan Sendiri (APS)</option>
                        <option value="deceased">Meninggal (Otomatis &lt;48j / &ge;48j)</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.25rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeDischargeModal()">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Pasien KRS</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Export Excel / CSV -->
    <div id="exportModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; align-items: center; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 480px; padding: 1.5rem; border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                <h3 class="section-title" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem; color: #166534;">
                    <i class="fa-solid fa-file-excel" style="font-size: 1.25rem; color: #16a34a;"></i> Export Rekapitulasi Sensus Excel
                </h3>
                <button type="button" onclick="closeExportModal()" style="background: none; border: none; font-size: 1.35rem; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
            </div>

            <form action="{{ route($rolePrefix . 'sensus.monthly.export') }}" method="GET" target="_blank">
                <div class="form-group">
                    <label class="form-label">Pilih Ruangan:</label>
                    @if($isSuper)
                        <select name="room_id" class="form-select" required>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}" {{ $r->id == $room->id ? 'selected' : '' }}>
                                    {{ $r->name }} ({{ $r->category }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $room->name }} ({{ $room->category }})" readonly style="background: #f1f5f9; cursor: not-allowed;">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                    @endif
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                    <div class="form-group">
                        <label class="form-label">Pilih Bulan:</label>
                        <select name="month" class="form-select" required>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                                $selectedMonth = (int) \Carbon\Carbon::parse($selectedDate)->month;
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $selectedMonth ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Tahun:</label>
                        <select name="year" class="form-select" required>
                            @php
                                $selectedYear = (int) \Carbon\Carbon::parse($selectedDate)->year;
                                $years = range(2024, 2028);
                            @endphp
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeExportModal()">Batal</button>
                    <button type="submit" class="btn btn-success" style="font-weight: 700;" onclick="setTimeout(closeExportModal, 500)">
                        <i class="fa-solid fa-download"></i> Download Excel (.xls)
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openDischargeModal(admissionId, patientName) {
        var isSuper = {{ $isSuper ? 'true' : 'false' }};
        var routePrefix = isSuper ? '/shri' : '/admin-ruang';
        
        document.getElementById('modalPatientTitle').innerText = 'Proses KRS: ' + patientName;
        document.getElementById('dischargeForm').action = routePrefix + '/sensus/discharge/' + admissionId;
        
        var now = new Date();
        var localNow = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
        
        document.getElementById('modalDischargeDate').value = localNow;
        document.getElementById('dischargeModal').style.display = 'flex';
    }

    function closeDischargeModal() {
        document.getElementById('dischargeModal').style.display = 'none';
    }

    function openExportModal() {
        document.getElementById('exportModal').style.display = 'flex';
    }

    function closeExportModal() {
        document.getElementById('exportModal').style.display = 'none';
    }
</script>
@endpush
