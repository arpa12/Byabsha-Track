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

        /* Submenu Styles */
        .nav-item-submenu {
            position: relative;
        }

        .nav-link-parent {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            cursor: pointer;
            justify-content: space-between;
        }

        .nav-link-parent:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-link-parent.active,
        .nav-link-parent[aria-expanded="true"] {
            background: rgba(37,99,235,0.1);
            color: white;
            border-left-color: var(--primary-color);
        }

        .nav-link-parent .left-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link-parent i.bi-chevron-down {
            font-size: 0.875rem;
            transition: transform 0.2s;
        }

        .nav-link-parent[aria-expanded="true"] i.bi-chevron-down {
            transform: rotate(180deg);
        }

        .submenu {
            background: rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .submenu .nav-link-custom {
            padding-left: 3.5rem;
            font-size: 0.9rem;
        }

        .submenu .nav-link-custom i {
            font-size: 1rem;
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

        /* Notification Bell */
        .notification-bell {
            position: relative;
            margin-right: 1rem;
        }

        .notification-bell .btn {
            position: relative;
            background: transparent;
            border: none;
            font-size: 1.25rem;
            color: #64748b;
            padding: 0.5rem;
            cursor: pointer;
        }

        .notification-bell .btn:hover {
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 380px;
            max-height: 500px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-dropdown-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-dropdown-header h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .notification-dropdown-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 0.75rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-item.unread {
            background: #eff6ff;
        }

        .notification-item.unread:hover {
            background: #dbeafe;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .notification-dropdown-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .notification-dropdown-footer a {
            font-size: 0.875rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .notification-dropdown-footer a:hover {
            text-decoration: underline;
        }

        .notification-empty {
            padding: 3rem 1.25rem;
            text-align: center;
            color: #94a3b8;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
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

            <!-- Notification Bell -->
            <div class="notification-bell">
                <button class="btn" id="notificationBell" type="button">
                    <i class="bi bi-bell-fill"></i>
                    @if(auth()->user()->unreadNotificationsCount() > 0)
                        <span class="notification-badge">{{ auth()->user()->unreadNotificationsCount() }}</span>
                    @endif
                </button>

                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-header">
                        <h6>{{ __('notifications.title') }}</h6>
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-primary p-0" style="font-size: 0.8rem;">
                                {{ __('notifications.mark_all_read') }}
                            </button>
                        </form>
                    </div>
                    <div class="notification-dropdown-body" id="notificationList">
                        @php
                            $recentNotifications = auth()->user()->notifications()->latest()->limit(5)->get();
                        @endphp

                        @forelse($recentNotifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}"
                               class="notification-item {{ $notification->isUnread() ? 'unread' : '' }}">
                                <i class="{{ $notification->icon }} notification-icon"></i>
                                <div class="notification-content">
                                    <div class="notification-title">{{ $notification->title }}</div>
                                    <div class="notification-message">{{ $notification->message }}</div>
                                    <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p class="mb-0">{{ __('notifications.no_notifications') }}</p>
                            </div>
                        @endforelse
                    </div>
                    @if($recentNotifications->count() > 0)
                        <div class="notification-dropdown-footer">
                            <a href="{{ route('notifications.index') }}">{{ __('notifications.view_all') }}</a>
                        </div>
                    @endif
                </div>
            </div>

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
                <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-person-gear"></i> {{ __('user.profile_title') }}
                </a>
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
            <a href="{{ route('user.profile.edit') }}" class="nav-link-custom {{ request()->is('profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>{{ __('user.profile_title') }}</span>
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
            <a href="{{ route('stock.index') }}" class="nav-link-custom {{ request()->is('stocks*') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i>
                <span>{{ __('app.stocks') }}</span>
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

            @if(auth()->user()->isSuperAdmin())
            <div class="nav-section-title">{{ __('app.system') }}</div>
            <a href="{{ route('user.index') }}" class="nav-link-custom {{ request()->is('users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>{{ __('app.users') }}</span>
            </a>

            <!-- Settings Submenu -->
            <div class="nav-item-submenu">
                <a class="nav-link-parent {{ request()->is('settings*') ? 'active' : '' }}"
                   data-bs-toggle="collapse"
                   href="#settingsSubmenu"
                   role="button"
                   aria-expanded="{{ request()->is('settings*') ? 'true' : 'false' }}"
                   aria-controls="settingsSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear"></i>
                        <span>{{ __('app.settings') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu {{ request()->is('settings*') ? 'show' : '' }}" id="settingsSubmenu">
                    <a href="{{ route('settings.index') }}?tab=general" class="nav-link-custom {{ request()->is('settings*') && (request()->get('tab', 'general') === 'general') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>{{ __('settings.general_settings') }}</span>
                    </a>
                    <a href="{{ route('settings.index') }}?tab=business" class="nav-link-custom {{ request()->is('settings*') && request()->get('tab') === 'business' ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i>
                        <span>{{ __('settings.business_settings') }}</span>
                    </a>
                    <a href="{{ route('settings.index') }}?tab=system" class="nav-link-custom {{ request()->is('settings*') && request()->get('tab') === 'system' ? 'active' : '' }}">
                        <i class="bi bi-cpu"></i>
                        <span>{{ __('settings.system_settings') }}</span>
                    </a>
                </div>
            </div>
            @endif

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

        // Notification bell dropdown
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBell && notificationDropdown) {
            notificationBell.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
