<?php
session_start();
include '../conn.php';

$error = '';

// Already logged in
if (isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    if (is_numeric($username)) {
        $query = "SELECT * FROM onlinestudents WHERE mobile='$username'";
    } else {
        $query = "SELECT * FROM onlinestudents WHERE username='$username'";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);

        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            $_SESSION['student_photo'] = $student['photo'];
            $_SESSION['student_session_start'] = $student['session_start'];
            $_SESSION['student_session_end'] = $student['session_end'];

            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username or mobile number!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Login | Sharnay Institute</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
        }

        .auth-wrapper {
            display: flex;
            height: 100vh;
        }

        /* LEFT */
        .auth-left {
            width: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-content {
            text-align: center;
            animation: fadeIn 1.2s ease;
        }

        .left-content img {
            width: 500px;
            margin-bottom: 20px;
        }

        .left-content h1 {
            font-size: 2.8rem;
            font-weight: 700;
        }

        .left-content p {
            opacity: .9;
            font-size: 1.1rem;
        }

        .icons i {
            font-size: 2rem;
            margin: 15px;
            animation: float 3s infinite ease-in-out;
        }

        .icons i:nth-child(2) {
            animation-delay: .5s
        }

        .icons i:nth-child(3) {
            animation-delay: 1s
        }

        /* RIGHT */
        .auth-right {
            width: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 380px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .1);
        }

        .login-card h3 {
            font-weight: 700;
        }

        /* Password Toggle Styles */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 50px;
            height: 48px;
            border-radius: 8px;
            border: 2px solid #e1e5eb;
            transition: all 0.3s ease;
        }

        .password-wrapper input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            font-size: 20px;
            padding: 8px;
            transition: all 0.3s ease;
            border-radius: 50%;
            z-index: 10;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .toggle-password:focus {
            outline: none;
            color: #667eea;
            background: rgba(102, 126, 234, 0.15);
        }

        .toggle-password:active {
            transform: translateY(-50%) scale(0.95);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            height: 48px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-secondary:hover {
            background: #667eea;
            color: #fff;
            border-color: #667eea;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 15px;
            border: none;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e1e5eb;
            padding: 10px 15px;
            height: 48px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        /* Animations */
        @keyframes float {
            0% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-12px)
            }

            100% {
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* Mobile */
        @media(max-width:768px) {
            .auth-wrapper {
                flex-direction: column;
            }

            .auth-left,
            .auth-right {
                width: 100%;
            }

            .auth-left {
                padding: 40px 20px;
                min-height: 300px;
            }

            .auth-left .left-content img {
                width: 200px;
            }

            .auth-right {
                padding: 20px;
                min-height: 500px;
            }

            .login-card {
                padding: 25px;
            }
        }

        @media(max-width:480px) {
            .left-content img {
                width: 150px;
            }

            .left-content h1 {
                font-size: 2rem;
            }

            .login-card {
                padding: 20px;
            }

            .password-wrapper input {
                padding-right: 45px;
            }

            .toggle-password {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">

        <!-- LEFT PANEL -->
        <div class="auth-left">
            <div class="left-content">
                <img src="../flogo.png" alt="Institute Logo">
                <h1>Welcome Back</h1>
                <p>
                    Learn • Grow • Succeed<br>
                    Access your dashboard anytime
                </p>
                <div class="icons">
                    <i class="fas fa-graduation-cap"></i>
                    <i class="fas fa-book"></i>
                    <i class="fas fa-laptop"></i>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right">
            <div class="login-card">

                <h3 class="text-center mb-2">Student Login</h3>
                <p class="text-muted text-center mb-4">
                    Sharnay Institute Portal
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="loginForm">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user me-2"></i>Username / Mobile
                        </label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username or mobile" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()" title="Show/Hide Password">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>

                    <div class="text-center mb-3">
                        <a href="forgot_password.php" class="small">
                            <i class="fas fa-key me-1"></i>Forgot Password?
                        </a>
                    </div>

                    <hr>

                    <div class="text-center">
                        <span class="small">New Student?</span><br>
                        <a href="../student_reg.php" class="btn btn-outline-secondary mt-2">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility with icon button
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
                toggleIcon.parentElement.setAttribute('title', 'Hide Password');
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fas fa-eye';
                toggleIcon.parentElement.setAttribute('title', 'Show Password');
            }
        }

        // Optional: Prevent form submission if no username/password
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.querySelector('input[name="username"]').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) {
                e.preventDefault();
                alert('Please enter both username and password');
                return false;
            }
        });

        // Keyboard shortcut: Press Ctrl+Shift+P to toggle password
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && (e.key === 'P' || e.key === 'p')) {
                e.preventDefault();
                togglePassword();
            }
        });
    </script>

</body>

</html>