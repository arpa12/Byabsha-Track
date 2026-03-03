<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Byabsha Track') - Business Tracking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
            margin-right: 2rem;
        }

        .header-brand i {
            font-size: 1.5rem;
        }

        .header-brand:hover {
            color: var(--primary-dark);
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-time {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .header-user {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 3px;
            gap: 2px;
        }
        .lang-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            color: #64748b;
            transition: background .2s, color .2s, box-shadow .2s;
            white-space: nowrap;
            letter-spacing: .01em;
        }
        .lang-btn:hover:not(.active) {
            background: #e2e8f0;
            color: #1e293b;
        }
        .lang-btn.active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 1px 4px rgba(37,99,235,.3);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link-custom i {
            font-size: 1.125rem;
            width: 20px;
        }

        .nav-link-custom:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-link-custom.active {
            background: rgba(37,99,235,0.1);
            color: white;
            border-left-color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            margin-right: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .header-time {
                display: none;
            }
        }

        /* Page Title */
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ url('/') }}" class="header-brand">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Byabsha Track</span>
        </a>
        <div class="header-right">
            <span class="header-time">
                <i class="bi bi-calendar3"></i>
                <span id="currentDate"></span>
            </span>
            <!-- Language Switcher -->
            <div class="lang-switcher me-2">
                <a href="{{ route('language.switch', 'en') }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'bn') }}"
                   class="lang-btn {{ app()->getLocale() === 'bn' ? 'active' : '' }}">বাংলা</a>
            </div>
            <div class="header-user ms-1">
                <i class="bi bi-person-circle me-1"></i>
                <span class="me-2">{{ auth()->user()->name ?? 'User' }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> {{ __('app.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <div class="nav-section-title">{{ __('app.main_menu') }}</div>
            <a href="{{ route('dashboard.index') }}" class="nav-link-custom {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>{{ __('app.dashboard') }}</span>
            </a>

            <div class="nav-section-title">{{ __('app.management') }}</div>
            <a href="{{ route('shop.index') }}" class="nav-link-custom {{ request()->is('shops*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>{{ __('app.shops') }}</span>
            </a>
            <a href="{{ route('product.index') }}" class="nav-link-custom {{ request()->is('products*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>{{ __('app.products') }}</span>
            </a>
            <a href="{{ route('sale.index') }}" class="nav-link-custom {{ request()->is('sales*') ? 'active' : '' }}">
                <i class="bi bi-cart-check"></i>
                <span>{{ __('app.sales') }}</span>
            </a>
            <a href="{{ route('capital.index') }}" class="nav-link-custom {{ request()->is('capitals*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i>
                <span>{{ __('app.capitals') }}</span>
            </a>
            <a href="{{ route('restock.index') }}" class="nav-link-custom {{ request()->is('restocks*') ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i>
                <span>{{ __('app.restocks') }}</span>
            </a>

            <div class="nav-section-title">{{ __('app.analytics') }}</div>
            <a href="{{ route('report.index') }}" class="nav-link-custom {{ request()->is('reports') || request()->is('reports/sales*') || request()->is('reports/products*') || request()->is('reports/shops*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>{{ __('app.reports') }}</span>
            </a>
            <a href="{{ route('report.daily') }}" class="nav-link-custom {{ request()->is('reports/daily*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>{{ __('app.daily_pnl') }}</span>
            </a>
            <a href="{{ route('report.monthly') }}" class="nav-link-custom {{ request()->is('reports/monthly*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range"></i>
                <span>{{ __('app.monthly_pnl') }}</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Update current date
        function updateDate() {
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                dateElement.textContent = new Date().toLocaleDateString('en-US', options);
            }
        }
        updateDate();
    </script>
    @stack('scripts')
</body>
</html>
