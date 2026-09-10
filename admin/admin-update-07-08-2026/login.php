<?php
session_start();
include 'conn.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_email'])) {
	header("Location: index.php");
	exit();
}

function test_input($data)
{
	return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = test_input($_POST["email"]);
	$password = test_input($_POST["password"]);

	// Check Admin Data
	$stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$admin = $result->fetch_assoc();

	if ($admin && password_verify($password, $admin['password'])) {
		$_SESSION['admin_email'] = $admin['email'];
		$_SESSION['admin_name'] = $admin['full_name'];
		$_SESSION['admin_role'] = $admin['role'];
		$_SESSION['admin_id'] = $admin['id'];
		header("Location: index.php");
		exit();
	} else {
		$error_message = "Invalid Email or Password";
	}
}
?>


<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<!-- All Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignLab">
	<meta name="robots" content="">
	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Page Title Here -->
	<title>Sharnay Dashboard</title>

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/admin-standard.css" rel="stylesheet">

	<style>
		/* Password toggle styles */
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
			transition: all 0.2s ease;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.password-toggle-btn:hover {
			background: #f1f3f5;
			color: #344054;
		}

		.password-toggle-btn:focus {
			outline: none;
			box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.3);
		}

		.password-toggle-btn .material-symbols-outlined {
			font-size: 24px;
			font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48;
		}

		.password-toggle-btn .material-symbols-outlined.filled {
			font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;
		}

		/* Error message styling */
		.alert-error {
			background: #fee2e2;
			color: #991b1b;
			border: 1px solid #fecaca;
			border-radius: 12px;
			padding: 12px 16px;
			margin-bottom: 16px;
			font-size: 14px;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.alert-error .material-symbols-outlined {
			font-size: 20px;
		}

		.authincation-content {
			width: 100%;
			max-width: 420px;
		}

		@media (max-width: 576px) {
			.authincation-content {
				padding: 0 15px;
			}
		}
	</style>
</head>

<body class="body h-100">
	<div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
		<div class="login-aside text-center d-flex flex-column flex-row-auto">
			<div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
				<div class="text-center mb-lg-4 mb-2 pt-5 logo">
					<img src="images/logo.png" alt="">
				</div>
				<h3 class="mb-2 text-white">Welcome to Sharny Institute!</h3>
			</div>
			<div class="aside-image position-relative" style="background-image:url(images/background/pic-2.png);">
			</div>
		</div>
		<div class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
			<div class="d-flex justify-content-center h-100 align-items-center">
				<div class="authincation-content style-2">
					<div class="row no-gutters">
						<div class="col-xl-12 tab-content">
							<div id="sign-up" class="auth-form tab-pane fade show active form-validation">
								<form action="" method="POST" id="loginForm">
									<div class="text-center mb-4">
										<h3 class="text-center mb-2 text-black">Sign In</h3>
									</div>

									<div class="sepertor">
										<span class="d-block mb-4 fs-13">With email</span>
									</div>

									<?php if (isset($error_message)): ?>
										<div class="alert-error">
											<span class="material-symbols-outlined">error</span>
											<span><?php echo htmlspecialchars($error_message); ?></span>
										</div>
									<?php endif; ?>

									<div class="mb-3">
										<label for="email" class="form-label mb-2 fs-13 label-color font-w500">Email address</label>
										<input type="email" name="email" id="email" class="form-control" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
									</div>

									<div class="mb-3">
										<label for="password" class="form-label mb-2 fs-13 label-color font-w500">Password</label>
										<div class="password-toggle-wrapper">
											<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
											<button type="button" class="password-toggle-btn" id="togglePassword" aria-label="Toggle password visibility">
												<span class="material-symbols-outlined" id="eyeIcon">visibility</span>
											</button>
										</div>
									</div>

									<!-- <div class="mb-3 d-flex justify-content-between align-items-center">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="rememberMe">
											<label class="form-check-label" for="rememberMe">Remember Me</label>
										</div>
										<a href="forgot-password.php" class="text-primary" style="font-size: 14px;">Forgot Password?</a>
									</div> -->

									<button type="submit" class="btn btn-block btn-primary">Sign In</button>

									<!-- <div class="text-center mt-3">
										<span class="fs-13">Don't have an account? <a href="register.php" class="text-primary">Register</a></span>
									</div> -->
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--**********************************
        Scripts
    ***********************************-->
	<!-- Required vendors -->
	<script src="vendor/global/global.min.js"></script>
	<script src="js/custom.min.js"></script>
	<script src="js/dlabnav-init.js"></script>
	<script src="js/admin-standard.js"></script>

	<script>
		// Password visibility toggle functionality
		document.addEventListener('DOMContentLoaded', function() {
			const togglePassword = document.getElementById('togglePassword');
			const passwordInput = document.getElementById('password');
			const eyeIcon = document.getElementById('eyeIcon');

			if (togglePassword && passwordInput && eyeIcon) {
				togglePassword.addEventListener('click', function() {
					// Toggle the type attribute
					const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
					passwordInput.setAttribute('type', type);

					// Toggle the icon
					if (type === 'text') {
						eyeIcon.textContent = 'visibility_off';
						eyeIcon.classList.add('filled');
					} else {
						eyeIcon.textContent = 'visibility';
						eyeIcon.classList.remove('filled');
					}
				});

				// Optional: Allow pressing Enter to submit form
				passwordInput.addEventListener('keypress', function(e) {
					if (e.key === 'Enter') {
						document.getElementById('loginForm').submit();
					}
				});

				emailInput.addEventListener('keypress', function(e) {
					if (e.key === 'Enter') {
						document.getElementById('loginForm').submit();
					}
				});
			}
		});
	</script>

</body>

</html>