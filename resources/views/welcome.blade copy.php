<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RKVIT REALESTATE PRIVATE LIMITED | Find Your Dream Dashboard</title>
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ----- RESET & GLOBAL ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #1e2b3c;
            line-height: 1.5;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ----- COLORS (MagicBricks inspired) ----- */
        :root {
            --primary: #d8232a;
            /* magicbricks red */
            --primary-dark: #b71c1c;
            --secondary: #2c3e50;
            --accent: #f7f9fc;
            --gray-light: #eef2f6;
            --gray-medium: #94a3b8;
            --text-dark: #1e2b3c;
            --text-soft: #4a5c6e;
            --white: #ffffff;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        /* ----- HEADER / NAVBAR ----- */
        .top-bar {
            background-color: var(--secondary);
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
            margin-right: 6px;
            color: var(--primary);
        }

        .top-bar a {
            color: #e2e8f0;
            text-decoration: none;
            margin-left: 20px;
        }

        .top-bar a:hover {
            color: white;
        }

        .navbar {
            background: var(--white);
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
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(216, 35, 42, 0.2);
        }

        .logo-text h1 {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--secondary);
        }

        .logo-text span {
            font-size: 0.7rem;
            color: var(--gray-medium);
            letter-spacing: 0.3px;
            display: block;
            margin-top: -2px;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }

        .btn-post {
            background-color: var(--primary);
            color: white !important;
            padding: 10px 22px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
            border: none;
            cursor: default;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.25);
        }

        .btn-post:hover {
            background-color: var(--primary-dark) !important;
        }

        /* ----- BANNER SLIDER (5 images) ----- */
        .slider-section {
            padding: 32px 0 20px 0;
            background: linear-gradient(to bottom, #f9fbfd, #ffffff);
        }

        .slider-container {
            position: relative;
            width: 100%;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            background: #e2e8f0;
            aspect-ratio: 16 / 6;
        }

        .slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            flex: 0 0 100%;
            height: 100%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: brightness(0.75);
        }

        .slide-content {
            position: absolute;
            bottom: 20%;
            left: 8%;
            color: white;
            max-width: 550px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .slide-content h2 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .slide-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            margin-bottom: 20px;
        }

        .slide-btn {
            background: var(--primary);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-weight: 600;
            border: none;
            font-size: 1rem;
            cursor: default;
            box-shadow: 0 8px 20px rgba(216, 35, 42, 0.4);
            display: inline-block;
            transition: 0.2s;
        }

        .slide-btn:hover {
            background: var(--primary-dark);
        }

        /* Slider dots */
        .slider-dots {
            position: absolute;
            bottom: 18px;
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
            cursor: pointer;
            transition: 0.2s;
        }

        .dot.active {
            background: white;
            width: 26px;
            border-radius: 20px;
        }

        /* Slider arrows (static, non-functional) */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            cursor: default;
            z-index: 20;
            transition: background 0.2s;
        }

        .slider-arrow:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .arrow-left {
            left: 20px;
        }

        .arrow-right {
            right: 20px;
        }

        /* ----- SEARCH BAR (MagicBricks style) ----- */
        .search-section {
            margin-top: -28px;
            position: relative;
            z-index: 30;
        }

        .search-card {
            background: white;
            border-radius: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            padding: 8px;
            display: flex;
            align-items: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .search-tabs {
            display: flex;
            padding: 0 10px;
        }

        .search-tab {
            padding: 12px 22px;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-soft);
            border-radius: 40px;
            cursor: default;
        }

        .search-tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.3);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 14px 18px;
            font-size: 1rem;
            outline: none;
            background: transparent;
            color: var(--text-dark);
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .search-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 40px;
            padding: 14px 36px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: default;
            transition: 0.2s;
            box-shadow: 0 8px 16px rgba(216, 35, 42, 0.3);
        }

        .search-btn i {
            font-size: 1rem;
        }

        /* ----- CATEGORY / QUICK LINKS ----- */
        .category-section {
            padding: 60px 0 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: -0.02em;
        }

        .section-header a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
        }

        .category-card {
            background: var(--white);
            border: 1px solid #e9eef3;
            border-radius: 16px;
            padding: 24px 12px;
            text-align: center;
            transition: 0.25s;
            box-shadow: var(--shadow-sm);
            cursor: default;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: #d8232a20;
        }

        .category-card i {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 14px;
        }

        .category-card span {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        /* ----- FEATURED PROPERTIES ----- */
        .featured-section {
            padding: 50px 0 60px;
            background: var(--accent);
        }

        .property-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 20px;
        }

        .property-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: 0.25s;
            border: 1px solid #e9eef3;
        }

        .property-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .property-img {
            height: 200px;
            background: #cbd5e1;
            position: relative;
            overflow: hidden;
        }

        .property-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .property-tag {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--primary);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            letter-spacing: 0.3px;
        }

        .property-info {
            padding: 20px;
        }

        .property-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--secondary);
        }

        .property-location {
            color: var(--gray-medium);
            font-size: 0.9rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .property-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 14px;
        }

        .property-meta {
            display: flex;
            gap: 18px;
            color: var(--text-soft);
            font-size: 0.85rem;
            border-top: 1px solid #edf2f7;
            padding-top: 14px;
        }

        .property-meta i {
            margin-right: 4px;
            color: var(--primary);
        }

        /* ----- WHY CHOOSE US / TRUST BANNER ----- */
        .trust-section {
            padding: 50px 0;
            background: white;
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
            text-align: center;
        }

        .trust-item i {
            font-size: 2.4rem;
            color: var(--primary);
            margin-bottom: 12px;
            background: #fef2f2;
            padding: 16px;
            border-radius: 60px;
        }

        .trust-item h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .trust-item p {
            color: var(--text-soft);
            font-size: 0.9rem;
            max-width: 220px;
            margin: 0 auto;
        }

        /* ----- CONTACT / ADDRESS STRIP ----- */
        .contact-strip {
            background: var(--secondary);
            color: white;
            padding: 38px 0;
        }

        .contact-strip .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .contact-item i {
            font-size: 1.8rem;
            color: var(--primary);
            width: 44px;
            text-align: center;
        }

        .contact-item div {
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .contact-item strong {
            display: block;
            font-size: 1rem;
            font-weight: 600;
        }

        .contact-item span {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        /* ----- FOOTER ----- */
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

        .footer-brand .logo-text h1 {
            color: white;
            font-size: 1.2rem;
        }

        .footer-brand p {
            margin: 18px 0 16px;
            line-height: 1.6;
            font-size: 0.9rem;
            max-width: 300px;
        }

        .social-icons {
            display: flex;
            gap: 14px;
        }

        .social-icons a {
            color: #94a3b8;
            background: #1e2b3c;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.2s;
        }

        .social-icons a:hover {
            background: var(--primary);
            color: white;
        }

        .footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .footer ul {
            list-style: none;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul a {
            color: #94a3b8;
            text-decoration: none;
            transition: 0.2s;
        }

        .footer ul a:hover {
            color: white;
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

        /* ----- RESPONSIVE (basic) ----- */
        @media (max-width: 1024px) {
            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .property-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .trust-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .slide-content h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                gap: 16px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 16px;
            }

            .search-card {
                flex-direction: column;
                border-radius: 24px;
                padding: 16px;
            }

            .search-tabs {
                width: 100%;
                justify-content: center;
                margin-bottom: 8px;
            }

            .search-btn {
                width: 100%;
                justify-content: center;
                margin-top: 8px;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .property-grid {
                grid-template-columns: 1fr;
            }

            .trust-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .contact-strip .container {
                flex-direction: column;
                align-items: flex-start;
            }

            .slide-content {
                left: 6%;
                bottom: 12%;
            }

            .slide-content h2 {
                font-size: 1.5rem;
            }

            .slide-content p {
                font-size: 0.9rem;
            }

            .slider-arrow {
                display: none;
            }

            .slider-container {
                aspect-ratio: 16 / 9;
            }
        }

        @media (max-width: 480px) {
            .category-grid {
                grid-template-columns: 1fr;
            }

            .logo-text h1 {
                font-size: 1rem;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- ========== TOP BAR ========== -->
    <div class="top-bar">
        <div class="container">
            <div><i class="fas fa-phone-alt"></i> +91-9835634422 &nbsp; | &nbsp; <i class="fas fa-envelope"></i> info@rkvitsolutions.com</div>
            <div><i class="fas fa-map-pin"></i> Patna, Bihar &nbsp; | &nbsp; <a href="#"><i class="fas fa-user"></i> Login</a></div>
        </div>
    </div>

    <!-- ========== NAVBAR ========== -->
    <header class="navbar">
        <div class="container">
            <div class="logo-area">
                <!-- Logo icon + text (logo image reference: using styled text for reliability) -->
                <div class="logo-img">R</div>
                <div class="logo-text">
                    <h1>RKVIT REALESTATE</h1>
                    <span>PRIVATE LIMITED</span>
                </div>
            </div>
            <div class="nav-links">
                <!-- <a href="#" class="active">Dashboard</a> -->
                <a href="#">Buy</a>
                <a href="#">Rent</a>
                <a href="#">Sell</a>
                <a href="#">Contact</a>
                @auth
                <a href="{{ route('public.post-property') }}" class="btn-post">
                    <i class="fas fa-plus-circle"></i> Post Property <span style="font-weight:400; font-size:0.75rem;">FREE</span>
                </a>
                <a href="{{ route('customer.dashboard') }}" class="btn-post" style="background:var(--secondary);">
                    <i class="fas fa-user"></i> Dashboard
                </a>
                @else
                <a href="{{ route('public.post-property') }}" class="btn-post">
                    <i class="fas fa-plus-circle"></i> Post Property <span style="font-weight:400; font-size:0.75rem;">FREE</span>
                </a>
                @endauth

            </div>
        </div>
    </header>

    <!-- ========== BANNER SLIDER (5 images) ========== -->
    <section class="slider-section">
        <div class="container">
            <div class="slider-container">
                <div class="slides" id="slides">
                    <!-- Slide 1 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=500&fit=crop&auto=format" alt="Luxury home">
                        <div class="slide-content">
                            <h2>Find Your Dream Dashboard</h2>
                            <p>Explore premium properties in Patna & across India</p>
                            <span class="slide-btn">Explore Now →</span>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1200&h=500&fit=crop&auto=format" alt="Modern apartment">
                        <div class="slide-content">
                            <h2>Ready to Move Flats</h2>
                            <p>Zero brokerage · Verified listings · Best prices</p>
                            <span class="slide-btn">View Properties →</span>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&h=500&fit=crop&auto=format" alt="Villa">
                        <div class="slide-content">
                            <h2>Luxury Villas & Plots</h2>
                            <p>Premium gated communities with modern amenities</p>
                            <span class="slide-btn">Discover More →</span>
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&h=500&fit=crop&auto=format" alt="Commercial space">
                        <div class="slide-content">
                            <h2>Commercial Spaces</h2>
                            <p>Office, retail & warehouse spaces for your business</p>
                            <span class="slide-btn">Enquire Now →</span>
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&h=500&fit=crop&auto=format" alt="Interior">
                        <div class="slide-content">
                            <h2>Trusted Real Estate Partner</h2>
                            <p>RKVIT REALESTATE – Your property journey starts here</p>
                            <span class="slide-btn">Contact Us →</span>
                        </div>
                    </div>
                </div>
                <!-- slider controls (static) -->
                <div class="slider-arrow arrow-left"><i class="fas fa-chevron-left"></i></div>
                <div class="slider-arrow arrow-right"><i class="fas fa-chevron-right"></i></div>
                <div class="slider-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SEARCH BAR ========== -->
    <div class="search-section">
        <div class="container">
            <div class="search-card">
                <div class="search-tabs">
                    <span class="search-tab active">Buy</span>
                    <span class="search-tab">Rent</span>
                    <span class="search-tab">Commercial</span>
                </div>
                <input class="search-input" type="text" placeholder="Enter city, locality or project name...">
                <span class="search-btn"><i class="fas fa-search"></i> Search</span>
            </div>
        </div>
    </div>

    <!-- ========== CATEGORY / QUICK LINKS ========== -->
    <section class="category-section">
        <div class="container">
            <div class="section-header">
                <h2>Explore Real Estate</h2>
                <a href="#">View All →</a>
            </div>
            <div class="category-grid">
                <div class="category-card"><i class="fas fa-building"></i><span>Apartments</span></div>
                <div class="category-card"><i class="fas fa-home"></i><span>Independent House</span></div>
                <div class="category-card"><i class="fas fa-tree"></i><span>Villas</span></div>
                <div class="category-card"><i class="fas fa-map"></i><span>Plots</span></div>
                <div class="category-card"><i class="fas fa-briefcase"></i><span>Office Spaces</span></div>
                <div class="category-card"><i class="fas fa-store"></i><span>Shops</span></div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED PROPERTIES ========== -->
    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2>Featured Properties</h2>
                <a href="#">View All →</a>
            </div>
            <div class="property-grid">
                <!-- Property 1 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=600&h=400&fit=crop&auto=format" alt="Property">
                        <span class="property-tag">Featured</span>
                    </div>
                    <div class="property-info">
                        <h3>3 BHK Luxury Apartment</h3>
                        <div class="property-location"><i class="fas fa-map-marker-alt"></i> Kankarbagh, Patna</div>
                        <div class="property-price">₹ 85 Lakhs</div>
                        <div class="property-meta">
                            <span><i class="fas fa-bed"></i> 3 Beds</span>
                            <span><i class="fas fa-bath"></i> 3 Baths</span>
                            <span><i class="fas fa-vector-square"></i> 1650 sqft</span>
                        </div>
                    </div>
                </div>
                <!-- Property 2 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=600&h=400&fit=crop&auto=format" alt="Property">
                        <span class="property-tag">New</span>
                    </div>
                    <div class="property-info">
                        <h3>4 BHK Premium Villa</h3>
                        <div class="property-location"><i class="fas fa-map-marker-alt"></i> Bailey Road, Patna</div>
                        <div class="property-price">₹ 2.1 Cr</div>
                        <div class="property-meta">
                            <span><i class="fas fa-bed"></i> 4 Beds</span>
                            <span><i class="fas fa-bath"></i> 4 Baths</span>
                            <span><i class="fas fa-vector-square"></i> 2800 sqft</span>
                        </div>
                    </div>
                </div>
                <!-- Property 3 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=600&h=400&fit=crop&auto=format" alt="Property">
                        <span class="property-tag">Hot Deal</span>
                    </div>
                    <div class="property-info">
                        <h3>2 BHK Modern Flat</h3>
                        <div class="property-location"><i class="fas fa-map-marker-alt"></i> Rajendra Nagar, Patna</div>
                        <div class="property-price">₹ 52 Lakhs</div>
                        <div class="property-meta">
                            <span><i class="fas fa-bed"></i> 2 Beds</span>
                            <span><i class="fas fa-bath"></i> 2 Baths</span>
                            <span><i class="fas fa-vector-square"></i> 1100 sqft</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== TRUST / WHY CHOOSE US ========== -->
    <section class="trust-section">
        <div class="container">
            <div class="section-header" style="justify-content:center; text-align:center; display:block; margin-bottom:40px;">
                <h2 style="font-size:1.8rem;">Why Choose RKVIT REALESTATE?</h2>
            </div>
            <div class="trust-grid">
                <div class="trust-item">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Verified Listings</h4>
                    <p>100% verified properties with authentic documentation</p>
                </div>
                <div class="trust-item">
                    <i class="fas fa-hand-holding-usd"></i>
                    <h4>Zero Brokerage</h4>
                    <p>Direct owner deals & transparent pricing</p>
                </div>
                <div class="trust-item">
                    <i class="fas fa-headset"></i>
                    <h4>24/7 Support</h4>
                    <p>Dedicated relationship manager for every client</p>
                </div>
                <div class="trust-item">
                    <i class="fas fa-file-signature"></i>
                    <h4>Legal Assistance</h4>
                    <p>Complete help with agreements & registration</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CONTACT STRIP ========== -->
    <section class="contact-strip">
        <div class="container">
            <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <strong>+91-9835634422</strong>
                    <span>Mon-Sat 9:00 AM - 8:00 PM</span>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <strong>info@rkvitsolutions.com</strong>
                    <span>Online support 24/7</span>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <strong>Yash Tower, Patna</strong>
                    <span>25/101, Ward No.-1, Opp. St. Xavier's, Patna 800011</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-area" style="gap:10px;">
                        <div class="logo-img" style="width:44px; height:44px; font-size:1.3rem;">R</div>
                        <div class="logo-text">
                            <h1 style="color:white; font-size:1.1rem;">RKVIT REALESTATE</h1>
                            <span style="color:#94a3b8;">PRIVATE LIMITED</span>
                        </div>
                    </div>
                    <p>Your trusted real estate partner in Patna & beyond. We help you buy, sell, and rent properties with complete transparency.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div>
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Buy Property</a></li>
                        <li><a href="#">Rent Property</a></li>
                        <li><a href="#">Post Property</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Explore</h5>
                    <ul>
                        <li><a href="#">Apartments</a></li>
                        <li><a href="#">Villas</a></li>
                        <li><a href="#">Plots</a></li>
                        <li><a href="#">Commercial</a></li>
                        <li><a href="#">PG / Hostels</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Contact</h5>
                    <ul style="color:#94a3b8;">
                        <li><i class="fas fa-map-marker-alt" style="color:var(--primary); width:18px;"></i> Yash Tower, 25/101, Ward No.-1, P.S.-1, Opp. St. Xavier's, Patna 800011, Bihar, India</li>
                        <li><i class="fas fa-phone-alt" style="color:var(--primary); width:18px;"></i> +91-9835634422</li>
                        <li><i class="fas fa-envelope" style="color:var(--primary); width:18px;"></i> info@rkvitsolutions.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>© 2025 RKVIT REALESTATE PRIVATE LIMITED. All rights reserved.</div>
                <div class="designer"><i class="fas fa-code"></i> Designed by: RKV IT Solutions Pvt. Ltd.</div>
            </div>
        </div>
    </footer>

    <!-- ===== simple static slider (auto-rotate with CSS/JS only for presentation) ===== -->
    <script>
        (function() {
            // simple auto-advance for slider (visual only)
            const slidesContainer = document.getElementById('slides');
            const dots = document.querySelectorAll('.dot');
            let currentIndex = 0;
            const totalSlides = 5;
            let interval;

            function updateSlider(index) {
                if (!slidesContainer) return;
                slidesContainer.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
                currentIndex = index;
            }

            function nextSlide() {
                let next = (currentIndex + 1) % totalSlides;
                updateSlider(next);
            }

            function startAuto() {
                if (interval) clearInterval(interval);
                interval = setInterval(nextSlide, 5000);
            }

            // optional: click on dots (non-functional but adds interactivity)
            dots.forEach((dot, idx) => {
                dot.addEventListener('click', () => {
                    updateSlider(idx);
                    startAuto(); // reset timer
                });
            });

            // start auto-rotation
            startAuto();

            // pause on hover for better UX
            const sliderContainer = document.querySelector('.slider-container');
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', () => clearInterval(interval));
                sliderContainer.addEventListener('mouseleave', startAuto);
            }
        })();
    </script>

</body>

</html>