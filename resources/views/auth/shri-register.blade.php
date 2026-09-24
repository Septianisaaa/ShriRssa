<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petugas SHRI Baru - RSUD Dr. Saiful Anwar Malang</title>
    <link rel="icon" href="{{ asset('logo-rssa.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-app: #fcfcfd;
            --primary: #ebd06c;
            --primary-dark: #b89628;
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
            padding: 2.25rem 2rem;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.75rem;
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
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(235, 208, 108, 0.25);
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
            color: #0f172a;
        }

        .btn-primary:hover {
            background: #d4b854;
            color: #0f172a;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.825rem;
            font-weight: 600;
        }

        .alert-danger { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.825rem;
            color: var(--text-muted);
            padding-top: 1.25rem;
            border-top: 1px solid var(--border-light);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('logo-rssa.jpg') }}" alt="Logo RSSA" class="auth-logo">
            <h1>Daftar Petugas SHRI Baru</h1>
            <p>Registrasi Akun Superadmin Rekam Medis / SHRI</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('shri.register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap Petugas:</label>
                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
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
                    <div style="position: relative;">
                        <input type="password" name="password" id="reg_password" class="form-control" style="padding-right: 2.5rem;" required>
                        <button type="button" onclick="togglePasswordVisibility('reg_password', this)" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 0.35rem; font-size: 0.9rem;" title="Lihat/Sembunyikan Password">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password:</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="reg_password_confirm" class="form-control" style="padding-right: 2.5rem;" required>
                        <button type="button" onclick="togglePasswordVisibility('reg_password_confirm', this)" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 0.35rem; font-size: 0.9rem;" title="Lihat/Sembunyikan Password">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Daftar Akun Petugas SHRI
            </button>
        </form>

        <div class="auth-footer">
            Sudah memiliki akun Petugas SHRI? <a href="{{ route('shri.login') }}">Login di Sini</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, btnEl) {
            var field = document.getElementById(fieldId);
            if (!field) return;
            var icon = btnEl.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
