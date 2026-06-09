<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Job Portal') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f8fafc;
        }

        .hero{
            background: linear-gradient(135deg,#0d6efd,#0b5ed7);
            color:white;
            padding:100px 0;
        }

        .job-card{
            border:none;
            border-radius:12px;
            transition:.3s;
        }

        .job-card:hover{
            transform:translateY(-5px);
            box-shadow:0 10px 30px rgba(0,0,0,.1);
        }

        .stat-box{
            background:white;
            border-radius:10px;
            padding:30px;
            text-align:center;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
        }

        footer{
            background:#212529;
            color:white;
            padding:20px 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold" href="/">
                Job Portal
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Companies</a>
                    </li>

                    @guest

                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}"
                               class="btn btn-outline-primary">
                                Login
                            </a>
                        </li>

                        <li class="nav-item ms-2">
                            <a href="{{ route('register') }}"
                               class="btn btn-primary">
                                Register
                            </a>
                        </li>

                    @else

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle"
                               href="#"
                               data-bs-toggle="dropdown">

                                {{ Auth::user()->name }}

                            </a>

                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('dashboard') }}">
                                        Dashboard
                                    </a>
                                </li>

                                 <li>
                                    <a class="dropdown-item"
                                       href="{{ route('profile.edit') }}">
                                        Profile
                                    </a>
                                </li>

                                <li>
                                    <form method="POST"
                                          action="{{ route('logout') }}">
                                        @csrf

                                        <button class="dropdown-item">
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </li>

                    @endguest

                </ul>

            </div>

        </div>
    </nav>

    @yield('content')

    <footer class="mt-5">
        <div class="container text-center">
            © {{ date('Y') }} Job Portal. All Rights Reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
