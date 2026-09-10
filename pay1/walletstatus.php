<?php
session_start();
require_once '../config/database.php';
require_once '../classes/AtomPaymentGateway.php';

$transactionId = $_GET['txn'] ?? 0;
$status = $_GET['status'] ?? 'unknown';

// Get transaction details
$gateway = new AtomPaymentGateway($db);
$transaction = $gateway->getTransactionDetails($transactionId);
$walletBalance = $gateway->getWalletBalance($_SESSION['user_id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Payment Status</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($status === 'success'): ?>
                            <div class="text-center">
                                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                <h3 class="mt-3 text-success">Payment Successful!</h3>
                                <?php if ($transaction): ?>
                                    <p>Amount: ₹<?php echo number_format($transaction['amount'], 2); ?></p>
                                    <p>Transaction ID: <?php echo htmlspecialchars($transaction['merchant_txn_id']); ?></p>
                                <?php endif; ?>
                                <p>Updated Balance: ₹<?php echo number_format($walletBalance, 2); ?></p>
                            </div>
                        <?php elseif ($status === 'failed'): ?>
                            <div class="text-center">
                                <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                                <h3 class="mt-3 text-danger">Payment Failed</h3>
                                <p>Please try again with a different payment method.</p>
                            </div>
                        <?php elseif ($status === 'pending'): ?>
                            <div class="text-center">
                                <i class="bi bi-clock text-warning" style="font-size: 4rem;"></i>
                                <h3 class="mt-3 text-warning">Payment Pending</h3>
                                <p>Your payment is being processed. We'll update you soon.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="/wallet/add-money.php" class="btn btn-primary w-100">Add More Money</a>
                            <a href="/dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Go to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>