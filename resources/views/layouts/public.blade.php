<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Post Property - RKVIT REALESTATE')</title>
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
            --border: #e2e8f0;
            --white: #fff;
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 6px 18px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.08);
            --radius: 12px;
        }

        body {
            background: #f5f7fa;
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

        /* Top bar */
        .top-bar {
            background: var(--secondary);
            color: #cbd5e1;
            font-size: 0.82rem;
            padding: 8px 0;
        }

        .top-bar .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
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

        /* Navbar */
        .navbar {
            background: #fff;
            box-shadow: var(--shadow-sm);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
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
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.3rem;
        }

        .logo-text h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: -0.01em;
        }

        .logo-text span {
            font-size: 0.65rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-links a {
            font-weight: 500;
            font-size: 0.9rem;
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
        }

        .btn-nav-solid:hover {
            background: var(--primary-dark);
        }

        /* Content */
        .page-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-soft);
            font-size: 1rem;
        }

        /* Footer */
        .site-footer {
            background: #0f1a26;
            color: #94a3b8;
            padding: 32px 0 20px;
            margin-top: 60px;
            font-size: 0.85rem;
        }

        .site-footer .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 32px;
            margin-bottom: 24px;
        }

        .site-footer h5 {
            color: #fff;
            font-size: 0.9rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .site-footer ul li {
            margin-bottom: 6px;
        }

        .site-footer a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid #1e2b3c;
            padding-top: 16px;
            text-align: center;
            font-size: 0.78rem;
        }

        .footer-bottom .designer {
            color: #cbd5e1;
            margin-top: 6px;
        }

        .footer-bottom .designer i {
            color: var(--primary);
        }

        @media (max-width:768px) {
            .navbar .container {
                flex-direction: column;
                gap: 12px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div>
                <i class="fas fa-phone-alt"></i> +91-9835634422 &nbsp; | &nbsp;
                <i class="fas fa-envelope"></i> info@rkvitsolutions.com
            </div>
            <div>
                <i class="fas fa-map-pin"></i> Patna, Bihar
            </div>
        </div>
    </div>

    <!-- Navbar -->
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
                <a href="#">Buy</a>
                <a href="#">Rent</a>
                <a href="{{ route('public.post-property') }}" class="{{ request()->routeIs('public.post-property') ? 'active' : '' }}">Post Property</a>

                @auth
                <a href="{{ route('customer.dashboard') }}" class="btn-nav btn-nav-outline">
                    <i class="fas fa-user"></i> Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-nav btn-nav-solid">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn-nav btn-nav-outline">Login</a>
                <a href="{{ route('register') }}" class="btn-nav btn-nav-solid">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="logo-area" style="margin-bottom:12px;">
                        <div class="logo-img" style="width:36px; height:36px; font-size:1.1rem;">R</div>
                        <div class="logo-text">
                            <h1 style="color:#fff; font-size:1rem;">RKVIT REALESTATE</h1>
                            <span style="color:#94a3b8;">PRIVATE LIMITED</span>
                        </div>
                    </div>
                    <p style="max-width:320px;">Your trusted real estate partner in Patna & beyond. Buy, sell, and rent properties with complete transparency.</p>
                </div>
                <div>
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="#">Buy</a></li>
                        <li><a href="#">Rent</a></li>
                        <li><a href="{{ route('public.post-property') }}">Post Property</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Contact</h5>
                    <ul>
                        <li><i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> Yash Tower, 25/101, Ward No.-1, Patna 800011</li>
                        <li><i class="fas fa-phone-alt" style="color:var(--primary);"></i> +91-9835634422</li>
                        <li><i class="fas fa-envelope" style="color:var(--primary);"></i> info@rkvitsolutions.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>© {{ date('Y') }} RKVIT REALESTATE PRIVATE LIMITED. All rights reserved.</div>
                <div class="designer"><i class="fas fa-code"></i> Designed by: RKV IT Solutions Pvt. Ltd.</div>
            </div>
        </div>
    </footer>

</body>

</html>