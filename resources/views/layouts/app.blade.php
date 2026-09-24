<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SHRI RSUD Dr. Saiful Anwar Malang')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logo-rssa.jpg') }}" type="image/jpeg">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        // Apply saved sidebar state immediately before render to avoid flash
        (function() {
            if (localStorage.getItem('shri_sidebar_collapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    <style>
        :root {
            --bg-app: #fcfcfd;
            --bg-surface: #ffffff;
            --border-color: #cbd5e1;
            --border-light: #e2e8f0;
            --primary: #ebd06c;
            --primary-dark: #b89628;
            --primary-light: #fefce8;
            --secondary: #475569;
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
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 68px;
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
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Navigation */
        aside {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand {
            padding: 1rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-light);
            height: 64px;
        }

        .brand-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            overflow: hidden;
        }

        .brand-logo-img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .brand-text {
            white-space: nowrap;
            transition: opacity 0.15s ease;
        }

        .brand-text h1 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 0.725rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .sidebar-toggle-btn {
            background: transparent;
            border: 1px solid var(--border-light);
            color: var(--text-muted);
            width: 30px;
            height: 30px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover {
            background: var(--bg-app);
            color: var(--text-dark);
            border-color: var(--border-color);
        }

        .nav-menu {
            list-style: none;
            padding: 1rem 0.65rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-item a:hover {
            color: var(--primary-dark);
            background: var(--bg-app);
        }

        .nav-item.active a {
            color: var(--primary-dark);
            background: var(--primary-light);
            font-weight: 700;
        }

        .nav-item a i {
            width: 20px;
            font-size: 0.95rem;
            text-align: center;
            flex-shrink: 0;
        }

        /* Collapsed Sidebar Styles */
        html.sidebar-collapsed aside {
            width: var(--sidebar-collapsed-width);
        }

        html.sidebar-collapsed .brand-text,
        html.sidebar-collapsed .nav-text {
            display: none;
        }

        html.sidebar-collapsed .brand {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        html.sidebar-collapsed .brand-content {
            display: none;
        }

        html.sidebar-collapsed main {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Main Container */
        main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 1.5rem 2rem;
            max-width: 1400px;
            transition: margin-left 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Header Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            background: #ffffff;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }

        .page-title h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .page-title p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .user-header-area {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--bg-app);
            padding: 0.4rem 0.85rem;
            border-radius: 6px;
            border: 1px solid var(--border-light);
        }

        .header-logo {
            width: 34px;
            height: 34px;
            object-fit: contain;
            border-radius: 4px;
        }

        /* Content Card */
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
        }

        /* Section Title */
        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .alert-info {
            background-color: var(--primary-light);
            border: 1px solid #fde047;
            color: #854d0e;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
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
            color: #0f172a;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: #d4b854;
            color: #0f172a;
        }

        .btn-success {
            background: #16a34a;
            color: #ffffff;
        }

        .btn-success:hover {
            background: #15803d;
        }

        html {
            scroll-behavior: smooth;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
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
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(235, 208, 108, 0.25);
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border: 1px solid var(--border-light);
            border-radius: 6px;
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
            font-weight: 700;
            font-size: 0.75rem;
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
            line-height: 1.2;
        }

        .badge-success { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
        .badge-warning { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }
        .badge-danger { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
        .badge-info { background: #fefce8; color: #854d0e; border: 1px solid #fde047; }

        @media (max-width: 992px) {
            aside { width: var(--sidebar-collapsed-width); }
            .brand-text, .nav-text { display: none; }
            main { margin-left: var(--sidebar-collapsed-width); padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $authUser = Auth::user();
        $isSuper = $authUser ? $authUser->isSuperAdmin() : false;
        $rolePrefix = $isSuper ? 'shri.' : 'admin_ruang.';
    @endphp

    <!-- Sidebar -->
    <aside>
        <div class="brand">
            <div class="brand-content">
                <img src="{{ asset('logo-rssa.jpg') }}" alt="Logo RSSA" class="brand-logo-img">
                <div class="brand-text">
                    <h1>SHRI RSSA</h1>
                    <p>RSUD Dr. Saiful Anwar</p>
                </div>
            </div>
            <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Buka/Tutup Sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('*dashboard') ? 'active' : '' }}">
                <a href="{{ route($rolePrefix . 'dashboard') }}" title="Dashboard Utama">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span class="nav-text">Dashboard Utama</span>
                </a>
            </li>
            <li class="nav-item {{ (request()->routeIs('*sensus.*') || request()->routeIs('*census.*')) ? 'active' : '' }}">
                <a href="{{ route($rolePrefix . 'sensus.index') }}" title="Sensus Harian">
                    <i class="fa-solid fa-hospital-user"></i>
                    <span class="nav-text">Sensus Harian</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('*transfers.*') ? 'active' : '' }}">
                <a href="{{ route($rolePrefix . 'transfers.index') }}" title="Mutasi Pindahan">
                    <i class="fa-solid fa-right-left"></i>
                    <span class="nav-text">Mutasi Pindahan</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('*guide.*') ? 'active' : '' }}">
                <a href="{{ route($rolePrefix . 'guide.index') }}" title="Buku Panduan">
                    <i class="fa-solid fa-book-open"></i>
                    <span class="nav-text">Buku Panduan</span>
                </a>
            </li>

            @if($isSuper)
            <li class="nav-item {{ request()->routeIs('*users.*') ? 'active' : '' }}">
                <a href="{{ route('shri.users.index') }}" title="Kelola Akun">
                    <i class="fa-solid fa-users-gear"></i>
                    <span class="nav-text">Kelola Akun</span>
                </a>
            </li>
            @endif

            <li class="nav-item {{ request()->routeIs('*profile.*') ? 'active' : '' }}">
                <a href="{{ route($rolePrefix . 'profile.index') }}" title="Profil Saya">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="nav-text">Profil Saya</span>
                </a>
            </li>
        </ul>

        <div style="padding: 1rem 0.65rem; border-top: 1px solid var(--border-light);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: flex-start; color: var(--danger-text); border-color: var(--danger-border); background: var(--danger-bg);">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="nav-text">Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main>
        <div class="top-bar">
            <div class="page-title">
                <h2>@yield('header_title', 'Dashboard')</h2>
                <p>@yield('header_subtitle', 'Sensus Harian Rawat Inap RSUD Dr. Saiful Anwar Malang')</p>
            </div>
            
            <div class="user-header-area">
                <div class="user-pill">
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark);">
                            {{ $authUser ? $authUser->name : 'Petugas' }}
                        </div>
                        <div style="font-size: 0.725rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.35rem;">
                            @if($isSuper)
                                <span class="badge badge-info" style="font-size: 0.65rem;">Admin (Petugas SHRI)</span>
                            @else
                                <span class="badge badge-success" style="font-size: 0.65rem;">Admin {{ $authUser && $authUser->room ? $authUser->room->name : 'Ruangan' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="display: block;">
                <ul style="margin-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            var isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('shri_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        }
    </script>
    @stack('scripts')
</body>
</html>


