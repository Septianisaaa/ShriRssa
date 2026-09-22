@extends('layouts.app')

@section('title', 'Kelola Admin Ruangan - SHRI RSSA')
@section('header_title', 'Kelola Account Admin Ruangan')
@section('header_subtitle', 'Pendaftaran & Manajamen Hak Akses Admin Ruangan Perawatan RSUD Dr. Saiful Anwar')

@section('content')

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem;">
        
        <!-- Form Registrasi Admin Ruangan Baru -->
        <div class="card">
            <h3 class="section-title">Registrasi Admin Ruangan Baru</h3>

            <form action="{{ route('shri.users.store_admin_ruang') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Admin:</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Perawat Ani, S.Kep" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control" placeholder="admin_bougenville" value="{{ old('username') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Resmi:</label>
                    <input type="email" name="email" class="form-control" placeholder="ani@rssa.go.id" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Ruangan Penugasan:</label>
                    <select name="room_id" class="form-select" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->name }} ({{ $r->category }} - {{ $r->floor }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP / WhatsApp (Opsional):</label>
                    <input type="text" name="phone" class="form-control" placeholder="08123456789" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Daftarkan Admin Ruangan
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Admin Ruangan & Petugas SHRI -->
        <div class="card">
            <h3 class="section-title">Daftar Admin Ruangan Terdaftar ({{ $adminRuangUsers->count() }})</h3>

            <div class="table-responsive" style="margin-bottom: 1.5rem;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Admin</th>
                            <th>Username / Email</th>
                            <th>Ruangan Penugasan</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminRuangUsers as $admin)
                            <tr>
                                <td><strong>{{ $admin->name }}</strong></td>
                                <td>
                                    <div><code>{{ $admin->username }}</code></div>
                                    <div style="font-size: 0.725rem; color: var(--text-muted);">{{ $admin->email }}</div>
                                </td>
                                <td>
                                    @if($admin->room)
                                        <span class="badge badge-success">{{ $admin->room->name }}</span>
                                    @else
                                        <span class="badge badge-warning">Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td>{{ $admin->phone ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('shri.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ruangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.55rem; font-size: 0.725rem; color: var(--danger-text); border-color: var(--danger-border); background: var(--danger-bg);">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                                    Belum ada akun Admin Ruangan terdaftar. Silakan gunakan form di samping untuk mendaftarkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h3 class="section-title">Daftar Petugas SHRI (Superadmin)</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Petugas</th>
                            <th>Username / Email</th>
                            <th>Role</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($superAdminUsers as $super)
                            <tr>
                                <td><strong>{{ $super->name }}</strong></td>
                                <td>
                                    <div><code>{{ $super->username }}</code></div>
                                    <div style="font-size: 0.725rem; color: var(--text-muted);">{{ $super->email }}</div>
                                </td>
                                <td><span class="badge badge-info">Superadmin</span></td>
                                <td>{{ $super->phone ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection
