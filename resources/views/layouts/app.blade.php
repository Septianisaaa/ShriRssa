<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SHRI RSUD Dr. Saiful Anwar Malang')</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-app: #f1f5f9;
            --bg-surface: #ffffff;
            --border-color: #cbd5e1;
            --border-light: #e2e8f0;
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #e0f2fe;
            --secondary: #475569;
            --accent: #0d9488;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --warning-text: #92400e;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --success-text: #166534;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --danger-text: #991b1b;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-app);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Sidebar Navigation */
        aside {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand {
            padding: 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-light);
            background: #ffffff;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #ffffff;
        }

        .brand-text h1 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .nav-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-item a:hover {
            color: var(--primary-dark);
            background: var(--bg-app);
        }

        .nav-item.active a {
            color: var(--primary-dark);
            background: var(--primary-light);
            font-weight: 600;
        }

        .nav-item a i {
            width: 20px;
            font-size: 1rem;
        }

        /* Main Container */
        main {
            margin-left: 260px;
            flex: 1;
            padding: 1.75rem 2rem;
            max-width: 1350px;
        }

        /* Header Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            background: #ffffff;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .page-title h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .page-title p {
            font-size: 0.825rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--bg-app);
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: var(--primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Content Card */
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1.25rem;
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .alert-info {
            background-color: var(--primary-light);
            border: 1px solid #bae6fd;
            color: var(--primary-dark);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            text-decoration: none;
            white-space: nowrap;
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

        .btn-secondary {
            background: #ffffff;
            color: var(--text-dark);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-app);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.55rem 0.85rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-dark);
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border: 1px solid var(--border-light);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
            background: #ffffff;
        }

        th {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.775rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-light);
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-dark);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.55rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
        }

        .badge-success { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
        .badge-warning { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }
        .badge-danger { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
        .badge-info { background: var(--primary-light); color: var(--primary-dark); border: 1px solid #bae6fd; }

        @media (max-width: 992px) {
            aside { width: 70px; }
            .brand-text, .nav-item span { display: none; }
            main { margin-left: 70px; padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside>
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-notes-medical"></i>
            </div>
            <div class="brand-text">
                <h1>SHRI RSSA</h1>
                <p>Sensus Rawat Inap</p>
            </div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-house-medical"></i>
                    <span>Dashboard Utama</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('census.*') ? 'active' : '' }}">
                <a href="{{ route('census.index') }}">
                    <i class="fa-solid fa-bed"></i>
                    <span>Sensus Harian</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('transfers.*') ? 'active' : '' }}">
                <a href="{{ route('transfers.index') }}">
                    <i class="fa-solid fa-right-left"></i>
                    <span>Mutasi Pindahan</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('guide.*') ? 'active' : '' }}">
                <a href="{{ route('guide.index') }}">
                    <i class="fa-solid fa-book"></i>
                    <span>Bukpa (Buku Panduan)</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main>
        <div class="top-bar">
            <div class="page-title">
                <h2>@yield('header_title', 'Dashboard')</h2>
                <p>@yield('header_subtitle', 'Sensus Harian Rawat Inap RSUD Dr. Saiful Anwar Malang')</p>
            </div>
            <div class="user-pill">
                <div class="user-avatar"><i class="fa-solid fa-user-nurse"></i></div>
                <div>
                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark);">Petugas Perawat / Rekam Medis</div>
                    <div style="font-size: 0.725rem; color: var(--text-muted);">RSUD Dr. Saiful Anwar</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
