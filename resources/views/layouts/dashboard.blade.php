<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - RKVIT REALESTATE')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ==================== RESET & VARIABLES ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        :root {
            --primary: #d8232a;
            --primary-dark: #b71c1c;
            --primary-light: #fef2f2;
            --secondary: #2c3e50;
            --sidebar-bg: #1a2332;
            --sidebar-hover: #253044;
            --sidebar-submenu-bg: #131b28;
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --white: #ffffff;
            --bg-body: #f5f7fa;
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 6px 18px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --sidebar-width: 270px;
        }

        body {
            background-color: var(--bg-body);
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

        /* ==================== SIDEBAR ==================== */
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
            transition: transform 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #2c3e50 transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #2c3e50;
            border-radius: 3px;
        }

        /* --- Sidebar Brand --- */
        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-decoration: none;
            flex-shrink: 0;
        }

        .sidebar-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.35);
        }

        .sidebar-brand-text h1 {
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .sidebar-brand-text span {
            font-size: 0.6rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
            display: block;
        }

        /* --- User Card --- */
        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d8232a, #a01820);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
            position: relative;
            border: 2px solid rgba(255, 255, 255, 0.1);
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
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info span {
            font-size: 0.7rem;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        /* ==================== MENU ==================== */
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
            transition: color 0.18s;
            flex-shrink: 0;
        }

        .menu-item .menu-label {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .menu-item:hover i.menu-icon {
            color: var(--primary);
        }

        /* Active state (single items) */
        .menu-item.active {
            background: linear-gradient(90deg, rgba(216, 35, 42, 0.15), rgba(216, 35, 42, 0.02));
            color: white;
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

        /* Badges */
        .menu-badge {
            background: var(--primary);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            line-height: 1.4;
        }

        .menu-badge.gray {
            background: #334155;
            color: #94a3b8;
        }

        /* Arrow */
        .menu-arrow {
            font-size: 0.7rem;
            color: #64748b;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        /* ==================== SUBMENU ==================== */
        .menu-parent {
            margin-bottom: 2px;
        }

        .menu-parent.open>.menu-item {
            background: var(--sidebar-hover);
            color: white;
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
            transition: max-height 0.32s ease;
            background: var(--sidebar-submenu-bg);
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
            color: white;
        }

        .submenu-item:hover::before {
            background: var(--primary);
            box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.15);
        }

        .submenu-item.active {
            color: white;
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

        .submenu-badge {
            margin-left: auto;
            background: #334155;
            color: #cbd5e1;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .submenu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
            margin: 6px 12px;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }

        .sidebar-footer .menu-item {
            width: 100%;
            background: none;
            border: none;
            font-family: inherit;
            text-align: left;
            color: #f87171;
        }

        .sidebar-footer .menu-item i.menu-icon {
            color: #f87171;
        }

        .sidebar-footer .menu-item:hover {
            background: rgba(248, 113, 113, 0.1);
            color: #fca5a5;
        }

        .sidebar-footer .menu-item:hover i.menu-icon {
            color: #fca5a5;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
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
            color: var(--text-dark);
            cursor: pointer;
            padding: 6px;
        }

        .page-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.01em;
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

        .search-topbar {
            position: relative;
            display: none;
        }

        .search-topbar input {
            padding: 9px 14px 9px 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            width: 240px;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s;
        }

        .search-topbar input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
        }

        .search-topbar i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
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
            transition: all 0.2s;
        }

        .topbar-icon:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: rgba(216, 35, 42, 0.2);
        }

        .topbar-icon .dot {
            position: absolute;
            top: 8px;
            right: 9px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            border: 2px solid white;
        }

        .btn-add-top {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.25);
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-add-top:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(216, 35, 42, 0.35);
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        /* ==================== SHARED COMPONENTS ==================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: white;
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

        .stat-trend {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .stat-trend.up {
            background: #ecfdf5;
            color: #059669;
        }

        .stat-trend.down {
            background: #fef2f2;
            color: #dc2626;
        }

        .stat-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--text-dark);
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
            background: white;
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
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.01em;
        }

        .panel-header h3 i {
            color: var(--primary);
            font-size: 1rem;
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
            background: white;
            border: 1px solid var(--border);
            color: var(--text-soft);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .btn-solid {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.2);
            text-decoration: none;
        }

        .btn-solid:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(216, 35, 42, 0.3);
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
            color: var(--text-dark);
            vertical-align: middle;
        }

        table tbody tr {
            transition: background 0.15s;
        }

        table tbody tr:hover {
            background: #f8fafc;
        }

        .property-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .property-thumb {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #eef2f6;
            object-fit: cover;
            flex-shrink: 0;
        }

        .property-cell-info strong {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .property-cell-info span {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.2px;
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

        .action-btns {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: white;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            font-size: 0.8rem;
            text-decoration: none;
        }

        .action-btn:hover {
            color: white;
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

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            padding: 22px;
        }

        .category-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            background: white;
            position: relative;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .category-card:hover {
            transform: translateY(-4px);
            border-color: rgba(216, 35, 42, 0.3);
            box-shadow: var(--shadow-md);
        }

        .category-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin: 0 auto 12px;
        }

        .category-card h4 {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .category-card span {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .category-count {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f1f5f9;
            color: var(--text-soft);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 24px;
        }

        .activity-list {
            padding: 12px 24px 20px;
        }

        .activity-item {
            display: flex;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .activity-icon.red {
            background: var(--primary-light);
            color: var(--primary);
        }

        .activity-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        .activity-icon.green {
            background: #ecfdf5;
            color: #10b981;
        }

        .activity-icon.purple {
            background: #f5f3ff;
            color: #8b5cf6;
        }

        .activity-content p {
            font-size: 0.85rem;
            color: var(--text-dark);
            line-height: 1.4;
            margin-bottom: 3px;
        }

        .activity-content p strong {
            font-weight: 700;
        }

        .activity-content span {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        /* ==================== RESPONSIVE ==================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 26, 38, 0.5);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .search-topbar {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .content-area {
                padding: 18px;
            }

            .topbar {
                padding: 12px 18px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.4rem;
            }

            .search-topbar {
                display: none;
            }

            .btn-add-top span {
                display: none;
            }

            .btn-add-top {
                padding: 10px 12px;
            }

            .panel-header {
                padding: 16px 18px;
            }

            .category-grid {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- ==================== MOBILE OVERLAY ==================== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="sidebar-logo">R</div>
            <div class="sidebar-brand-text">
                <h1>RKVIT REALESTATE</h1>
                <span>PRIVATE LIMITED</span>
            </div>
        </a>

        <!-- User Card -->
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <h4>{{ Auth::user()->name ?? 'User' }}</h4>
                <span>{{ Auth::user()->email ?? 'user@example.com' }}</span>
            </div>
        </div>

        <!-- ==================== MENU ==================== -->
        <nav class="sidebar-menu">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large menu-icon"></i>
                <span class="menu-label">Dashboard</span>
            </a>

            {{-- ========== PROPERTY MANAGEMENT ========== --}}
            <div class="menu-parent {{ request()->routeIs('properties.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-building menu-icon"></i>
                    <span class="menu-label">Property Management</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('properties.index') }}"
                        class="submenu-item {{ request()->routeIs('properties.index') ? 'active' : '' }}">
                        <i class="fas fa-list" style="width:14px; font-size:0.75rem;"></i>
                        All Properties
                    </a>
                    <a href="{{ route('properties.create') }}"
                        class="submenu-item {{ request()->routeIs('properties.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle" style="width:14px; font-size:0.75rem;"></i>
                        Add New Property
                    </a>
                    <a href="{{ route('properties.index', ['is_featured' => 'yes']) }}" class="submenu-item">
                        <i class="fas fa-star" style="width:14px; font-size:0.75rem;"></i>
                        Featured Properties
                    </a>
                    <a href="{{ route('properties.index', ['status' => 'pending']) }}" class="submenu-item">
                        <i class="fas fa-clock" style="width:14px; font-size:0.75rem;"></i>
                        Pending Approval
                    </a>
                    <a href="{{ route('properties.index', ['status' => 'sold']) }}" class="submenu-item">
                        <i class="fas fa-archive" style="width:14px; font-size:0.75rem;"></i>
                        Archived / Sold
                    </a>
                </div>
            </div>

            {{-- ========== PROPERTY CATEGORIES ========== --}}
            <div class="menu-parent {{ request()->routeIs('categories.*') ? 'open' : '' }}">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-layer-group menu-icon"></i>
                    <span class="menu-label">Property Categories</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="{{ route('categories.index') }}"
                        class="submenu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                        <i class="fas fa-th-list" style="width:14px; font-size:0.75rem;"></i>
                        All Categories
                    </a>
                    <a href="{{ route('categories.create') }}"
                        class="submenu-item {{ request()->routeIs('categories.create') ? 'active' : '' }}">
                        <i class="fas fa-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add New Category
                    </a>
                </div>
            </div>

            {{-- ========== LOCATIONS ========== --}}
            <div class="menu-parent">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-map-marked-alt menu-icon"></i>
                    <span class="menu-label">Locations</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-city" style="width:14px; font-size:0.75rem;"></i>
                        All Cities
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add City
                    </a>
                </div>
            </div>

            {{-- ========== CRM ========== --}}
            <div class="menu-parent">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-users menu-icon"></i>
                    <span class="menu-label">CRM</span>
                    <span class="menu-badge gray">New</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-friends" style="width:14px; font-size:0.75rem;"></i>
                        All Customers
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-plus" style="width:14px; font-size:0.75rem;"></i>
                        Add Customer
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-tie" style="width:14px; font-size:0.75rem;"></i>
                        Agents
                    </a>
                    <div class="submenu-divider"></div>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-envelope-open-text" style="width:14px; font-size:0.75rem;"></i>
                        Inquiries
                        <span class="submenu-badge">8</span>
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-calendar-check" style="width:14px; font-size:0.75rem;"></i>
                        Site Visits
                    </a>
                </div>
            </div>

            {{-- ========== REPORTS ========== --}}
            <div class="menu-parent">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-chart-pie menu-icon"></i>
                    <span class="menu-label">Reports & Analytics</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-line" style="width:14px; font-size:0.75rem;"></i>
                        Dashboard Analytics
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-alt" style="width:14px; font-size:0.75rem;"></i>
                        Property Reports
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-invoice-dollar" style="width:14px; font-size:0.75rem;"></i>
                        Transaction History
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-chart-bar" style="width:14px; font-size:0.75rem;"></i>
                        Sales Reports
                    </a>
                </div>
            </div>

            {{-- ========== MEDIA ========== --}}
            <div class="menu-parent">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-photo-video menu-icon"></i>
                    <span class="menu-label">Media Library</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-images" style="width:14px; font-size:0.75rem;"></i>
                        All Images
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-camera" style="width:14px; font-size:0.75rem;"></i>
                        Property Photos
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-file-pdf" style="width:14px; font-size:0.75rem;"></i>
                        Documents
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-cloud-upload-alt" style="width:14px; font-size:0.75rem;"></i>
                        Upload Media
                    </a>
                </div>
            </div>

            {{-- ========== SETTINGS ========== --}}
            <div class="menu-parent">
                <div class="menu-item menu-toggle-item">
                    <i class="fas fa-cog menu-icon"></i>
                    <span class="menu-label">Settings</span>
                    <i class="fas fa-chevron-right menu-arrow"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-user-cog" style="width:14px; font-size:0.75rem;"></i>
                        Profile Settings
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-key" style="width:14px; font-size:0.75rem;"></i>
                        Change Password
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

        <!-- Footer / Logout -->
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

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    @yield('page-title', 'Dashboard')
                    <span>@yield('page-subtitle', 'Welcome back to RKVIT Realestate')</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="search-topbar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search properties...">
                </div>
                <div class="topbar-icon">
                    <i class="fas fa-bell"></i>
                    <span class="dot"></span>
                </div>
                <div class="topbar-icon">
                    <i class="fas fa-envelope"></i>
                    <span class="dot"></span>
                </div>
                <a href="#" class="btn-add-top">
                    <i class="fas fa-plus"></i>
                    <span>Add Property</span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        // ===== Sidebar toggle for mobile =====
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }

        // ===== Parent menu toggle (FIXED — lets submenu links work) =====
        document.querySelectorAll('.menu-parent > .menu-toggle-item').forEach(function(parentItem) {
            parentItem.addEventListener('click', function(e) {
                // Let clicks on actual links pass through
                if (e.target.closest('a')) return;

                e.preventDefault();
                e.stopPropagation();

                const parent = this.parentElement;

                // Accordion: close other open menus
                document.querySelectorAll('.menu-parent.open').forEach(function(openParent) {
                    if (openParent !== parent) openParent.classList.remove('open');
                });

                parent.classList.toggle('open');
            });
        });

        // ===== Auto-open parent if a submenu item is active =====
        document.querySelectorAll('.submenu-item.active').forEach(function(activeItem) {
            const parent = activeItem.closest('.menu-parent');
            if (parent) parent.classList.add('open');
        });
    </script>
</body>

</html>