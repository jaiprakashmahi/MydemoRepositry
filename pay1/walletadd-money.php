<?php
session_start();
require_once '../config/database.php';
require_once '../classes/AtomPaymentGateway.php';

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    
    // Validate amount
    if ($amount < 10 || $amount > 10000) {
        $error = 'Amount must be between ₹10 and ₹10,000';
    } else {
        try {
            $gateway = new AtomPaymentGateway($db);
            
            // Customer details (optional based on Atom requirements)
            $customerDetails = [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'phone' => $_SESSION['user_phone'] ?? '',
                'account_no' => $_SESSION['user_id']
            ];
            
            // Initiate payment and get redirect form
            $paymentForm = $gateway->initiatePayment($userId, $amount, $customerDetails);
            
            // Output auto-submit form
            echo $paymentForm;
            exit;
            
        } catch (Exception $e) {
            $error = 'Payment initiation failed. Please try again.';
            error_log('Atom Payment Error: ' . $e->getMessage());
        }
    }
}

// Get wallet balance
$gateway = new AtomPaymentGateway($db);
$walletBalance = $gateway->getWalletBalance($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Money to Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Money to Wallet</h4>
                    </div>
                    <div class="card-body">
                        <!-- Wallet Balance -->
                        <div class="alert alert-info">
                            Current Balance: ₹<?php echo number_format($walletBalance, 2); ?>
                        </div>
                        
                        <!-- Error Message -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <!-- Success Message -->
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>
                        
                        <!-- Add Money Form -->
                        <form method="POST" id="addMoneyForm" onsubmit="return validateAmount()">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Enter Amount (₹)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="amount" 
                                       name="amount" 
                                       min="10" 
                                       max="10000" 
                                       step="0.01" 
                                       placeholder="Enter amount in INR"
                                       required>
                                <div class="form-text">Min: ₹10 | Max: ₹10,000</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Quick Amounts</label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(100)">₹100</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(200)">₹200</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(500)">₹500</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(1000)">₹1000</button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <span id="btnText">Proceed to Pay</span>
                                <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
        }
        
        function validateAmount() {
            const amount = parseFloat(document.getElementById('amount').value);
            if (isNaN(amount) || amount < 10 || amount > 10000) {
                alert('Please enter a valid amount between ₹10 and ₹10,000');
                return false;
            }
            
            // Show loader
            document.getElementById('btnText').classList.add('d-none');
            document.getElementById('btnLoader').classList.remove('d-none');
            document.getElementById('submitBtn').disabled = true;
            
            return true;
        }
    </script>
</body>
</html>