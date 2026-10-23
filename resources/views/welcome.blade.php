<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RKVIT REALESTATE PRIVATE LIMITED | Find Your Dream Home</title>
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
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --text-muted: #94a3b8;
            --gray-light: #eef2f6;
            --border: #e2e8f0;
            --white: #fff;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }

        body {
            background: #fff;
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
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            background: var(--secondary);
            color: #cbd5e1;
            font-size: 0.85rem;
            padding: 8px 0;
            border-bottom: 1px solid #3a4a5a;
        }

        .top-bar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .top-bar i {
            color: var(--primary);
            margin-right: 6px;
        }

        .top-bar a {
            color: #e2e8f0;
            margin-left: 16px;
        }

        .top-bar a:hover {
            color: #fff;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: #fff;
            box-shadow: var(--shadow-sm);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(216, 35, 42, 0.2);
        }

        .logo-text h1 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: -0.02em;
        }

        .logo-text span {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 0.3px;
            display: block;
            margin-top: -2px;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-links a {
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }

        .btn-nav {
            padding: 9px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-nav-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-nav-outline:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-nav-solid {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.25);
        }

        .btn-nav-solid:hover {
            background: var(--primary-dark);
        }

        .btn-post {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 10px 22px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.88rem;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-post:hover {
            transform: translateY(-1px);
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, #1a2332, #2c3e50);
            padding: 60px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(216, 35, 42, 0.15), transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 14px;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero p {
            font-size: 1.05rem;
            color: #cbd5e1;
            margin-bottom: 28px;
        }

        /* ===== SEARCH BAR ===== */
        .search-box {
            background: white;
            border-radius: 16px;
            padding: 10px;
            box-shadow: var(--shadow-lg);
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            max-width: 900px;
            margin: 0 auto;
        }

        .search-field {
            flex: 1;
            min-width: 150px;
            position: relative;
        }

        .search-field select,
        .search-field input {
            width: 100%;
            padding: 12px 14px;
            border: none;
            outline: none;
            font-size: 0.9rem;
            background: transparent;
            color: var(--text-dark);
            font-family: inherit;
        }

        .search-field::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background: var(--border);
        }

        .search-field:last-of-type::after {
            display: none;
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.3);
            transition: all 0.2s;
        }

        .search-btn:hover {
            transform: translateY(-1px);
        }

        /* ===== CATEGORIES ===== */

        /* ===== BANNER SLIDER ===== */
        .slider-section {
            position: relative;
            background: #1a2332;
        }

        .slider-wrapper {
            position: relative;
            width: 100%;
            height: 520px;
            overflow: hidden;
        }

        .slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slide {
            flex: 0 0 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .slide.active img {
            transform: scale(1.12);
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    rgba(15, 26, 38, 0.85) 0%,
                    rgba(15, 26, 38, 0.6) 50%,
                    rgba(15, 26, 38, 0.3) 100%);
        }

        .slide-content {
            position: absolute;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            max-width: 620px;
            color: white;
            z-index: 2;
            animation: slideFadeIn 0.8s ease;
        }

        @keyframes slideFadeIn {
            from {
                opacity: 0;
                transform: translateY(-40%);
            }

            to {
                opacity: 1;
                transform: translateY(-50%);
            }
        }

        .slide-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(216, 35, 42, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 18px;
        }

        .slide-content h2 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 14px;
            letter-spacing: -0.03em;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .slide-content p {
            font-size: 1.1rem;
            line-height: 1.5;
            opacity: 0.95;
            margin-bottom: 28px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .slide-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: var(--primary);
            padding: 15px 32px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            transition: all 0.25s;
            letter-spacing: 0.3px;
        }

        .slide-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35);
        }

        .slide-cta i {
            transition: transform 0.25s;
        }

        .slide-cta:hover i {
            transform: translateX(4px);
        }

        /* Arrows */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.25s;
        }

        .slider-arrow:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.08);
        }

        .arrow-left {
            left: 24px;
        }

        .arrow-right {
            right: 24px;
        }

        /* Dots */
        .slider-dots {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0;
        }

        .dot.active {
            background: white;
            width: 32px;
            border-radius: 10px;
        }

        /* ===== SEARCH SECTION (overlaps slider) ===== */
        .search-section {
            position: relative;
            margin-top: -60px;
            z-index: 20;
            padding-bottom: 20px;
        }

        .search-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .search-tabs {
            display: flex;
            padding: 4px;
            background: #f5f7fa;
            border-radius: 14px;
        }

        .search-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-soft);
            border-radius: 10px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .search-tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(216, 35, 42, 0.3);
        }

        .search-tab:hover:not(.active) {
            color: var(--primary);
        }

        .search-form {
            flex: 1;
            display: flex;
            gap: 4px;
            align-items: stretch;
            flex-wrap: wrap;
            min-width: 0;
        }

        .search-field {
            flex: 1;
            min-width: 160px;
            padding: 8px 16px;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .search-field:last-of-type {
            border-right: none;
        }

        .search-field.city-field {
            min-width: 200px;
        }

        .search-field.keyword-field {
            flex: 2;
            min-width: 220px;
        }

        .search-field label {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .search-field label i {
            color: var(--primary);
            font-size: 0.7rem;
        }

        .search-field select,
        .search-field input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
            font-family: inherit;
            padding: 0;
            width: 100%;
            cursor: pointer;
        }

        .search-field input::placeholder {
            color: var(--text-muted);
            font-weight: 500;
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 15px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(216, 35, 42, 0.3);
            transition: all 0.2s;
            margin: 4px;
        }

        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(216, 35, 42, 0.4);
        }

        /* ===== RESPONSIVE SLIDER ===== */
        @media (max-width: 1024px) {
            .slider-wrapper {
                height: 460px;
            }

            .slide-content h2 {
                font-size: 2.4rem;
            }

            .slide-content {
                left: 6%;
                max-width: 500px;
            }
        }

        @media (max-width: 768px) {
            .slider-wrapper {
                height: 420px;
            }

            .slide-content {
                left: 24px;
                right: 24px;
                max-width: none;
                top: 45%;
            }

            .slide-content h2 {
                font-size: 1.8rem;
            }

            .slide-content p {
                font-size: 0.95rem;
            }

            .slide-badge {
                font-size: 0.68rem;
                padding: 6px 12px;
            }

            .slide-cta {
                padding: 12px 24px;
                font-size: 0.85rem;
            }

            .slider-arrow {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }

            .arrow-left {
                left: 12px;
            }

            .arrow-right {
                right: 12px;
            }

            .slider-dots {
                bottom: 90px;
            }

            .search-card {
                flex-direction: column;
                padding: 16px;
                gap: 12px;
            }

            .search-tabs {
                width: 100%;
                justify-content: center;
            }

            .search-form {
                flex-direction: column;
                width: 100%;
            }

            .search-field {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 12px 8px;
            }

            .search-field:last-of-type {
                border-bottom: none;
            }

            .search-btn {
                width: 100%;
                justify-content: center;
                margin: 8px 0 0;
            }

            .search-section {
                margin-top: -30px;
            }
        }

        @media (max-width: 480px) {
            .slider-wrapper {
                height: 380px;
            }

            .slide-content h2 {
                font-size: 1.5rem;
            }

            .slide-content p {
                font-size: 0.85rem;
                margin-bottom: 20px;
            }

            .slider-dots {
                bottom: 70px;
            }

            .dot {
                width: 8px;
                height: 8px;
            }

            .dot.active {
                width: 24px;
            }
        }

        /* new end */



        .section {
            padding: 60px 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: -0.02em;
        }

        .section-header p {
            color: var(--text-soft);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .section-header a {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
        }

        .category-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px 16px;
            text-align: center;
            transition: all 0.25s;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            display: block;
            color: inherit;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: rgba(216, 35, 42, 0.3);
        }

        .category-card .cat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 14px;
        }

        .category-card h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .category-card span {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .category-card .count-badge {
            display: inline-block;
            background: #f1f5f9;
            color: var(--text-soft);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 8px;
        }

        /* ===== FEATURED PROPERTIES ===== */
        .featured-section {
            background: #f5f7fa;
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .property-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .property-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .property-img {
            height: 220px;
            background: #cbd5e1;
            position: relative;
            overflow: hidden;
        }

        .property-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .property-card:hover .property-img img {
            transform: scale(1.05);
        }

        .property-tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--primary);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .property-tag.featured {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .property-price-badge {
            position: absolute;
            bottom: 16px;
            left: 16px;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 1.05rem;
        }

        .property-info {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .property-info h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 6px;
            line-height: 1.3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .property-location {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .property-location i {
            color: var(--primary);
        }

        .property-meta {
            display: flex;
            gap: 14px;
            color: var(--text-soft);
            font-size: 0.82rem;
            border-top: 1px solid #edf2f7;
            padding-top: 14px;
            margin-top: auto;
        }

        .property-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .property-meta i {
            color: var(--primary);
        }

        .property-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-view {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.2);
            transition: all 0.2s;
        }

        .btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(216, 35, 42, 0.3);
        }

        .property-type-badge {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-soft);
            background: #f1f5f9;
            padding: 5px 10px;
            border-radius: 6px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            border: 1px dashed var(--border);
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ===== CTA SECTION ===== */
        .cta-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 60px 24px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -40px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .cta-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .cta-section h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .cta-section p {
            font-size: 1.05rem;
            opacity: 0.95;
            margin-bottom: 28px;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: var(--primary);
            padding: 16px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            transition: all 0.2s;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #0f1a26;
            color: #94a3b8;
            padding: 48px 0 24px;
            font-size: 0.9rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul a:hover {
            color: #fff;
        }

        .footer-brand p {
            margin: 18px 0 16px;
            line-height: 1.6;
            font-size: 0.9rem;
            max-width: 300px;
        }

        .footer-bottom {
            border-top: 1px solid #1e2b3c;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.85rem;
        }

        .footer-bottom .designer {
            color: #cbd5e1;
            font-weight: 500;
        }

        .footer-bottom .designer i {
            color: var(--primary);
            margin-right: 4px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:1024px) {
            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .property-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .hero h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width:768px) {
            .navbar .container {
                flex-direction: column;
                gap: 16px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 14px;
            }

            .search-box {
                flex-direction: column;
                padding: 16px;
            }

            .search-field {
                width: 100%;
            }

            .search-field::after {
                display: none;
            }

            .search-btn {
                width: 100%;
                justify-content: center;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .property-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 1.8rem;
            }

            .hero p {
                font-size: 0.95rem;
            }

            .section {
                padding: 40px 0;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }

            .cta-section h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width:480px) {
            .category-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="container">
            <div><i class="fas fa-phone-alt"></i> +91-9835634422 &nbsp; | &nbsp; <i class="fas fa-envelope"></i> info@rkvitsolutions.com</div>
            <div><i class="fas fa-map-pin"></i> Patna, Bihar</div>
        </div>
    </div>

    <!-- NAVBAR -->
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
                <a href="{{ url('/') }}" class="active">Home</a>
                <a href="{{ route('public.search') }}">Buy</a>
                <a href="{{ route('public.search', ['listing_type' => 'rent']) }}">Rent</a>
                <a href="#">Contact</a>

                @auth
                <a href="{{ route('public.post-property') }}" class="btn-post">
                    <i class="fas fa-plus-circle"></i> Post Property
                </a>
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="btn-nav btn-nav-outline">
                    <i class="fas fa-user"></i> Dashboard
                </a>
                @else
                <a href="{{ route('public.post-property') }}" class="btn-post">
                    <i class="fas fa-plus-circle"></i> Post Property
                </a>
                <a href="{{ route('login') }}" class="btn-nav btn-nav-outline">Login</a>
                <a href="{{ route('register') }}" class="btn-nav btn-nav-solid">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- ========== BANNER SLIDER ========== -->
    <section class="slider-section">
        <div class="slider-wrapper">
            <div class="slides" id="bannerSlides">

                {{-- Slide 1 --}}
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1600&h=600&fit=crop&auto=format" alt="Luxury homes">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="slide-badge"><i class="fas fa-star"></i> PREMIUM LISTINGS</div>
                        <h2>Find Your Dream Home</h2>
                        <p>Explore handpicked premium properties across Patna & India</p>
                        <a href="{{ route('public.search') }}" class="slide-cta">Explore Properties <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1600&h=600&fit=crop&auto=format" alt="Modern apartments">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="slide-badge"><i class="fas fa-bolt"></i> READY TO MOVE</div>
                        <h2>Modern Apartments</h2>
                        <p>Zero brokerage · Verified listings · Best prices in the market</p>
                        <a href="{{ route('public.search', ['property_type_id' => 1]) }}" class="slide-cta">View Apartments <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1600&h=600&fit=crop&auto=format" alt="Luxury villas">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="slide-badge"><i class="fas fa-crown"></i> LUXURY LIVING</div>
                        <h2>Premium Villas & Plots</h2>
                        <p>Gated communities with world-class amenities</p>
                        <a href="{{ route('public.search', ['property_type_id' => 3]) }}" class="slide-cta">Discover Villas <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                {{-- Slide 4 --}}
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1600&h=600&fit=crop&auto=format" alt="Commercial spaces">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="slide-badge"><i class="fas fa-briefcase"></i> COMMERCIAL</div>
                        <h2>Office & Retail Spaces</h2>
                        <p>Prime commercial properties for your growing business</p>
                        <a href="{{ route('public.search', ['property_type_id' => 5]) }}" class="slide-cta">Explore Commercial <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                {{-- Slide 5 --}}
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1600&h=600&fit=crop&auto=format" alt="Trusted partner">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="slide-badge"><i class="fas fa-shield-alt"></i> TRUSTED PARTNER</div>
                        <h2>Your Property Journey Starts Here</h2>
                        <p>RKVIT Realestate — Trusted by thousands of happy families</p>
                        <a href="{{ route('public.post-property') }}" class="slide-cta">Post Your Property <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

            </div>

            {{-- Arrows --}}
            <button class="slider-arrow arrow-left" id="prevSlide" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-arrow arrow-right" id="nextSlide" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            {{-- Dots --}}
            <div class="slider-dots" id="sliderDots">
                <button class="dot active" data-slide="0" aria-label="Slide 1"></button>
                <button class="dot" data-slide="1" aria-label="Slide 2"></button>
                <button class="dot" data-slide="2" aria-label="Slide 3"></button>
                <button class="dot" data-slide="3" aria-label="Slide 4"></button>
                <button class="dot" data-slide="4" aria-label="Slide 5"></button>
            </div>
        </div>
    </section>

    <!-- ========== SEARCH SECTION (overlaps slider) ========== -->
    <section class="search-section">
        <div class="container">
            <div class="search-card">
                <div class="search-tabs">
                    <a href="{{ route('public.search', ['listing_type' => 'sale']) }}" class="search-tab {{ request('listing_type') === 'rent' ? '' : 'active' }}">
                        <i class="fas fa-home"></i> Buy
                    </a>
                    <a href="{{ route('public.search', ['listing_type' => 'rent']) }}" class="search-tab {{ request('listing_type') === 'rent' ? 'active' : '' }}">
                        <i class="fas fa-key"></i> Rent
                    </a>
                    <a href="{{ route('public.search') }}" class="search-tab">
                        <i class="fas fa-briefcase"></i> Commercial
                    </a>
                </div>

                <form action="{{ route('public.search') }}" method="GET" class="search-form">
                    {{-- Dynamic City Dropdown --}}
                    <div class="search-field city-field">
                        <label><i class="fas fa-map-marker-alt"></i> City</label>
                        <select name="city_id">
                            <option value="">All Cities</option>

                            @if ($popularCities->count() > 0)
                            <optgroup label="⭐ Popular Cities">
                                @foreach ($popularCities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                    @if ($city->properties_count > 0)
                                    ({{ $city->properties_count }})
                                    @endif
                                </option>
                                @endforeach
                            </optgroup>
                            @endif

                            <optgroup label="📍 All Cities">
                                @foreach ($allCities as $city)
                                @if (!$city->is_popular)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                    @if ($city->properties_count > 0)
                                    ({{ $city->properties_count }})
                                    @endif
                                </option>
                                @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    {{-- Property Type --}}
                    <div class="search-field">
                        <label><i class="fas fa-building"></i> Property Type</label>
                        <select name="property_type_id">
                            <option value="">All Types</option>
                            @foreach ($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keyword --}}
                    <div class="search-field keyword-field">
                        <label><i class="fas fa-search"></i> Search</label>
                        <input type="text" name="q" placeholder="Locality, project, keyword..." value="{{ request('q') }}">
                    </div>

                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ========== EXPLORE REAL ESTATE ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2>Explore Real Estate</h2>
                    <p>Browse by property categories</p>
                </div>
                <a href="{{ route('public.search') }}">View All →</a>
            </div>

            @if ($categories->count() > 0)
            <div class="category-grid">
                @foreach ($categories as $cat)
                <a href="{{ route('public.category.show', $cat) }}" class="category-card">
                    <div class="cat-icon" style="background:{{ $cat->color }}15; color:{{ $cat->color }};">
                        <i class="{{ $cat->icon }}"></i>
                    </div>
                    <h4>{{ $cat->name }}</h4>
                    <span>{{ $cat->properties_count }} {{ Str::plural('Property', $cat->properties_count) }}</span>
                </a>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-layer-group"></i>
                <h3>No categories available yet</h3>
                <p>Categories will appear here once added.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- ========== FEATURED PROPERTIES ========== -->
    <section class="section featured-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2>Featured Properties</h2>
                    <p>Hand-picked premium listings for you</p>
                </div>
                <a href="{{ route('public.search') }}">View All →</a>
            </div>

            @if ($featuredProperties->count() > 0)
            <div class="property-grid">
                @foreach ($featuredProperties as $property)
                @php
                $img = $property->primaryImage
                ? $property->primaryImage->url
                : ($property->featured_image
                ? asset('storage/'.$property->featured_image)
                : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600&h=400&fit=crop');
                @endphp
                <div class="property-card">
                    <div class="property-img">
                        <img src="{{ $img }}" alt="{{ $property->title }}" loading="lazy">
                        @if ($property->is_featured)
                        <span class="property-tag featured"><i class="fas fa-star"></i> Featured</span>
                        @else
                        <span class="property-tag">{{ ucfirst($property->listing_type) }}</span>
                        @endif
                        <span class="property-price-badge">{{ $property->formatted_price }}</span>
                    </div>
                    <div class="property-info">
                        <h3>{{ $property->title }}</h3>
                        <div class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->city->name ?? '' }}
                        </div>
                        <div class="property-meta">
                            @if ($property->bedrooms > 0)
                            <span><i class="fas fa-bed"></i> {{ $property->bedrooms }} Bed</span>
                            @endif
                            @if ($property->bathrooms > 0)
                            <span><i class="fas fa-bath"></i> {{ $property->bathrooms }} Bath</span>
                            @endif
                            @if ($property->area)
                            <span><i class="fas fa-vector-square"></i> {{ number_format($property->area) }} {{ $property->area_unit }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="property-footer">
                        <span class="property-type-badge">{{ $property->propertyType->name ?? 'Property' }}</span>
                        <a href="{{ route('public.property.show', $property) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <h3>No featured properties yet</h3>
                <p>Properties marked as featured will appear here.</p>
                <a href="{{ route('public.search') }}" class="btn-view" style="margin-top:16px;">
                    <i class="fas fa-search"></i> Browse All Properties
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- ========== CTA: POST PROPERTY ========== -->
    <section class="cta-section">
        <div class="cta-content">
            <div class="cta-badge"><i class="fas fa-bullhorn"></i> IT'S 100% FREE</div>
            <h2>Have a property to sell or rent?</h2>
            <p>List it on RKVIT Realestate and reach thousands of potential buyers & tenants across India.</p>
            <a href="{{ route('public.post-property') }}" class="cta-btn">
                <i class="fas fa-plus-circle"></i> Post Your Property Free
            </a>
            <div style="margin-top:20px; font-size:0.85rem; opacity:0.85;">
                <i class="fas fa-check-circle"></i> No hidden charges &nbsp;•&nbsp;
                <i class="fas fa-clock"></i> Approval in 24 hours
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-area">
                        <div class="logo-img" style="width:44px; height:44px; font-size:1.3rem;">R</div>
                        <div class="logo-text">
                            <h1 style="color:#fff; font-size:1.1rem;">RKVIT REALESTATE</h1>
                            <span style="color:#94a3b8;">PRIVATE LIMITED</span>
                        </div>
                    </div>
                    <p>Your trusted real estate partner in Patna & beyond. We help you buy, sell, and rent properties with complete transparency.</p>
                </div>
                <div>
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('public.search', ['listing_type' => 'sale']) }}">Buy</a></li>
                        <li><a href="{{ route('public.search', ['listing_type' => 'rent']) }}">Rent</a></li>
                        <li><a href="{{ route('public.post-property') }}">Post Property</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Categories</h5>
                    <ul>
                        @foreach ($categories->take(4) as $cat)
                        <li><a href="{{ route('public.category.show', $cat) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h5>Contact</h5>
                    <ul>
                        <li><i class="fas fa-map-marker-alt" style="color:var(--primary); width:18px;"></i> Yash Tower, 25/101, Ward No.-1, Patna 800011</li>
                        <li><i class="fas fa-phone-alt" style="color:var(--primary); width:18px;"></i> +91-9835634422</li>
                        <li><i class="fas fa-envelope" style="color:var(--primary); width:18px;"></i> info@rkvitsolutions.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>© {{ date('Y') }} RKVIT REALESTATE PRIVATE LIMITED. All rights reserved.</div>
                <div class="designer"><i class="fas fa-code"></i> Designed by: RKV IT Solutions Pvt. Ltd.</div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelector('.slides');
            const slideEls = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');

            if (!slides || slideEls.length === 0) return;

            let current = 0;
            const total = slideEls.length;
            let autoTimer = null;

            function goTo(index) {
                current = (index + total) % total;
                slides.style.transform = `translateX(-${current * 100}%)`;
                dots.forEach((d, i) => d.classList.toggle('active', i === current));
                slideEls.forEach((s, i) => s.classList.toggle('active', i === current));
            }

            function next() {
                goTo(current + 1);
            }

            function prev() {
                goTo(current - 1);
            }

            function startAuto() {
                stopAuto();
                autoTimer = setInterval(next, 5500);
            }

            function stopAuto() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            // Controls
            if (nextBtn) nextBtn.addEventListener('click', () => {
                next();
                startAuto();
            });
            if (prevBtn) prevBtn.addEventListener('click', () => {
                prev();
                startAuto();
            });

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goTo(i);
                    startAuto();
                });
            });

            // Pause on hover
            const wrapper = document.querySelector('.slider-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', stopAuto);
                wrapper.addEventListener('mouseleave', startAuto);
            }

            // Touch swipe (mobile)
            let touchStartX = 0;
            let touchEndX = 0;
            if (wrapper) {
                wrapper.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                    stopAuto();
                }, {
                    passive: true
                });

                wrapper.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    const diff = touchStartX - touchEndX;
                    if (Math.abs(diff) > 50) {
                        if (diff > 0) next();
                        else prev();
                    }
                    startAuto();
                }, {
                    passive: true
                });
            }

            // Keyboard nav
            document.addEventListener('keydown', e => {
                if (e.key === 'ArrowLeft') {
                    prev();
                    startAuto();
                }
                if (e.key === 'ArrowRight') {
                    next();
                    startAuto();
                }
            });

            // Init
            goTo(0);
            startAuto();
        });
    </script>
</body>

</html>