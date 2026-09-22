<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registrasi - SHRI RSUD Dr. Saiful Anwar Malang</title>
    <link rel="icon" href="{{ asset('logo-rssa.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-app: #f8fafc;
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #f0f9ff;
            --border-light: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-app);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        .auth-header {
            text-align: center;
            padding: 2rem 1.5rem 1.25rem 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid var(--border-light);
        }

        .auth-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 0.85rem;
        }

        .auth-header h1 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .auth-header p {
            font-size: 0.825rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .auth-tabs {
            display: flex;
            background: #f1f5f9;
            border-bottom: 1px solid var(--border-light);
        }

        .tab-btn {
            flex: 1;
            padding: 0.85rem 0.5rem;
            border: none;
            background: transparent;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
            border-bottom: 2px solid transparent;
        }

        .tab-btn.active {
            background: #ffffff;
            color: var(--primary-dark);
            border-bottom-color: var(--primary);
        }

        .auth-body {
            padding: 1.75rem 1.5rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            font-size: 0.875rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: #16a34a;
            color: #ffffff;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.825rem;
            font-weight: 600;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-info {
            background-color: var(--primary-light);
            border: 1px solid #bae6fd;
            color: var(--primary-dark);
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .credential-box {
            margin-top: 1.5rem;
            padding: 0.85rem 1rem;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            font-size: 0.775rem;
            color: var(--text-muted);
        }

        .credential-box strong {
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Auth Header with RSSA Logo -->
        <div class="auth-header">
            <img src="{{ asset('logo-rssa.jpg') }}" alt="Logo RSSA" class="auth-logo">
            <h1>Sensus Harian Rawat Inap (SHRI)</h1>
            <p>RSUD Dr. Saiful Anwar Malang</p>
        </div>

        <!-- Auth Tabs -->
        <div class="auth-tabs">
            <button type="button" class="tab-btn {{ $tab === 'shri_login' ? 'active' : '' }}" onclick="switchTab('shri_login')">
                Petugas SHRI
            </button>
            <button type="button" class="tab-btn {{ $tab === 'admin_login' ? 'active' : '' }}" onclick="switchTab('admin_login')">
                Admin Ruangan
            </button>
            <button type="button" class="tab-btn {{ $tab === 'shri_register' ? 'active' : '' }}" onclick="switchTab('shri_register')">
                Daftar SHRI Baru
            </button>
        </div>

        <div class="auth-body">
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- TAB 1: LOGIN PETUGAS SHRI (SUPERADMIN) -->
            <div id="tab_shri_login" style="display: {{ $tab === 'shri_login' ? 'block' : 'none' }};">
                <form action="{{ route('shri.login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email / Username Petugas SHRI:</label>
                        <input type="text" name="login" class="form-control" placeholder="Contoh: shri atau shri@rssa.go.id" value="{{ old('login', 'shri') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" value="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Masuk Petugas SHRI (Superadmin)
                    </button>
                </form>

                <div class="credential-box">
                    <strong>Demo Credential (Petugas SHRI):</strong><br>
                    Username / Email: <code>shri</code> / <code>shri@rssa.go.id</code><br>
                    Password: <code>password</code>
                </div>
            </div>

            <!-- TAB 2: LOGIN ADMIN RUANGAN (ADMIN) -->
            <div id="tab_admin_login" style="display: {{ $tab === 'admin_login' ? 'block' : 'none' }};">
                <form action="{{ route('admin_ruang.login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email / Username Admin Ruangan:</label>
                        <input type="text" name="login" class="form-control" placeholder="Contoh: adminruang" value="{{ old('login', 'adminruang') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" value="password" required>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Masuk Admin Ruangan
                    </button>
                </form>

                <div class="credential-box">
                    <strong>Demo Credential (Admin Ruang):</strong><br>
                    Username / Email: <code>adminruang</code> / <code>admin.ruang@rssa.go.id</code><br>
                    Password: <code>password</code>
                </div>
            </div>

            <!-- TAB 3: REGISTRASI PETUGAS SHRI (SUPERADMIN) -->
            <div id="tab_shri_register" style="display: {{ $tab === 'shri_register' ? 'block' : 'none' }};">
                <form action="{{ route('shri.register.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Petugas:</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username:</label>
                        <input type="text" name="username" class="form-control" placeholder="username_shri" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Resmi RSSA:</label>
                        <input type="email" name="email" class="form-control" placeholder="petugas@rssa.go.id" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. HP / WhatsApp (Opsional):</label>
                        <input type="text" name="phone" class="form-control" placeholder="08123456789" value="{{ old('phone') }}">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div class="form-group">
                            <label class="form-label">Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password:</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Daftar Akun Petugas SHRI Baru
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.getElementById('tab_shri_login').style.display = (tabName === 'shri_login') ? 'block' : 'none';
            document.getElementById('tab_admin_login').style.display = (tabName === 'admin_login') ? 'block' : 'none';
            document.getElementById('tab_shri_register').style.display = (tabName === 'shri_register') ? 'block' : 'none';

            var buttons = document.querySelectorAll('.tab-btn');
            buttons[0].classList.toggle('active', tabName === 'shri_login');
            buttons[1].classList.toggle('active', tabName === 'admin_login');
            buttons[2].classList.toggle('active', tabName === 'shri_register');
        }
    </script>
</body>
</html>
