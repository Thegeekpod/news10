<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — {{ $globalSettings['site_name'] ?? 'Bharat Samachar' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #c1121f;
            --primary: #c1121f;
            --primary-hover: #a50e1a;
            --bg-body: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', 'Noto Sans Devanagari', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
            color: #fff;
        }

        .brand-icon {
            font-size: 24px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-sub {
            font-size: 11px;
            color: #94a3b8;
            display: block;
        }

        .sidebar-menu {
            list-style: none;
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-header {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            padding: 10px 12px 6px;
            letter-spacing: 0.5px;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
            margin-bottom: 3px;
        }

        .menu-item a:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }

        .menu-item a.active {
            background-color: var(--sidebar-active);
            color: #ffffff;
            font-weight: 600;
        }

        .menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* ── Main Layout ── */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-header {
            background: #ffffff;
            height: 65px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: #475569;
            cursor: pointer;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .view-site-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #f1f5f9;
            color: #475569;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: 0.2s;
        }

        .view-site-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .admin-user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: #c1121f;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .admin-content {
            padding: 30px;
            flex: 1;
        }

        /* ── Common Components ── */
        .card {
            background: var(--card-bg);
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .card-body {
            padding: 22px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #64748b;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-outline {
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
        }

        .btn-outline:hover {
            background: #f8fafc;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12.5px;
            border-radius: 5px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 7px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            background: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.1);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .custom-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
        }

        .custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .custom-table tr:hover td {
            background: #f8fafc;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #0369a1; }

        @media (max-width: 900px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .admin-content {
                padding: 18px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ════ Sidebar ════ -->
<aside class="admin-sidebar" id="adminSidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <span class="brand-icon">🇮🇳</span>
        <div>
            <span class="brand-title">{{ $globalSettings['site_name'] ?? 'Bharat Samachar' }}</span>
            <span class="brand-sub">Admin Control Center</span>
        </div>
    </a>

    <ul class="sidebar-menu">
        <li class="menu-header">Main Menu</li>
        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>

        <li class="menu-header">Content Management</li>
        <li class="menu-item">
            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i> All Posts
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.posts.create') }}">
                <i class="fa-solid fa-square-plus"></i> Add Post
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Categories
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i> Tags
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.tickers.index') }}" class="{{ request()->routeIs('admin.tickers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bolt"></i> Breaking Tickers
            </a>
        </li>

        <li class="menu-header">Monetization</li>
        <li class="menu-item">
            <a href="{{ route('admin.ads.index') }}" class="{{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
                <i class="fa-solid fa-rectangle-ad"></i> Ads Manager
            </a>
        </li>

        <li class="menu-header">Administration</li>
        <li class="menu-item">
            <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-open-text"></i> Subscribers
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i> Settings
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i> Profile
            </a>
        </li>
    </ul>

    <div style="padding: 15px; border-top: 1px solid rgba(255,255,255,0.08);">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" style="width: 100%; background: #dc2626; color: #fff; border: none; padding: 9px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- ════ Main Content ════ -->
<div class="admin-main">
    <!-- Header -->
    <header class="admin-header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
            <h2 style="font-size: 18px; font-weight: 700; color: #0f172a;">@yield('page_title', 'Control Panel')</h2>
        </div>

        <div class="header-right">
            <a href="{{ route('home') }}" target="_blank" class="view-site-btn">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Site
            </a>

            <div class="admin-user-pill">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div>{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 500;">Chief Editor</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="admin-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
                <ul style="margin-left: 15px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', () => {
            adminSidebar.classList.toggle('show');
        });
    }
</script>
@stack('scripts')
</body>
</html>
