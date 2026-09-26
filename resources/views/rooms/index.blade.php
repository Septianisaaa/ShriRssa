@extends('layouts.app')

@section('title', 'Kelola Ruangan, Instalasi & Kelas - SHRI RSSA')
@section('header_title', 'Kelola Ruangan, Instalasi & Kelas Perawatan')
@section('header_subtitle', 'Tambah dan atur daftar instalasi, kelas perawatan, serta kapasitas tempat tidur (TT)')

@section('content')

    <!-- Top Summary Metrics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
        <div class="card" style="padding: 1.15rem 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Total Ruangan</div>
            <div style="font-size: 1.65rem; font-weight: 800; color: var(--text-dark); margin-top: 0.2rem;">{{ $rooms->count() }}</div>
        </div>
        <div class="card" style="padding: 1.15rem 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Total Tempat Tidur (TT)</div>
            <div style="font-size: 1.65rem; font-weight: 800; color: #166534; margin-top: 0.2rem;">{{ $rooms->sum('capacity') }} Bed</div>
        </div>
        <div class="card" style="padding: 1.15rem 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Jumlah Instalasi</div>
            <div style="font-size: 1.65rem; font-weight: 800; color: #1d4ed8; margin-top: 0.2rem;">{{ $existingCategories->count() }} Instalasi</div>
        </div>
        <div class="card" style="padding: 1.15rem 1.25rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Jumlah Kelas Perawatan</div>
            <div style="font-size: 1.65rem; font-weight: 800; color: #854d0e; margin-top: 0.2rem;">{{ $allClasses->count() }} Kelas</div>
        </div>
    </div>

    <!-- Action Bar & Search Filter -->
    <div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <form method="GET" action="{{ route('shri.rooms.index') }}" style="display: flex; gap: 0.85rem; flex: 1; min-width: 280px; flex-wrap: wrap;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama ruangan, kode, instalasi..." value="{{ request('search') }}" style="max-width: 260px;">
                
                <select name="category" class="form-select" onchange="this.form.submit()" style="max-width: 200px;">
                    <option value="">-- Semua Instalasi --</option>
                    @foreach($existingCategories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="room_class" class="form-select" onchange="this.form.submit()" style="max-width: 200px;">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($allClasses as $cls)
                        <option value="{{ $cls }}" {{ request('room_class') == $cls ? 'selected' : '' }}>{{ $cls }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-secondary">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </form>

            <button type="button" class="btn btn-primary" onclick="openAddRoomModal()" style="font-weight: 700; height: 38px;">
                <i class="fa-solid fa-plus"></i> Tambah Ruangan / Instalasi / Kelas
            </button>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card">
        <h3 class="section-title" style="margin-bottom: 1rem;">Daftar Ruangan Rawat Inap ({{ $rooms->count() }})</h3>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Ruangan</th>
                        <th>Instalasi (Kategori)</th>
                        <th>Kelas Perawatan</th>
                        <th>Kapasitas TT</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $r->code }}</strong></td>
                            <td><strong>{{ $r->name }}</strong></td>
                            <td><span class="badge badge-info">{{ $r->category }}</span></td>
                            <td><span class="badge badge-warning">{{ $r->room_class }}</span></td>
                            <td><strong>{{ $r->capacity }} Bed</strong></td>
                            <td>
                                @if($r->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-primary" style="padding: 0.3rem 0.65rem; font-size: 0.775rem;" onclick="openEditRoomModal({{ json_encode($r) }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-secondary" style="padding: 0.3rem 0.65rem; font-size: 0.775rem; color: var(--danger-text); border-color: var(--danger-border); background: var(--danger-bg);" onclick="confirmDeleteRoom({{ $r->id }}, '{{ $r->name }}')">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                Belum ada data ruangan yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Ruangan / Instalasi / Kelas -->
    <div id="addRoomModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1.5rem;">
        <div class="card" style="width: 100%; max-width: 520px; padding: 1.5rem; border-radius: 10px; margin: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                <h3 class="section-title" style="margin-bottom: 0;">Tambah Ruangan / Instalasi / Kelas</h3>
                <button type="button" onclick="closeAddRoomModal()" style="background: none; border: none; font-size: 1.35rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form action="{{ route('shri.rooms.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Ruangan:</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Ruang Dahlia 1" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kode Ruangan:</label>
                    <input type="text" name="code" class="form-control" placeholder="Contoh: DHL-01" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Instalasi (Kategori Ruangan):</label>
                    <select name="category_select" id="add_category_select" class="form-select" onchange="toggleCustomCategoryInput()">
                        <option value="">-- Pilih Instalasi Ada --</option>
                        @foreach($existingCategories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Instalasi Baru Baru...</option>
                    </select>
                    <input type="text" name="category_custom" id="add_category_custom" class="form-control" placeholder="Ketik nama Instalasi Baru..." style="margin-top: 0.5rem; display: none;">
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas Perawatan:</label>
                    <select name="room_class_select" id="add_room_class_select" class="form-select" onchange="toggleCustomClassInput()">
                        <option value="">-- Pilih Kelas Ada --</option>
                        @foreach($allClasses as $cls)
                            <option value="{{ $cls }}">{{ $cls }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Kelas Perawatan Baru...</option>
                    </select>
                    <input type="text" name="room_class_custom" id="add_room_class_custom" class="form-control" placeholder="Ketik nama Kelas Baru..." style="margin-top: 0.5rem; display: none;">
                </div>

                <div class="form-group">
                    <label class="form-label">Kapasitas Tempat Tidur (TT):</label>
                    <input type="number" name="capacity" class="form-control" value="10" min="1" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="add_is_active" value="1" checked>
                    <label for="add_is_active" style="font-weight: 600; font-size: 0.85rem; cursor: pointer;">Ruangan Aktif</label>
                </div>

                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddRoomModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Ruangan -->
    <div id="editRoomModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto; padding: 1.5rem;">
        <div class="card" style="width: 100%; max-width: 520px; padding: 1.5rem; border-radius: 10px; margin: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-light); padding-bottom: 0.75rem;">
                <h3 class="section-title" style="margin-bottom: 0;">Edit Data Ruangan</h3>
                <button type="button" onclick="closeEditRoomModal()" style="background: none; border: none; font-size: 1.35rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form id="editRoomForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Ruangan:</label>
                    <input type="text" name="name" id="edit_room_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kode Ruangan:</label>
                    <input type="text" name="code" id="edit_room_code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Instalasi (Kategori Ruangan):</label>
                    <input type="text" name="category" id="edit_room_category" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas Perawatan:</label>
                    <input type="text" name="room_class" id="edit_room_class" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kapasitas Tempat Tidur (TT):</label>
                    <input type="number" name="capacity" id="edit_room_capacity" class="form-control" min="1" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="edit_room_is_active" value="1">
                    <label for="edit_room_is_active" style="font-weight: 600; font-size: 0.85rem; cursor: pointer;">Ruangan Aktif</label>
                </div>

                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditRoomModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Hidden Hapus Ruangan -->
    <form id="deleteRoomForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
<script>
    function openAddRoomModal() {
        document.getElementById('addRoomModal').style.display = 'flex';
    }

    function closeAddRoomModal() {
        document.getElementById('addRoomModal').style.display = 'none';
    }

    function toggleCustomCategoryInput() {
        var sel = document.getElementById('add_category_select').value;
        var inp = document.getElementById('add_category_custom');
        if (sel === '__NEW__') {
            inp.style.display = 'block';
            inp.focus();
        } else {
            inp.style.display = 'none';
            inp.value = '';
        }
    }

    function toggleCustomClassInput() {
        var sel = document.getElementById('add_room_class_select').value;
        var inp = document.getElementById('add_room_class_custom');
        if (sel === '__NEW__') {
            inp.style.display = 'block';
            inp.focus();
        } else {
            inp.style.display = 'none';
            inp.value = '';
        }
    }

    function openEditRoomModal(room) {
        document.getElementById('editRoomForm').action = '/shri/rooms/' + room.id;
        document.getElementById('edit_room_name').value = room.name;
        document.getElementById('edit_room_code').value = room.code;
        document.getElementById('edit_room_category').value = room.category;
        document.getElementById('edit_room_class').value = room.room_class;
        document.getElementById('edit_room_capacity').value = room.capacity;
        document.getElementById('edit_room_is_active').checked = (room.is_active == 1);
        document.getElementById('editRoomModal').style.display = 'flex';
    }

    function closeEditRoomModal() {
        document.getElementById('editRoomModal').style.display = 'none';
    }

    function confirmDeleteRoom(id, name) {
        if (confirm('Apakah Anda yakin ingin menghapus ruangan "' + name + '"?')) {
            var form = document.getElementById('deleteRoomForm');
            form.action = '/shri/rooms/' + id;
            form.submit();
        }
    }
</script>
@endpush
