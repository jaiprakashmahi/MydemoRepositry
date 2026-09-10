<?php
/**
 * Cron job to reconcile pending transactions
 * Run every 30 minutes
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/AtomPaymentGateway.php';

$gateway = new AtomPaymentGateway($db);

// Get pending transactions older than 30 minutes
$stmt = $db->prepare("
    SELECT * FROM wallet_transactions 
    WHERE status = 'PENDING' 
    AND created_at < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
    LIMIT 50
");
$stmt->execute();
$pendingTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pendingTransactions as $transaction) {
    // Implement Atom status check API call if available
    error_log("Pending transaction found: " . $transaction['merchant_txn_id']);
}