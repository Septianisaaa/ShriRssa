@extends('layouts.app')

@section('title', 'Profil Pengguna - SHRI RSSA')
@section('header_title', 'Profil Saya')
@section('header_subtitle', 'Informasi Akun dan Pengaturan Keamanan')

@section('content')

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
        
        <!-- Detail Info Profil -->
        <div class="card">
            <h3 class="section-title">Informasi Akun</h3>

            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-light);">
                <img src="{{ asset('logo-rssa.jpg') }}" alt="Logo RSSA" style="width: 54px; height: 54px; object-fit: contain; border-radius: 8px; border: 1px solid var(--border-light);">
                <div>
                    <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--text-dark);">{{ $user->name }}</h4>
                    <div style="margin-top: 0.25rem;">
                        @if($user->isSuperAdmin())
                            <span class="badge badge-info">Petugas SHRI (Superadmin)</span>
                        @else
                            <span class="badge badge-success">Admin {{ $user->room ? $user->room->name : 'Ruangan' }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route($user->isSuperAdmin() ? 'shri.profile.update' : 'admin_ruang.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap:</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username:</label>
                    <input type="text" class="form-control" value="{{ $user->username ?? '-' }}" readonly style="background: #f1f5f9; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Resmi:</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP / WhatsApp:</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="08123456789">
                </div>

                @if($user->isAdminRuang() && $user->room)
                <div class="form-group">
                    <label class="form-label">Ruangan Penugasan:</label>
                    <input type="text" class="form-control" value="{{ $user->room->name }} ({{ $user->room->room_class }})" readonly style="background: #f1f5f9; cursor: not-allowed;">
                </div>
                @endif

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        <!-- Form Ganti Password -->
        <div class="card">
            <h3 class="section-title">Ubah Password</h3>

            <form action="{{ route($user->isSuperAdmin() ? 'shri.profile.password' : 'admin_ruang.profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Password Saat Ini:</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: center; font-weight: 700;">
                    Perbarui Password
                </button>
            </form>
        </div>

    </div>

@endsection
