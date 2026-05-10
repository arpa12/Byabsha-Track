<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Byabsha Track'); ?> - Business Tracking System</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 68px;
            --bg: #f4f8fb;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --brand: #0f766e;
            --brand-deep: #155e75;
            --line: #d8e4ee;
            --primary-color: var(--brand);
            --primary-dark: var(--brand-deep);
            --sidebar-bg: linear-gradient(180deg, #0f766e 0%, #0b5f58 52%, #0a4f4a 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.14);
            --text-muted: var(--ink-500);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background:
                radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.15), transparent 60%),
                radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
                linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
            color: var(--ink-900);
            overflow-x: hidden;
        }

        .display-font {
            font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
            letter-spacing: -0.03em;
        }

        /* Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: rgba(250, 252, 254, 0.9);
            border-bottom: 1px solid rgba(100, 116, 139, 0.17);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.14rem;
            font-weight: 800;
            color: var(--ink-900);
            text-decoration: none;
            margin-right: 2rem;
        }

        .brand-chip {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--brand), var(--brand-deep));
            color: #fff;
            box-shadow: 0 10px 26px rgba(15, 118, 110, 0.34);
        }

        .header-brand:hover {
            color: var(--ink-900);
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-time {
            color: var(--ink-700);
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.42rem 0.8rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.09);
            border: 1px solid rgba(15, 118, 110, 0.18);
        }

        .header-user {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--ink-700);
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0.35rem 0.4rem 0.35rem 0.7rem;
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            background: #e6eef5;
            border: 1px solid #d2deea;
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
            font-weight: 700;
            text-decoration: none;
            color: #55657a;
            transition: background .2s, color .2s, box-shadow .2s;
            white-space: nowrap;
            letter-spacing: .01em;
        }
        .lang-btn:hover:not(.active) {
            background: #d8e5f1;
            color: #0f172a;
        }
        .lang-btn.active {
            background: #0f766e;
            color: #fff;
            box-shadow: 0 1px 4px rgba(15, 118, 110, .3);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 10px;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height) - 10px);
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
            border: 1px solid rgba(8, 82, 76, 0.75);
            border-radius: 18px;
            box-shadow: 0 20px 35px rgba(6, 54, 50, 0.28);
            backdrop-filter: blur(8px);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.34);
            border-radius: 3px;
        }

        .sidebar-nav {
            padding: 1.2rem 0;
        }

        .nav-section-title {
            color: rgba(214, 245, 237, 0.82);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
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
            padding: 0.66rem 1rem;
            margin: 0.2rem 0.75rem;
            border-radius: 12px;
            color: rgba(240, 255, 251, 0.9);
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .nav-link-custom i {
            font-size: 1.125rem;
            width: 20px;
        }

        .nav-link-custom:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link-custom.active {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow: inset 3px 0 0 #ffffff;
        }

        /* Submenu Styles */
        .nav-item-submenu {
            position: relative;
        }

        .nav-link-parent {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.66rem 1rem;
            margin: 0.2rem 0.75rem;
            border-radius: 12px;
            color: rgba(240, 255, 251, 0.9);
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
            justify-content: space-between;
        }

        .nav-link-parent:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link-parent.active,
        .nav-link-parent[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow: inset 3px 0 0 #ffffff;
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
            background: rgba(7, 67, 62, 0.38);
            overflow: hidden;
            border-radius: 12px;
            margin: 0.1rem 0.75rem;
        }

        .submenu .nav-link-custom {
            margin: 0.16rem 0.4rem;
            padding-left: 2.4rem;
            font-size: 0.9rem;
        }

        .submenu .nav-link-custom i {
            font-size: 1rem;
        }

        .submenu-group-title {
            font-size: 0.75rem;
            color: rgba(240,255,251,0.6);
            padding: 0.5rem 1.1rem;
            margin-top: 0.6rem;
            margin-bottom: 0.1rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: calc(var(--sidebar-width) + 10px);
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
            color: #41556a;
            cursor: pointer;
            margin-right: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                left: 0;
                height: calc(100vh - var(--header-height));
                border-radius: 0 18px 18px 0;
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
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid var(--line);
            font-size: 1.25rem;
            color: #49627a;
            padding: 0.42rem 0.58rem;
            cursor: pointer;
            border-radius: 999px;
        }

        .notification-bell .btn:hover {
            color: var(--primary-color);
            background: #ffffff;
            border-color: #bfd3e4;
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
            background: #fdfefe;
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.14);
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-dropdown-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #dde7f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 118, 110, 0.06);
        }

        .notification-dropdown-header h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #102438;
        }

        .notification-dropdown-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #edf3f8;
            display: flex;
            gap: 0.75rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            background: #f1f7fb;
        }

        .notification-item.unread {
            background: #e8f4fb;
        }

        .notification-item.unread:hover {
            background: #dcedf8;
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
            color: #102438;
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
            color: #70879d;
        }

        .notification-dropdown-footer {
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #dde7f0;
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
            color: #70879d;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Page Title */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #102438;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .btn-outline-primary {
            --bs-btn-color: #0f766e;
            --bs-btn-border-color: #0f766e;
            --bs-btn-hover-bg: #0f766e;
            --bs-btn-hover-border-color: #0f766e;
            --bs-btn-active-bg: #155e75;
            --bs-btn-active-border-color: #155e75;
            --bs-btn-disabled-color: #0f766e;
            --bs-btn-disabled-border-color: #0f766e;
        }

        .btn-outline-danger {
            --bs-btn-color: #b91c1c;
            --bs-btn-border-color: #f0b3b3;
            --bs-btn-hover-bg: #b91c1c;
            --bs-btn-hover-border-color: #b91c1c;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <a href="<?php echo e(url('/')); ?>" class="header-brand">
            <span class="brand-chip">
                <i class="bi bi-graph-up-arrow"></i>
            </span>
            <span class="display-font">Byabsha Track</span>
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
                    <?php if(auth()->user()->unreadNotificationsCount() > 0): ?>
                        <span class="notification-badge"><?php echo e(auth()->user()->unreadNotificationsCount()); ?></span>
                    <?php endif; ?>
                </button>

                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-header">
                        <h6><?php echo e(__('notifications.title')); ?></h6>
                        <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-link text-primary p-0" style="font-size: 0.8rem;">
                                <?php echo e(__('notifications.mark_all_read')); ?>

                            </button>
                        </form>
                    </div>
                    <div class="notification-dropdown-body" id="notificationList">
                        <?php
                            $recentNotifications = auth()->user()->notifications()->latest()->limit(5)->get();
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e($notification->data['url'] ?? '#'); ?>"
                               class="notification-item <?php echo e($notification->isUnread() ? 'unread' : ''); ?>">
                                <i class="<?php echo e($notification->icon); ?> notification-icon"></i>
                                <div class="notification-content">
                                    <div class="notification-title"><?php echo e($notification->title); ?></div>
                                    <div class="notification-message"><?php echo e($notification->message); ?></div>
                                    <div class="notification-time"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p class="mb-0"><?php echo e(__('notifications.no_notifications')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if($recentNotifications->count() > 0): ?>
                        <div class="notification-dropdown-footer">
                            <a href="<?php echo e(route('notifications.index')); ?>"><?php echo e(__('notifications.view_all')); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Language Switcher -->
            <div class="lang-switcher me-2">
                <a href="<?php echo e(route('language.switch', 'en')); ?>"
                   class="lang-btn <?php echo e(app()->getLocale() === 'en' ? 'active' : ''); ?>">EN</a>
                <a href="<?php echo e(route('language.switch', 'bn')); ?>"
                   class="lang-btn <?php echo e(app()->getLocale() === 'bn' ? 'active' : ''); ?>">বাংলা</a>
            </div>
            <div class="header-user ms-1">
                <i class="bi bi-person-circle me-1"></i>
                <span class="me-2"><?php echo e(auth()->user()->name ?? 'User'); ?></span>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-person-gear"></i> <?php echo e(__('user.profile_title')); ?>

                </a>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> <?php echo e(__('app.logout')); ?>

                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <?php $sidebarUser = auth()->user(); ?>
            <div class="nav-section-title"><?php echo e(__('app.main_menu')); ?></div>
            <?php if($sidebarUser->hasModuleAccess('dashboard')): ?>
                <a href="<?php echo e(route('dashboard.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('dashboard.*') ? 'active' : ''); ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span><?php echo e(__('app.dashboard')); ?></span>
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('user.profile.edit')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('user.profile.*') ? 'active' : ''); ?>">
                <i class="bi bi-person-gear"></i>
                <span><?php echo e(__('user.profile_title')); ?></span>
            </a>
            <?php if(!$sidebarUser->isSuperAdmin()): ?>
            <a href="<?php echo e(route('subscription.plans')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('subscription.*') ? 'active' : ''); ?>">
                <i class="bi bi-stars"></i>
                <span><?php echo e(__('app.subscription')); ?></span>
                <?php
                    $activeSub = $sidebarUser->activeSubscription();
                    $subPlan = $activeSub?->plan;
                ?>
                <?php if($subPlan && !$subPlan->isFree()): ?>
                    <span class="ms-auto badge" style="background: rgba(255,255,255,0.22); font-size: 0.65rem; border-radius: 999px; letter-spacing: 0.03em;"><?php echo e($subPlan->name); ?></span>
                <?php endif; ?>
            </a>
            <?php endif; ?>

            
            <?php
                $isSetupOpen = request()->routeIs('shop.*') || request()->routeIs('branch.*') || request()->routeIs('brand.*') || request()->routeIs('category.*');
            ?>
            <div class="nav-item-submenu">
                <a class="nav-link-parent <?php echo e($isSetupOpen ? 'active' : ''); ?>"
                   data-bs-toggle="collapse"
                   href="#setupSubmenu"
                   role="button"
                   aria-expanded="<?php echo e($isSetupOpen ? 'true' : 'false'); ?>"
                   aria-controls="setupSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear-wide-connected"></i>
                        <span><?php echo e(__('app.setup')); ?></span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu <?php echo e($isSetupOpen ? 'show' : ''); ?>" id="setupSubmenu">
                    <?php if($sidebarUser->hasModuleAccess('shop')): ?>
                        <a href="<?php echo e(route('shop.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('shop.*') ? 'active' : ''); ?>">
                            <i class="bi bi-shop"></i>
                            <span><?php echo e(__('app.shops')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('branch')): ?>
                        <a href="<?php echo e(route('branch.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('branch.*') ? 'active' : ''); ?>">
                            <i class="bi bi-diagram-3"></i>
                            <span><?php echo e(__('app.branches')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('brand')): ?>
                        <a href="<?php echo e(route('brand.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('brand.*') ? 'active' : ''); ?>">
                            <i class="bi bi-bookmark-star"></i>
                            <span><?php echo e(__('app.brands')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('category')): ?>
                        <a href="<?php echo e(route('category.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('category.*') ? 'active' : ''); ?>">
                            <i class="bi bi-tags"></i>
                            <span><?php echo e(__('app.categories')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php
                $isInventoryOpen = request()->routeIs('product.*') || request()->routeIs('product.dynamic-fields.*') || request()->routeIs('stock.*');
            ?>
            <div class="nav-item-submenu">
                <a class="nav-link-parent <?php echo e($isInventoryOpen ? 'active' : ''); ?>"
                   data-bs-toggle="collapse"
                   href="#inventorySubmenu"
                   role="button"
                   aria-expanded="<?php echo e($isInventoryOpen ? 'true' : 'false'); ?>"
                   aria-controls="inventorySubmenu">
                    <div class="left-content">
                        <i class="bi bi-boxes"></i>
                        <span><?php echo e(__('app.inventory')); ?></span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu <?php echo e($isInventoryOpen ? 'show' : ''); ?>" id="inventorySubmenu">
                    <?php if($sidebarUser->hasModuleAccess('product')): ?>
                        <a href="<?php echo e(route('product.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('product.*') && !request()->routeIs('product.dynamic-fields.*') ? 'active' : ''); ?>">
                            <i class="bi bi-box-seam"></i>
                            <span><?php echo e(__('app.products')); ?></span>
                        </a>
                        <?php if($sidebarUser->canUseProductAttributes()): ?>
                            <a href="<?php echo e(route('product.dynamic-fields.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('product.dynamic-fields.*') ? 'active' : ''); ?>">
                                <i class="bi bi-sliders"></i>
                                <span><?php echo e(__('app.product_attributes')); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('stock') && $sidebarUser->canUseStocks()): ?>
                        <a href="<?php echo e(route('stock.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('stock.*') ? 'active' : ''); ?>">
                            <i class="bi bi-boxes"></i>
                            <span><?php echo e(__('app.stocks')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php
                $isOperationsOpen = request()->routeIs('sale.*') || request()->routeIs('capital.*') || request()->routeIs('restock.*') || request()->routeIs('damage.*');
            ?>
            <div class="nav-item-submenu">
                <a class="nav-link-parent <?php echo e($isOperationsOpen ? 'active' : ''); ?>"
                   data-bs-toggle="collapse"
                   href="#operationsSubmenu"
                   role="button"
                   aria-expanded="<?php echo e($isOperationsOpen ? 'true' : 'false'); ?>"
                   aria-controls="operationsSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear-fill"></i>
                        <span><?php echo e(__('app.operations')); ?></span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu <?php echo e($isOperationsOpen ? 'show' : ''); ?>" id="operationsSubmenu">
                    <?php if($sidebarUser->hasModuleAccess('sale')): ?>
                        <a href="<?php echo e(route('sale.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('sale.*') ? 'active' : ''); ?>">
                            <i class="bi bi-cart-check"></i>
                            <span><?php echo e(__('app.sales')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('capital') && $sidebarUser->canUseCapital()): ?>
                        <a href="<?php echo e(route('capital.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('capital.*') ? 'active' : ''); ?>">
                            <i class="bi bi-cash-coin"></i>
                            <span><?php echo e(__('app.capitals')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('restock') && $sidebarUser->canUseRestock()): ?>
                        <a href="<?php echo e(route('restock.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('restock.*') ? 'active' : ''); ?>">
                            <i class="bi bi-arrow-repeat"></i>
                            <span><?php echo e(__('app.restocks')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if($sidebarUser->hasModuleAccess('damage') && $sidebarUser->canUseDamages()): ?>
                        <a href="<?php echo e(route('damage.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('damage.*') ? 'active' : ''); ?>">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span><?php echo e(__('app.damages')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($sidebarUser->isSuperAdmin()): ?>
            <div class="nav-section-title"><?php echo e(__('app.system')); ?></div>
            <?php
                $isUserManagementRoute = request()->routeIs('user.index')
                    || request()->routeIs('user.create')
                    || request()->routeIs('user.store')
                    || request()->routeIs('user.show')
                    || request()->routeIs('user.edit')
                    || request()->routeIs('user.update')
                    || request()->routeIs('user.destroy')
                    || request()->routeIs('user.restore')
                    || request()->routeIs('user.force-delete');
                $isSettingsMenuOpen = request()->routeIs('settings.*') || $isUserManagementRoute || request()->routeIs('admin.subscriptions.*');
            ?>

            <!-- Settings Submenu -->
            <div class="nav-item-submenu">
                <a class="nav-link-parent <?php echo e($isSettingsMenuOpen ? 'active' : ''); ?>"
                   data-bs-toggle="collapse"
                   href="#settingsSubmenu"
                   role="button"
                   aria-expanded="<?php echo e($isSettingsMenuOpen ? 'true' : 'false'); ?>"
                   aria-controls="settingsSubmenu">
                    <div class="left-content">
                        <i class="bi bi-gear"></i>
                        <span><?php echo e(__('app.settings')); ?></span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse submenu <?php echo e($isSettingsMenuOpen ? 'show' : ''); ?>" id="settingsSubmenu">
                    <a href="<?php echo e(route('settings.general')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('settings.general') || request()->routeIs('settings.index') ? 'active' : ''); ?>">
                        <i class="bi bi-sliders"></i>
                        <span><?php echo e(__('settings.general_settings')); ?></span>
                    </a>
                    <a href="<?php echo e(route('settings.system')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('settings.system') ? 'active' : ''); ?>">
                        <i class="bi bi-cpu"></i>
                        <span><?php echo e(__('settings.system_settings')); ?></span>
                    </a>
                    <a href="<?php echo e(route('user.index')); ?>" class="nav-link-custom <?php echo e($isUserManagementRoute ? 'active' : ''); ?>">
                        <i class="bi bi-people"></i>
                        <span><?php echo e(__('app.users')); ?></span>
                    </a>
                    <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('admin.subscriptions.*') ? 'active' : ''); ?>">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span><?php echo e(__('app.subscriptions')); ?></span>
                        <?php $pendingSubCount = \Modules\Subscription\Models\PaymentRequest::where('status','pending')->count(); ?>
                        <?php if($pendingSubCount > 0): ?>
                            <span class="ms-auto badge bg-warning text-dark" style="font-size: 0.65rem; border-radius: 999px;"><?php echo e($pendingSubCount); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if($sidebarUser->hasModuleAccess('report') && $sidebarUser->canUseReports()): ?>
                <div class="nav-section-title"><?php echo e(__('app.analytics')); ?></div>
                <a href="<?php echo e(route('report.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('report.index') || request()->routeIs('report.sales') || request()->routeIs('report.products') || request()->routeIs('report.shops') ? 'active' : ''); ?>">
                    <i class="bi bi-bar-chart-line"></i>
                    <span><?php echo e(__('app.reports')); ?></span>
                </a>
                <a href="<?php echo e(route('report.daily')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('report.daily') || request()->routeIs('report.export.daily-pdf') ? 'active' : ''); ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span><?php echo e(__('app.daily_pnl')); ?></span>
                </a>
                <a href="<?php echo e(route('report.monthly')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('report.monthly') || request()->routeIs('report.export.monthly-pdf') ? 'active' : ''); ?>">
                    <i class="bi bi-calendar-range"></i>
                    <span><?php echo e(__('app.monthly_pnl')); ?></span>
                </a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Arpa\self_project\byabshaTrack\resources\views/layouts/app.blade.php ENDPATH**/ ?>