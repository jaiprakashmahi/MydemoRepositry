<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

// Function to sanitize input
function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = validate_input($_POST["current_password"]);
    $new_password = validate_input($_POST["new_password"]);
    $confirm_password = validate_input($_POST["confirm_password"]);

    $admin_id = $_SESSION['admin_id'];

    // Validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New password and confirm password do not match!";
    } elseif (strlen($new_password) < 6) {
        $error_message = "New password must be at least 6 characters long!";
    } else {
        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($current_password, $admin['password'])) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $update_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $admin_id);

            if ($update_stmt->execute()) {
                $success_message = "Password changed successfully!";

                // Log the password change
                error_log("Password changed for admin ID: " . $admin_id . " on " . date('Y-m-d H:i:s'));
            } else {
                $error_message = "Failed to change password. Please try again!";
            }
            $update_stmt->close();
        } else {
            $error_message = "Current password is incorrect!";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Password - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="vendor/wow-master/css/libs/animate.css" rel="stylesheet">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/admin-standard.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .page-wrapper-custom {
            padding: 24px;
        }

        .page-heading-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 18px;
            padding: 22px 26px;
            color: #fff;
            margin-bottom: 24px;
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.22);
        }

        .page-heading-card h4 {
            color: #fff;
            margin: 0;
            font-weight: 700;
        }

        .page-heading-card p {
            margin: 6px 0 0;
            opacity: .9;
        }

        .content-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(16, 24, 40, 0.06);
            border: 1px solid #edf0f5;
            margin-bottom: 22px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .password-box {
            background: #f8f9fc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 10px;
        }

        .password-box .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #344054;
            margin-bottom: 7px;
        }

        .password-box .form-control {
            height: 48px;
            border-radius: 12px;
            border: 1px solid #d0d5dd;
            box-shadow: none;
            font-size: 15px;
            padding: 10px 16px;
            transition: all 0.2s ease;
        }

        .password-box .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .10);
        }

        .password-box .input-gap {
            margin-bottom: 20px;
        }

        .password-hint {
            font-size: 12px;
            color: #667085;
            margin-top: 6px;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .btn-rounded {
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 15px;
        }

        .btn-change-password {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-change-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
            color: #fff;
        }

        .btn-change-password:active {
            transform: translateY(0);
        }

        .alert-custom {
            border-radius: 12px;
            padding: 16px 20px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-custom.alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-custom.alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667085;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.2s ease;
            margin-top: 15px;
        }

        .back-link:hover {
            color: #4f46e5;
        }

        .back-link .material-icons {
            font-size: 20px;
        }

        .security-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .security-icon .material-icons {
            font-size: 36px;
            color: #4f46e5;
        }

        @media (max-width: 768px) {
            .page-wrapper-custom {
                padding: 14px;
            }

            .page-heading-card {
                padding: 18px;
            }

            .content-card {
                padding: 20px;
            }

            .password-box {
                padding: 18px;
            }

            .btn-rounded {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        /* Toggle password visibility */
        .password-toggle-wrapper {
            position: relative;
        }

        .password-toggle-wrapper .form-control {
            padding-right: 48px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #667085;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .password-toggle-btn:hover {
            background: #f1f3f5;
        }

        .password-toggle-btn .material-icons {
            font-size: 22px;
        }

        .input-group-text-custom {
            background: #f8f9fc;
            border: 1px solid #d0d5dd;
            border-radius: 12px 0 0 12px;
            padding: 0 16px;
            color: #667085;
        }
    </style>
</head>

<body>
    <div id="main-wrapper">

        <?php include 'menu.php'; ?>

        <div class="content-body">
            <div class="container-fluid page-wrapper-custom">

                <div class="page-heading-card">
                    <h4>🔐 Change Password</h4>
                    <p>Update your password to keep your account secure.</p>
                </div>

                <?php if ($success_message) { ?>
                    <div class="alert alert-custom alert-success" style="max-width:600px;margin:0 auto 20px;">
                        <?= htmlspecialchars($success_message); ?>
                    </div>
                <?php } ?>

                <?php if ($error_message) { ?>
                    <div class="alert alert-custom alert-danger" style="max-width:600px;margin:0 auto 20px;">
                        <?= htmlspecialchars($error_message); ?>
                    </div>
                <?php } ?>

                <div class="content-card">
                    <div class="security-icon">
                        <span class="material-icons">security</span>
                    </div>

                    <h5 style="text-align:center;color:#344054;font-weight:700;margin-bottom:8px;">
                        Change Your Password
                    </h5>
                    <p style="text-align:center;color:#667085;font-size:14px;margin-bottom:24px;">
                        Enter your current password and choose a new strong password.
                    </p>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="change_password" value="1">

                        <div class="password-box">
                            <!-- Current Password -->
                            <div class="input-gap">
                                <label class="form-label">Current Password</label>
                                <div class="password-toggle-wrapper">
                                    <input type="password"
                                        id="current_password"
                                        name="current_password"
                                        class="form-control"
                                        placeholder="Enter your current password"
                                        required>
                                    <button type="button"
                                        class="password-toggle-btn"
                                        onclick="togglePassword('current_password', this)">
                                        <span class="material-icons">visibility</span>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="input-gap">
                                <label class="form-label">New Password</label>
                                <div class="password-toggle-wrapper">
                                    <input type="password"
                                        id="new_password"
                                        name="new_password"
                                        class="form-control"
                                        placeholder="Enter new password (min 6 characters)"
                                        required
                                        oninput="checkPasswordStrength(this.value)">
                                    <button type="button"
                                        class="password-toggle-btn"
                                        onclick="togglePassword('new_password', this)">
                                        <span class="material-icons">visibility</span>
                                    </button>
                                </div>
                                <div class="password-hint">
                                    Password must be at least 6 characters long
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                                <div id="strengthText" style="font-size:12px;margin-top:4px;color:#667085;"></div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="input-gap" style="margin-bottom:0;">
                                <label class="form-label">Confirm New Password</label>
                                <div class="password-toggle-wrapper">
                                    <input type="password"
                                        id="confirm_password"
                                        name="confirm_password"
                                        class="form-control"
                                        placeholder="Re-enter new password"
                                        required>
                                    <button type="button"
                                        class="password-toggle-btn"
                                        onclick="togglePassword('confirm_password', this)">
                                        <span class="material-icons">visibility</span>
                                    </button>
                                </div>
                                <div id="confirmMatch" style="font-size:12px;margin-top:4px;color:#667085;"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-change-password btn-rounded">
                            <span class="material-icons" style="font-size:20px;vertical-align:middle;margin-right:8px;">lock</span>
                            Update Password
                        </button>

                        <a href="index.php" class="back-link">
                            <span class="material-icons">arrow_back</span>
                            Back to Dashboard
                        </a>
                    </form>
                </div>

            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/dlabnav-init.js"></script>
    <script src="js/admin-standard.js"></script>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('.material-icons');

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // Check password strength
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');

            if (password.length === 0) {
                bar.style.width = '0%';
                bar.style.background = '#e5e7eb';
                text.textContent = '';
                return;
            }

            let strength = 0;

            // Length check
            if (password.length >= 6) strength += 1;
            if (password.length >= 10) strength += 1;

            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 1;

            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 1;

            // Contains numbers
            if (/\d/.test(password)) strength += 1;

            // Contains special characters
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;

            // Update bar
            const percentage = (strength / 6) * 100;
            bar.style.width = percentage + '%';

            // Set color and text
            if (strength <= 2) {
                bar.style.background = '#ef4444';
                text.textContent = 'Weak password';
                text.style.color = '#ef4444';
            } else if (strength <= 4) {
                bar.style.background = '#f59e0b';
                text.textContent = 'Medium password';
                text.style.color = '#f59e0b';
            } else {
                bar.style.background = '#22c55e';
                text.textContent = 'Strong password ✓';
                text.style.color = '#22c55e';
            }
        }

        // Confirm password matching
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = this.value;
            const matchText = document.getElementById('confirmMatch');

            if (confirmPass.length === 0) {
                matchText.textContent = '';
                return;
            }

            if (newPass === confirmPass) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#22c55e';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
            }
        });

        // Prevent form submission if passwords don't match
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (newPass !== confirmPass) {
                e.preventDefault();
                document.getElementById('confirmMatch').textContent = '✗ Passwords do not match';
                document.getElementById('confirmMatch').style.color = '#ef4444';
                document.getElementById('confirm_password').focus();
                return false;
            }

            if (newPass.length < 6) {
                e.preventDefault();
                document.getElementById('strengthText').textContent = 'Password must be at least 6 characters';
                document.getElementById('strengthText').style.color = '#ef4444';
                document.getElementById('new_password').focus();
                return false;
            }
        });
    </script>

</body>

</html>