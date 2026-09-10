<?php
// Set page title
$page_title = "Change Password";

// Include header
include 'header.php';

// Start session and check login
session_start();
include '../conn.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$error = '';
$success = '';

// Fetch student details for display
$query = "SELECT * FROM onlinestudents WHERE id = '$student_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current password hash
    $query = "SELECT password FROM onlinestudents WHERE id = '$student_id'";
    $result = mysqli_query($conn, $query);
    $student_data = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $student_data['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE onlinestudents SET password = '$new_password_hash' WHERE id = '$student_id'";

                if (mysqli_query($conn, $update_query)) {
                    $success = "Password changed successfully!";
                } else {
                    $error = "Error updating password: " . mysqli_error($conn);
                }
            } else {
                $error = "New password must be at least 6 characters long";
            }
        } else {
            $error = "New password and confirm password do not match";
        }
    } else {
        $error = "Current password is incorrect";
    }
}
?>

<style>
    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 45px;
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
        font-size: 18px;
        padding: 5px;
        transition: color 0.3s ease;
        z-index: 10;
    }

    .toggle-password:hover {
        color: #667eea;
    }

    .toggle-password:focus {
        outline: none;
    }

    .password-strength {
        margin-top: 8px;
        height: 4px;
        border-radius: 2px;
        background: #e1e5eb;
        transition: all 0.3s ease;
    }

    .password-strength.weak {
        background: #dc3545;
        width: 33%;
    }

    .password-strength.medium {
        background: #ffc107;
        width: 66%;
    }

    .password-strength.strong {
        background: #28a745;
        width: 100%;
    }

    .requirements-list {
        list-style: none;
        padding: 0;
        margin-top: 10px;
    }

    .requirements-list li {
        font-size: 13px;
        color: #6c757d;
        padding: 3px 0;
    }

    .requirements-list li i {
        margin-right: 8px;
        font-size: 12px;
    }

    .requirements-list li.valid {
        color: #28a745;
    }

    .requirements-list li.invalid {
        color: #dc3545;
    }

    .form-control-static {
        padding: 10px 0;
        margin-bottom: 0;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 30px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 10px 30px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .alert {
        border-radius: 10px;
        padding: 15px 20px;
        border: none;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #333;
        font-weight: 700;
    }

    .page-header p {
        color: #6c757d;
    }
</style>

<main class="main-content">
    <div class="page-header">
        <h1 class="h3 mb-2"><i class="fas fa-key me-2"></i>Change Password</h1>
        <p class="text-muted mb-0">Update your password to keep your account secure</p>
    </div>

    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <?php
                    $photo_path = !empty($student['photo']) ? "../uploads/students/photos/" . $student['photo'] : "https://ui-avatars.com/api/?name=" . urlencode($student['name']) . "&background=667eea&color=fff&size=200";
                    ?>
                    <img src="<?php echo $photo_path; ?>"
                        class="profile-picture mb-3"
                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($student['name']); ?>&background=667eea&color=fff&size=200'"
                        alt="Profile Picture">
                    <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($student['student_code']); ?></p>

                    <hr>

                    <div class="text-start">
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> <?php echo htmlspecialchars($student['email']); ?></p>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i> <?php echo htmlspecialchars($student['mobile']); ?></p>
                        <p class="mb-0"><i class="fas fa-graduation-cap me-2 text-primary"></i> <?php echo htmlspecialchars($student['course_name']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <!-- <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="profile.php" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                        <a href="change-password.php" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Change Password
                        </a>
                        <a href="logout.php" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Change Password Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4"><i class="fas fa-shield-alt me-2 text-primary"></i>Change Password</h5>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="passwordForm">
                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Current Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="form-control"
                                    placeholder="Enter your current password"
                                    required>
                                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key me-2"></i>New Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="form-control"
                                    placeholder="Enter new password (min 6 characters)"
                                    required
                                    minlength="6"
                                    onkeyup="checkPasswordStrength(this.value)">
                                <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength"></div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Password must be at least 6 characters long
                            </small>

                            <!-- Password Requirements -->
                            <ul class="requirements-list" id="requirementsList">
                                <li id="reqLength" class="invalid">
                                    <i class="fas fa-times"></i> At least 6 characters
                                </li>
                                <li id="reqLowercase" class="invalid">
                                    <i class="fas fa-times"></i> Contains lowercase letter
                                </li>
                                <li id="reqUppercase" class="invalid">
                                    <i class="fas fa-times"></i> Contains uppercase letter
                                </li>
                                <li id="reqNumber" class="invalid">
                                    <i class="fas fa-times"></i> Contains number
                                </li>
                            </ul>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-check-circle me-2"></i>Confirm New Password
                            </label>
                            <div class="password-wrapper">
                                <input type="password"
                                    name="confirm_password"
                                    id="confirm_password"
                                    class="form-control"
                                    placeholder="Confirm your new password"
                                    required
                                    onkeyup="checkPasswordMatch()">
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMatchMessage" style="font-size: 13px; margin-top: 5px;"></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Change Password
                        </button>

                        <a href="profile.php" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.parentElement.querySelector('.toggle-password');
        const icon = button.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    // Check password strength and requirements
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');

        // Check requirements
        const hasLength = password.length >= 6;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);

        // Update requirements list
        updateRequirement('reqLength', hasLength);
        updateRequirement('reqLowercase', hasLowercase);
        updateRequirement('reqUppercase', hasUppercase);
        updateRequirement('reqNumber', hasNumber);

        // Calculate strength
        let strength = 0;
        if (hasLength) strength++;
        if (hasLowercase) strength++;
        if (hasUppercase) strength++;
        if (hasNumber) strength++;

        // Update strength bar
        strengthBar.className = 'password-strength';
        if (password.length === 0) {
            strengthBar.style.width = '0';
        } else if (strength <= 1) {
            strengthBar.classList.add('weak');
        } else if (strength <= 2) {
            strengthBar.classList.add('medium');
        } else {
            strengthBar.classList.add('strong');
        }
    }

    // Update requirement status
    function updateRequirement(id, isValid) {
        const element = document.getElementById(id);
        const icon = element.querySelector('i');

        if (isValid) {
            element.className = 'valid';
            icon.className = 'fas fa-check-circle';
        } else {
            element.className = 'invalid';
            icon.className = 'fas fa-times-circle';
        }
    }

    // Check if passwords match
    function checkPasswordMatch() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const message = document.getElementById('passwordMatchMessage');

        if (confirmPassword.length > 0) {
            if (newPassword === confirmPassword) {
                message.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Passwords match!';
                message.style.color = '#28a745';
            } else {
                message.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Passwords do not match';
                message.style.color = '#dc3545';
            }
        } else {
            message.innerHTML = '';
        }
    }

    // Form validation before submit
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New password and confirm password do not match!');
            return false;
        }

        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long!');
            return false;
        }
    });

    // Auto-check password match on new password change
    document.getElementById('new_password').addEventListener('keyup', function() {
        checkPasswordMatch();
    });
</script>

<?php
// Include footer
include 'footer.php';
?>