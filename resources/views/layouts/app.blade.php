<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RKVIT REALESTATE PRIVATE LIMITED')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f7f9fc;
            color: #1e2b3c;
            line-height: 1.5;
            min-height: 100vh;
        }

        :root {
            --primary: #d8232a;
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
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            background-color: var(--secondary);
            color: #cbd5e1;
            font-size: 0.85rem;
            padding: 8px 0;
            border-bottom: 1px solid #3a4a5a;
        }

        .top-bar .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
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
            margin-left: 20px;
            transition: color 0.2s;
        }

        .top-bar a:hover {
            color: white;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-sm);
            padding: 16px 0;
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
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
        }

        .btn-nav {
            padding: 10px 22px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background: transparent;
            color: var(--primary) !important;
            border: 2px solid var(--primary);
        }

        .btn-login:hover {
            background: var(--primary);
            color: white !important;
        }

        .btn-register {
            background-color: var(--primary);
            color: white !important;
            box-shadow: 0 4px 10px rgba(216, 35, 42, 0.25);
        }

        .btn-register:hover {
            background-color: var(--primary-dark);
        }

        /* ===== AUTH CONTAINER ===== */
        .auth-wrapper {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: linear-gradient(135deg, #f7f9fc 0%, #eef2f6 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(216, 35, 42, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-wrapper::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(44, 62, 80, 0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 460px;
            padding: 44px 40px;
            position: relative;
            z-index: 1;
            border: 1px solid #e9eef3;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d8232a, #a01820);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0 auto 18px;
            box-shadow: 0 8px 20px rgba(216, 35, 42, 0.25);
        }

        .auth-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .auth-header p {
            color: var(--text-soft);
            font-size: 0.95rem;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            color: var(--text-dark);
            transition: all 0.2s;
            background: #fafbfc;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(216, 35, 42, 0.08);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 44px;
        }

        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-medium);
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .input-icon:hover {
            color: var(--primary);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .invalid-feedback::before {
            content: '\f06a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 6px 16px rgba(216, 35, 42, 0.3);
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(216, 35, 42, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* ===== CHECKBOX & EXTRAS ===== */
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            color: var(--text-soft);
            cursor: pointer;
        }

        .link-primary {
            color: var(--primary);
            font-weight: 600;
            transition: color 0.2s;
        }

        .link-primary:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-info {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 26px 0;
            color: var(--gray-medium);
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ===== AUTH FOOTER ===== */
        .auth-footer {
            text-align: center;
            margin-top: 26px;
            font-size: 0.9rem;
            color: var(--text-soft);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .terms-text {
            font-size: 0.8rem;
            color: var(--gray-medium);
            text-align: center;
            margin-top: 18px;
            line-height: 1.5;
        }

        /* ===== PAGE FOOTER ===== */
        .page-footer {
            background: #0f1a26;
            color: #94a3b8;
            padding: 24px 0;
            font-size: 0.85rem;
            text-align: center;
        }

        .page-footer .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .page-footer .designer {
            margin-top: 8px;
            color: #cbd5e1;
        }

        .page-footer .designer i {
            color: var(--primary);
            margin-right: 4px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                gap: 14px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 14px;
            }

            .top-bar .container {
                justify-content: center;
                text-align: center;
            }

            .auth-card {
                padding: 32px 24px;
            }

            .auth-header h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .auth-wrapper {
                padding: 24px 16px;
            }

            .logo-text h1 {
                font-size: 1rem;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* ===== SPINNER ===== */
        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <!-- ===== TOP BAR ===== -->
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

    <!-- ===== NAVBAR ===== -->
    <header class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="logo-area">
                <div class="logo-img">R</div>
                <div class="logo-text">
                    <h1>RKVIT REALESTATE</h1>
                    <span>PRIVATE LIMITED</span>
                </div>
            </a>
            <div class="nav-links">
                <a href="{{ url('/') }}">Home</a>
                <a href="#">Buy</a>
                <a href="#">Rent</a>
                <a href="#">Contact</a>

                @auth
                <a href="{{ route('dashboard') }}" class="btn-nav btn-register">
                    <i class="fas fa-user"></i> Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-nav btn-login">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn-nav btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn-nav btn-register">
                    <i class="fas fa-user-plus"></i> Register
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    @yield('content')

    <!-- ===== PAGE FOOTER ===== -->
    <footer class="page-footer">
        <div class="container">
            <div>© {{ date('Y') }} RKVIT REALESTATE PRIVATE LIMITED. All rights reserved.</div>
            <div class="designer">
                <i class="fas fa-code"></i> Designed by: RKV IT Solutions Pvt. Ltd.
            </div>
        </div>
    </footer>

    <script>
        // Password show/hide toggle
        document.querySelectorAll('.toggle-password').forEach(function(el) {
            el.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.target);
                if (!target) return;
                const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
                target.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>