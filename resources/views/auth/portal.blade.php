<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sensus Harian Rawat Inap (SHRI) - RSUD Dr. Saiful Anwar</title>
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

        .portal-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            width: 100%;
            max-width: 520px;
            padding: 2.25rem 2rem;
            text-align: center;
        }

        .portal-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .portal-card h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .portal-card p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.35rem;
            margin-bottom: 1.75rem;
        }

        .portal-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .portal-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            background: #ffffff;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        }

        .portal-btn:hover {
            border-color: var(--primary);
            background: #f0f9ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04);
        }

        .portal-btn-content {
            text-align: left;
        }

        .portal-btn-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .portal-btn-desc {
            font-size: 0.775rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .portal-btn-arrow {
            font-size: 1.1rem;
            color: var(--primary);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            font-size: 0.825rem;
            font-weight: 600;
            text-align: left;
        }

        .alert-info { background-color: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; }
    </style>
</head>
<body>

    <div class="portal-card">
        <img src="{{ asset('logo-rssa.jpg') }}" alt="Logo RSSA" class="portal-logo">
        <h1>Sensus Harian Rawat Inap (SHRI)</h1>
        <p>RSUD Dr. Saiful Anwar Malang</p>

        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.85rem; color: var(--text-dark); text-align: left;">
            Pilih Halaman Login Sesuai Hak Akses Anda:
        </div>

        <div class="portal-options">
            <a href="{{ route('shri.login') }}" class="portal-btn" style="border-left: 4px solid var(--primary);">
                <div class="portal-btn-content">
                    <div class="portal-btn-title">Login Petugas SHRI (Superadmin)</div>
                    <div class="portal-btn-desc">Akses penuh pengelolaan sensus, mutasi, & pendaftaran admin ruangan.</div>
                </div>
                <div class="portal-btn-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('admin_ruang.login') }}" class="portal-btn" style="border-left: 4px solid #16a34a;">
                <div class="portal-btn-content">
                    <div class="portal-btn-title">Login Admin Ruangan</div>
                    <div class="portal-btn-desc">Pencatatan mutasi pasien (MRS & KRS) khusus per-ruangan.</div>
                </div>
                <div class="portal-btn-arrow" style="color: #16a34a;"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
        </div>
    </div>

</body>
</html>
