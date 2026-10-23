<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if (isset($categoryFilter))
        {{ $categoryFilter->name }} Properties
        @elseif (request('q'))
        "{{ request('q') }}" - Search Results
        @else
        Search Properties
        @endif
        - RKVIT REALESTATE
    </title>
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
            --secondary: #1a2332;
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --white: #fff;
            --bg-body: #f5f7fa;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 48px rgba(0, 0, 0, 0.12);
            --radius: 14px;
        }

        body {
            background: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ==================== NAVBAR ==================== */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 200;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .logo-img {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .logo-text h1 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.1;
        }

        .logo-text span {
            font-size: 0.65rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: auto;
        }

        .nav-links a {
            padding: 9px 16px;
            font-weight: 500;
            font-size: 0.88rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .nav-links a.active {
            color: var(--primary);
            font-weight: 600;
        }

        .btn-post {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff !important;
            padding: 9px 18px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-post:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: #fff !important;
        }

        .btn-nav {
            padding: 9px 18px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.82rem;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-nav:hover {
            background: var(--primary) !important;
            color: #fff !important;
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            background: linear-gradient(135deg, #1a2332 0%, #2c3e50 100%);
            padding: 40px 0 80px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(216, 35, 42, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.9);
        }

        .breadcrumb a:hover {
            color: #fff;
        }

        .breadcrumb i {
            font-size: 0.65rem;
            opacity: 0.6;
        }

        .breadcrumb .current {
            color: #fff;
            font-weight: 600;
        }

        .header-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            flex-wrap: wrap;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .header-title .highlight {
            color: #fbbf24;
        }

        .header-subtitle {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .header-subtitle .result-count {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.82rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-subtitle .result-count strong {
            color: #fbbf24;
            font-size: 0.95rem;
            margin-right: 4px;
        }

        .header-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .header-stat i {
            color: #fbbf24;
            font-size: 0.9rem;
        }

        /* Quick chips (active filters shown) */
        .active-filters {
            margin-top: 20px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-chip {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .filter-chip .remove {
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .filter-chip .remove:hover {
            opacity: 1;
        }

        .clear-filters {
            background: rgba(239, 68, 68, 0.9);
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .clear-filters:hover {
            background: #dc2626;
        }

        /* ==================== MAIN LAYOUT ==================== */
        .main-wrapper {
            padding: 0 0 60px;
            margin-top: -45px;
            position: relative;
            z-index: 10;
        }

        .layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .sidebar-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h3 {
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dark);
        }

        .sidebar-header h3 i {
            color: var(--primary);
        }

        .sidebar-reset {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .sidebar-reset:hover {
            color: var(--primary);
        }

        .sidebar-body {
            padding: 20px;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-group label {
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-soft);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .filter-group input[type="text"],
        .filter-group input[type="number"],
        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: 0.85rem;
            outline: none;
            background: #fafbfc;
            font-family: inherit;
            transition: all 0.2s;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(216, 35, 42, 0.08);
        }

        .price-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .filter-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(216, 35, 42, 0.25);
            transition: all 0.2s;
            margin-top: 6px;
        }

        .filter-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(216, 35, 42, 0.35);
        }

        /* ==================== RESULTS SECTION ==================== */
        .results-section {
            min-width: 0;
        }

        .toolbar {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.88rem;
            color: var(--text-soft);
        }

        .toolbar-left strong {
            color: var(--text-dark);
            font-weight: 700;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sort-select {
            padding: 9px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            background: #fff;
            outline: none;
            font-family: inherit;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s;
        }

        .sort-select:focus {
            border-color: var(--primary);
        }

        .view-toggle {
            display: flex;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .view-btn {
            padding: 8px 12px;
            border: none;
            background: #fff;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .view-btn.active {
            background: var(--primary);
            color: #fff;
        }

        .view-btn:not(.active):hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        /* ==================== PROPERTY GRID ==================== */
        .property-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .property-card {
            background: #fff;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: rgba(216, 35, 42, 0.2);
        }

        .property-img {
            height: 200px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        }

        .property-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .property-card:hover .property-img img {
            transform: scale(1.06);
        }

        .img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.5) 100%);
            pointer-events: none;
        }

        .property-tag {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--primary);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 6px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .property-tag.rent {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .property-tag.sale {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .property-featured {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
            display: flex;
            align-items: center;
            gap: 3px;
            letter-spacing: 0.3px;
        }

        .img-price {
            position: absolute;
            bottom: 14px;
            left: 14px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.01em;
        }

        .img-views {
            position: absolute;
            bottom: 14px;
            right: 14px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .property-info {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .property-type-row {
            display: flex;
            gap: 6px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .type-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            background: #f1f5f9;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .type-badge.verified {
            background: #ecfdf5;
            color: #059669;
        }

        .property-info h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 8px;
            line-height: 1.35;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            min-height: 2.7em;
        }

        .property-location {
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .property-location i {
            color: var(--primary);
            font-size: 0.75rem;
        }

        .property-meta {
            display: flex;
            gap: 14px;
            color: var(--text-soft);
            font-size: 0.82rem;
            padding: 14px 0;
            border-top: 1px solid #f1f5f9;
            margin-top: auto;
        }

        .property-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .property-meta .meta-item i {
            color: var(--primary);
            font-size: 0.82rem;
            width: 14px;
            text-align: center;
        }

        .property-meta .meta-item strong {
            color: var(--text-dark);
            font-weight: 700;
        }

        .property-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.2);
            letter-spacing: 0.2px;
        }

        .btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(216, 35, 42, 0.3);
        }

        .btn-view i {
            font-size: 0.7rem;
        }

        .listed-time {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            background: #fff;
            border-radius: var(--radius);
            border: 1px dashed var(--border);
            padding: 70px 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .empty-icon i {
            font-size: 2.5rem;
            color: var(--primary);
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 10px;
            color: var(--secondary);
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 24px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            padding: 11px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.25);
            transition: all 0.2s;
        }

        .btn-reset:hover {
            transform: translateY(-1px);
        }

        .btn-alt {
            background: #fff;
            border: 1.5px solid var(--border);
            color: var(--text-soft);
            padding: 11px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-alt:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ==================== PAGINATION ==================== */
        .pagination-wrap {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrap svg {
            width: 16px;
            height: 16px;
        }

        .pagination-wrap nav>div:first-child {
            display: none;
        }

        /* ==================== MOBILE FILTER TOGGLE ==================== */
        .mobile-filter-bar {
            display: none;
            background: #fff;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            justify-content: space-between;
            align-items: center;
        }

        .mobile-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
        }

        .mobile-count {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 320px;
                max-width: 90vw;
                height: 100vh;
                z-index: 1000;
                border-radius: 0;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .mobile-filter-bar {
                display: flex;
            }

            .property-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .header-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 0 16px;
            }

            .page-header {
                padding: 24px 0 70px;
            }

            .header-title {
                font-size: 1.35rem;
            }

            .header-subtitle {
                font-size: 0.85rem;
            }

            .header-stats {
                gap: 14px;
            }

            .property-grid {
                grid-template-columns: 1fr;
            }

            .main-wrapper {
                margin-top: -40px;
            }

            .toolbar {
                padding: 12px 16px;
            }

            .toolbar-left {
                font-size: 0.82rem;
            }

            .property-img {
                height: 220px;
            }

            .navbar .container {
                flex-wrap: wrap;
                gap: 12px;
            }

            .nav-links {
                gap: 4px;
                flex-wrap: wrap;
            }

            .nav-links a {
                padding: 7px 12px;
                font-size: 0.8rem;
            }

            .btn-post,
            .btn-nav {
                padding: 7px 14px;
                font-size: 0.78rem;
            }
        }
    </style>
</head>

<body>

    <!-- ==================== NAVBAR ==================== -->
    <header class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="logo-area">
                <div class="logo-img">R</div>
                <div class="logo-text">
                    <h1>RKVIT REALESTATE</h1>
                    <span>PRIVATE LIMITED</span>
                </div>
            </a>
            <nav class="nav-links">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('public.search', ['listing_type' => 'sale']) }}" class="{{ request('listing_type') === 'sale' ? 'active' : '' }}">Buy</a>
                <a href="{{ route('public.search', ['listing_type' => 'rent']) }}" class="{{ request('listing_type') === 'rent' ? 'active' : '' }}">Rent</a>
                <a href="{{ route('public.post-property') }}" class="btn-post">
                    <i class="fas fa-plus-circle"></i> Post Property
                </a>
                @auth
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="btn-nav">
                    <i class="fas fa-user"></i> Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="btn-nav">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- ==================== PAGE HEADER ==================== -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">

                {{-- Breadcrumb --}}
                <nav class="breadcrumb">
                    <a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ route('public.search') }}">Properties</a>
                    @if (isset($categoryFilter))
                    <i class="fas fa-chevron-right"></i>
                    <span class="current">{{ $categoryFilter->name }}</span>
                    @elseif (request('city_id') && isset($cities))
                    @php $currentCity = $cities->firstWhere('id', request('city_id')); @endphp
                    @if ($currentCity)
                    <i class="fas fa-chevron-right"></i>
                    <span class="current">{{ $currentCity->name }}</span>
                    @endif
                    @endif
                </nav>

                {{-- Title Row --}}
                <div class="header-title-row">
                    <div>
                        <h1 class="header-title">
                            @if (isset($categoryFilter))
                            {{ $categoryFilter->name }} in <span class="highlight">Patna</span>
                            @elseif (request('q'))
                            Search: <span class="highlight">"{{ request('q') }}"</span>
                            @elseif (request('listing_type') === 'rent')
                            Properties for <span class="highlight">Rent</span>
                            @elseif (request('listing_type') === 'sale')
                            Properties for <span class="highlight">Sale</span>
                            @else
                            All <span class="highlight">Properties</span>
                            @endif
                        </h1>
                        <div class="header-subtitle">
                            <span class="result-count">
                                <strong>{{ $properties->total() }}</strong>
                                {{ Str::plural('property', $properties->total()) }} found
                            </span>
                            @if (request('city_id') && isset($cities))
                            @php $city = $cities->firstWhere('id', request('city_id')); @endphp
                            @if ($city)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $city->name }}</span>
                            @endif
                            @endif
                        </div>
                    </div>

                    <div class="header-stats">
                        <div class="header-stat">
                            <i class="fas fa-check-circle"></i>
                            <span>Verified Listings</span>
                        </div>
                        <div class="header-stat">
                            <i class="fas fa-shield-alt"></i>
                            <span>Zero Brokerage</span>
                        </div>
                        <div class="header-stat">
                            <i class="fas fa-headset"></i>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>

                {{-- Active Filter Chips --}}
                @php
                $activeFilters = collect(request()->only(['q', 'city_id', 'property_type_id', 'listing_type', 'bedrooms', 'min_price', 'max_price']))
                ->filter()
                ->except(['sort']);
                @endphp

                @if ($activeFilters->count() > 0)
                <div class="active-filters">
                    @if (request('q'))
                    <span class="filter-chip">
                        <i class="fas fa-search"></i> "{{ request('q') }}"
                    </span>
                    @endif
                    @if (request('city_id') && isset($cities) && ($c = $cities->firstWhere('id', request('city_id'))))
                    <span class="filter-chip">
                        <i class="fas fa-map-marker-alt"></i> {{ $c->name }}
                    </span>
                    @endif
                    @if (request('property_type_id') && isset($propertyTypes) && ($t = $propertyTypes->firstWhere('id', request('property_type_id'))))
                    <span class="filter-chip">
                        <i class="fas fa-building"></i> {{ $t->name }}
                    </span>
                    @endif
                    @if (request('listing_type'))
                    <span class="filter-chip">
                        <i class="fas fa-tag"></i> For {{ ucfirst(request('listing_type')) }}
                    </span>
                    @endif
                    @if (request('bedrooms'))
                    <span class="filter-chip">
                        <i class="fas fa-bed"></i> {{ request('bedrooms') }}+ Beds
                    </span>
                    @endif
                    <a href="{{ isset($categoryFilter) ? route('public.category.show', $categoryFilter) : route('public.search') }}" class="clear-filters">
                        <i class="fas fa-times"></i> Clear All
                    </a>
                </div>
                @endif

            </div>
        </div>
    </section>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="container main-wrapper">

        {{-- Mobile Filter Bar --}}
        <div class="mobile-filter-bar">
            <button class="mobile-filter-btn" id="openFilters">
                <i class="fas fa-filter"></i> Filters
            </button>
            <span class="mobile-count">{{ $properties->total() }} results</span>
        </div>

        <div class="layout">

            {{-- ============ SIDEBAR ============ --}}
            <aside class="sidebar" id="filterSidebar">
                <form method="GET" action="{{ route('public.search') }}">
                    @if (isset($categoryFilter))
                    <input type="hidden" name="category_id" value="{{ $categoryFilter->id }}">
                    @endif

                    <div class="sidebar-header">
                        <h3><i class="fas fa-filter"></i> Filters</h3>
                        <a href="{{ isset($categoryFilter) ? route('public.category.show', $categoryFilter) : route('public.search') }}" class="sidebar-reset">
                            Reset
                        </a>
                    </div>

                    <div class="sidebar-body">

                        {{-- Keyword --}}
                        <div class="filter-group">
                            <label>Keyword</label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Title, locality...">
                        </div>

                        {{-- City --}}
                        <div class="filter-group">
                            <label>City</label>
                            <select name="city_id">
                                <option value="">All Cities</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Property Type --}}
                        <div class="filter-group">
                            <label>Property Type</label>
                            <select name="property_type_id">
                                <option value="">All Types</option>
                                @foreach ($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Listing Type --}}
                        <div class="filter-group">
                            <label>Listing Type</label>
                            <select name="listing_type">
                                <option value="">Sale / Rent</option>
                                <option value="sale" {{ request('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ request('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>

                        {{-- Bedrooms --}}
                        <div class="filter-group">
                            <label>Bedrooms (min)</label>
                            <select name="bedrooms">
                                <option value="">Any</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+ Beds</option>
                                    @endfor
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div class="filter-group">
                            <label>Price Range (₹)</label>
                            <div class="price-row">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max">
                            </div>
                        </div>

                        <button type="submit" class="filter-btn">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </aside>

            {{-- ============ RESULTS ============ --}}
            <div class="results-section">

                {{-- Toolbar --}}
                <div class="toolbar">
                    <div class="toolbar-left">
                        Showing <strong>{{ $properties->firstItem() ?? 0 }}–{{ $properties->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $properties->total() }}</strong> {{ Str::plural('property', $properties->total()) }}
                    </div>
                    <div class="toolbar-right">
                        <form method="GET" action="{{ isset($categoryFilter) ? route('public.category.show', $categoryFilter) : route('public.search') }}" style="margin:0;">
                            @foreach (request()->except('sort', 'page') as $key => $val)
                            @if (is_scalar($val))
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                            @endforeach
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Properties --}}
                @if ($properties->count() > 0)
                <div class="property-grid">
                    @foreach ($properties as $property)
                    @php
                    $img = $property->primaryImage
                    ? $property->primaryImage->url
                    : ($property->featured_image
                    ? asset('storage/'.$property->featured_image)
                    : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600&h=400&fit=crop');
                    @endphp
                    <article class="property-card">
                        <div class="property-img">
                            <img src="{{ $img }}" alt="{{ $property->title }}" loading="lazy">
                            <div class="img-overlay"></div>

                            <span class="property-tag {{ $property->listing_type }}">
                                <i class="fas fa-{{ $property->listing_type === 'rent' ? 'key' : 'tag' }}"></i>
                                For {{ ucfirst($property->listing_type) }}
                            </span>

                            @if ($property->is_featured)
                            <span class="property-featured">
                                <i class="fas fa-star"></i> Featured
                            </span>
                            @endif

                            <span class="img-price">{{ $property->formatted_price }}</span>

                            <span class="img-views">
                                <i class="fas fa-eye"></i> {{ number_format($property->views_count) }}
                            </span>
                        </div>

                        <div class="property-info">
                            <div class="property-type-row">
                                <span class="type-badge">{{ $property->propertyType->name ?? 'Property' }}</span>
                                @if ($property->is_verified)
                                <span class="type-badge verified">
                                    <i class="fas fa-shield-alt" style="font-size:0.6rem;"></i> Verified
                                </span>
                                @endif
                            </div>

                            <h3>{{ $property->title }}</h3>

                            <div class="property-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->city->name ?? 'India' }}
                            </div>

                            <div class="property-meta">
                                @if ($property->bedrooms > 0)
                                <div class="meta-item">
                                    <i class="fas fa-bed"></i>
                                    <strong>{{ $property->bedrooms }}</strong> Beds
                                </div>
                                @endif
                                @if ($property->bathrooms > 0)
                                <div class="meta-item">
                                    <i class="fas fa-bath"></i>
                                    <strong>{{ $property->bathrooms }}</strong> Baths
                                </div>
                                @endif
                                @if ($property->area)
                                <div class="meta-item">
                                    <i class="fas fa-vector-square"></i>
                                    <strong>{{ number_format($property->area) }}</strong> {{ $property->area_unit }}
                                </div>
                                @endif
                            </div>

                            <div class="property-footer">
                                <span class="listed-time">
                                    <i class="far fa-clock"></i> {{ $property->created_at->diffForHumans() }}
                                </span>
                                <a href="{{ route('public.property.show', $property) }}" class="btn-view">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <div class="pagination-wrap">
                    {{ $properties->links() }}
                </div>

                @else
                {{-- Empty State --}}
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No properties found</h3>
                    <p>
                        We couldn't find any properties matching your search criteria.
                        Try adjusting your filters or explore all available listings.
                    </p>
                    <div class="empty-actions">
                        <a href="{{ isset($categoryFilter) ? route('public.category.show', $categoryFilter) : route('public.search') }}" class="btn-reset">
                            <i class="fas fa-redo"></i> Clear Filters
                        </a>
                        <a href="{{ url('/') }}" class="btn-alt">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile filter toggle
            const openBtn = document.getElementById('openFilters');
            const sidebar = document.getElementById('filterSidebar');

            if (openBtn && sidebar) {
                openBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.add('open');
                });

                // Close on backdrop click
                document.addEventListener('click', function(e) {
                    if (sidebar.classList.contains('open') &&
                        !sidebar.contains(e.target) &&
                        e.target !== openBtn &&
                        !openBtn.contains(e.target)) {
                        sidebar.classList.remove('open');
                    }
                });
            }
        });
    </script>

</body>

</html>