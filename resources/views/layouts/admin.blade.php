<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - RKVIT REALESTATE')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        :root {
            --primary: #d8232a;
            --primary-dark: #b71c1c;
            --primary-light: #fef2f2;
            --secondary: #2c3e50;
            --sidebar-bg: #0f1a26;
            --sidebar-hover: #1a2332;
            --sidebar-submenu: #0a1219;
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --white: #fff;
            --bg-body: #f5f7fa;
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 6px 18px rgba(0, 0, 0, 0.06);
            --radius: 12px;
            --sidebar-width: 270px;
        }

        body {
            background: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            font-size: 0.95rem;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #cbd5e1;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #2c3e50;
            border-radius: 3px;
        }

        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.35);
        }

        .sidebar-brand-text h1 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .sidebar-brand-text span {
            font-size: 0.6rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d8232a, #a01820);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 11px;
            height: 11px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid var(--sidebar-bg);
        }

        .user-info {
            overflow: hidden;
            flex: 1;
        }

        .user-info h4 {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info span {
            font-size: 0.7rem;
            color: #94a3b8;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-badge {
            display: inline-block;
            background: rgba(216, 35, 42, 0.2);
            color: #f87171;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            border-radius: 8px;
            color: #cbd5e1;
            font-size: 0.87rem;
            font-weight: 500;
            margin-bottom: 2px;
            cursor: pointer;
            transition: all 0.18s;
            position: relative;
            user-select: none;
        }

        .menu-item i.menu-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            color: #94a3b8;
            flex-shrink: 0;
            transition: color 0.18s;
        }

        .menu-item .menu-label {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-item:hover i.menu-icon {
            color: var(--primary);
        }

        .menu-item.active {
            background: linear-gradient(90deg, rgba(216, 35, 42, 0.15), rgba(216, 35, 42, 0.02));
            color: #fff;
            font-weight: 600;
        }

        .menu-item.active i.menu-icon {
            color: var(--primary);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            height: 70%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 4px 4px 0;
        }

        .menu-badge {
            background: var(--primary);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .menu-badge.gray {
            background: #334155;
            color: #94a3b8;
        }

        .menu-arrow {
            font-size: 0.7rem;
            color: #64748b;
            transition: transform 0.25s;
        }

        .menu-parent {
            margin-bottom: 2px;
        }

        .menu-parent.open>.menu-item {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-parent.open>.menu-item i.menu-icon {
            color: var(--primary);
        }

        .menu-parent.open>.menu-item .menu-arrow {
            transform: rotate(90deg);
            color: var(--primary);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.32s;
            background: var(--sidebar-submenu);
            border-radius: 8px;
            margin: 4px 0;
        }

        .menu-parent.open .submenu {
            max-height: 700px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px 9px 42px;
            color: #94a3b8;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: 6px;
            margin: 1px 6px;
            transition: all 0.18s;
            position: relative;
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 26px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #475569;
            transition: all 0.18s;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .submenu-item:hover::before {
            background: var(--primary);
            box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.15);
        }

        .submenu-item.active {
            color: #fff;
            font-weight: 600;
            background: rgba(216, 35, 42, 0.1);
        }

        .submenu-item.active::before {
            background: var(--primary);
            width: 7px;
            height: 7px;
            left: 25px;
            box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.2);
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-footer .menu-item {
            width: 100%;
            background: none;
            border: none;
            font-family: inherit;
            text-align: left;
            color: #f87171;
            cursor: pointer;
        }

        .sidebar-footer .menu-item i.menu-icon {
            color: #f87171;
        }

        .sidebar-footer .menu-item:hover {
            background: rgba(248, 113, 113, 0.1);
            color: #fca5a5;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s;
        }

        .topbar {
            background: var(--white);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 500;
            border-bottom: 1px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 6px;
        }

        .page-title {
            font-size: 1.15rem;
            font-weight: 700;
        }

        .page-title span {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-soft);
            cursor: pointer;
            position: relative;
        }

        .topbar-icon:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .topbar-icon .dot {
            position: absolute;
            top: 8px;
            right: 9px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 26, 38, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Shared UI */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: all 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            opacity: 0.08;
        }

        .stat-card.red::after {
            background: var(--primary);
        }

        .stat-card.blue::after {
            background: #3b82f6;
        }

        .stat-card.green::after {
            background: #10b981;
        }

        .stat-card.purple::after {
            background: #8b5cf6;
        }

        .stat-card.orange::after {
            background: #f59e0b;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .stat-card.red .stat-icon {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-card.blue .stat-icon {
            background: #eff6ff;
            color: #3b82f6;
        }

        .stat-card.green .stat-icon {
            background: #ecfdf5;
            color: #10b981;
        }

        .stat-card.purple .stat-icon {
            background: #f5f3ff;
            color: #8b5cf6;
        }

        .stat-card.orange .stat-icon {
            background: #fffbeb;
            color: #f59e0b;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 0.83rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 4px;
        }

        .panel {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .panel-header {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-header h3 {
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-header h3 i {
            color: var(--primary);
        }

        .panel-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .panel-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text-soft);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .btn-solid {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.2);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-solid:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(216, 35, 42, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-success:hover {
            transform: translateY(-1px);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f8fafc;
        }

        table th {
            padding: 13px 20px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            white-space: nowrap;
        }

        table td {
            padding: 15px 20px;
            border-top: 1px solid var(--border);
            font-size: 0.88rem;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-success {
            background: #ecfdf5;
            color: #059669;
        }

        .badge-warning {
            background: #fffbeb;
            color: #d97706;
        }

        .badge-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-info {
            background: #eff6ff;
            color: #2563eb;
        }

        .badge-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .badge-gray {
            background: #f1f5f9;
            color: #64748b;
        }

        .action-btns {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.18s;
            text-decoration: none;
        }

        .action-btn:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .action-btn.edit:hover {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .action-btn.view:hover {
            background: #10b981;
            border-color: #10b981;
        }

        .action-btn.delete:hover {
            background: var(--primary);
            border-color: var(--primary);
        }

        .flash-alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flash-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .flash-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media (max-width:1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }
        }

        @media (max-width:640px) {
            .content-area {
                padding: 18px;
            }

            .topbar {
                padding: 12px 18px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-logo">R</div>
            <div class="sidebar-brand-text">
                <h1>RKVIT REALESTATE</h1>
                <span>ADMIN PANEL</span>
            </div>
        </a>

        <div class="sidebar-user">
            <a href="{{ route('admin.profile') }}" class="sidebar-user" style="text-decoration:none;">
                <div class="user-avatar">{{ Auth::user()->initials ?? strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
                <div class="user-info">
                    <h4>{{ Auth::user()->name ?? 'Admin' }}</h4>
                    <span>{{ Auth::user()->email ?? '' }}</span>
                    <div class="admin-badge">Administrator</div>
                </div>
            </a>
        </div>

        <nav class="sidebar-menu">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large menu-icon"></i>
                <span class="menu-label">Dashboard</span>
            </a>

            {{-- ============ PROPERTIES ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.properties.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-building menu-icon"></i>
                    <span class="menu-label">Properties</span>
                    <span class="menu-badge">{{ \App\Models\Property::where('status','pending')->count() }}</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('admin.properties.index') }}"
                        class="submenu-item {{ request()->routeIs('admin.properties.index') && !request()->filled('status') ? 'active' : '' }}">
                        <i class="fas fa-list" style="width:14px; font-size:0.75rem;"></i>
                        All Properties
                    </a>
                    <a href="{{ route('admin.properties.create') }}"
                        class="submenu-item {{ request()->routeIs('admin.properties.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle" style="width:14px; font-size:0.75rem;"></i>
                        Add New Property
                    </a>
                    <div class="submenu-divider"></div>
                    <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}"
                        class="submenu-item {{ request('status') === 'pending' ? 'active' : '' }}">
                        <i class="fas fa-clock" style="width:14px; font-size:0.75rem;"></i>
                        Pending Approval
                        <span class="submenu-badge">{{ \App\Models\Property::where('status','pending')->count() }}</span>
                    </a>
                    <a href="{{ route('admin.properties.index', ['status' => 'active']) }}"
                        class="submenu-item {{ request('status') === 'active' ? 'active' : '' }}">
                        <i class="fas fa-check-circle" style="width:14px; font-size:0.75rem;"></i>
                        Active
                    </a>
                    <a href="{{ route('admin.properties.index', ['status' => 'rejected']) }}"
                        class="submenu-item {{ request('status') === 'rejected' ? 'active' : '' }}">
                        <i class="fas fa-times-circle" style="width:14px; font-size:0.75rem;"></i>
                        Rejected
                    </a>
                    <a href="{{ route('admin.properties.index', ['status' => 'sold']) }}"
                        class="submenu-item {{ request('status') === 'sold' ? 'active' : '' }}">
                        <i class="fas fa-archive" style="width:14px; font-size:0.75rem;"></i>
                        Sold / Rented
                    </a>
                </div>
            </div>

            {{-- ============ CATEGORIES ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.categories.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-layer-group menu-icon"></i>
                    <span class="menu-label">Categories</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('admin.categories.index') }}"
                        class="submenu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <i class="fas fa-th-list" style="width:14px; font-size:0.75rem;"></i>
                        All Categories
                    </a>
                    <a href="{{ route('admin.categories.create') }}"
                        class="submenu-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                        <i class="fas fa-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add New Category
                    </a>
                </div>
            </div>

            {{-- ============ PROPERTY TYPES ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.property-types.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-home menu-icon"></i>
                    <span class="menu-label">Property Types</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-th-list" style="width:14px; font-size:0.75rem;"></i>
                        All Types
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add New Type
                    </a>
                </div>
            </div>

            {{-- ============ AMENITIES ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.amenities.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-star menu-icon"></i>
                    <span class="menu-label">Amenities</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-th-list" style="width:14px; font-size:0.75rem;"></i>
                        All Amenities
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add New Amenity
                    </a>
                </div>
            </div>

            {{-- ============ LOCATIONS ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.locations.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-map-marked-alt menu-icon"></i>
                    <span class="menu-label">Locations</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-city" style="width:14px; font-size:0.75rem;"></i>
                        States & Cities
                    </a>
                </div>
            </div>

            {{-- ============ USERS ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.users.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-users menu-icon"></i>
                    <span class="menu-label">Users</span>
                    <span class="menu-badge">{{ \App\Models\User::where('role','customer')->count() }}</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('admin.users.index') }}"
                        class="submenu-item {{ request()->routeIs('admin.users.index') && !request()->filled('role') ? 'active' : '' }}">
                        <i class="fas fa-list" style="width:14px; font-size:0.75rem;"></i>
                        All Users
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
                        class="submenu-item {{ request('role') === 'customer' ? 'active' : '' }}">
                        <i class="fas fa-user" style="width:14px; font-size:0.75rem;"></i>
                        Customers
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                        class="submenu-item {{ request('role') === 'admin' ? 'active' : '' }}">
                        <i class="fas fa-user-shield" style="width:14px; font-size:0.75rem;"></i>
                        Administrators
                    </a>
                </div>
            </div>

            {{-- ============ SETTINGS ============ --}}
            <div class="menu-parent {{ request()->routeIs('admin.profile*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-cog menu-icon"></i>
                    <span class="menu-label">Settings</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('admin.profile') }}"
                        class="submenu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-cog" style="width:14px; font-size:0.75rem;"></i>
                        Profile Settings
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-globe" style="width:14px; font-size:0.75rem;"></i>
                        Site Settings
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-envelope" style="width:14px; font-size:0.75rem;"></i>
                        Email Templates
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-search" style="width:14px; font-size:0.75rem;"></i>
                        SEO Settings
                    </a>
                </div>
            </div>

        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item">
                    <i class="fas fa-sign-out-alt menu-icon"></i>
                    <span class="menu-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div class="page-title">
                    @yield('page-title', 'Admin Dashboard')
                    <span>@yield('page-subtitle', 'Manage all properties & users')</span>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ url('/') }}" class="topbar-icon" title="Visit Site"><i class="fas fa-globe"></i></a>
                <div class="topbar-icon"><i class="fas fa-bell"></i><span class="dot"></span></div>
                <div class="topbar-icon"><i class="fas fa-envelope"></i><span class="dot"></span></div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (menuToggle) menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });
        if (overlay) overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        document.querySelectorAll('.menu-parent > .menu-toggle-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                if (e.target.closest('a')) return;
                e.preventDefault();
                const parent = this.parentElement;
                document.querySelectorAll('.menu-parent.open').forEach(op => {
                    if (op !== parent) op.classList.remove('open');
                });
                parent.classList.toggle('open');
            });
        });
        document.querySelectorAll('.submenu-item.active').forEach(function(active) {
            const parent = active.closest('.menu-parent');
            if (parent) parent.classList.add('open');
        });
    </script>
</body>

</html>