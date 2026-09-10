<?php
/**
 * Atom Payment Gateway Integration Class
 * Version: 2.0
 * Compatible with PHP 7.4+
 */
class AtomPaymentGateway {
    private $config;
    private $db;
    private $logger;
    private $transactionTable = 'wallet_transactions';
    private $walletTable = 'user_wallets';
    private $logTable = 'wallet_transaction_logs';
    
    /**
     * Constructor
     */
    public function __construct($db, $logger = null) {
        $this->db = $db;
        $this->logger = $logger;
        $this->config = require_once __DIR__ . '/../config/payment_gateway.php';
        $this->config = $this->config['atom'];
    }
    
    /**
     * Generate unique merchant transaction ID
     */
    private function generateMerchantTxnId($userId) {
        return 'ATOM_' . $userId . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4));
    }
    
    /**
     * Generate Atom hash for request
     */
    private function generateRequestHash($params) {
        $hashString = $this->config['login_id'] . 
                      $params['ttype'] . 
                      $params['tempTxnId'] . 
                      number_format($params['amount'], 2, '.', '') . 
                      $params['date'] . 
                      $this->config['request_hash_key'];
        
        return hash('sha512', $hashString);
    }
    
    /**
     * Verify response hash from Atom
     */
    public function verifyResponseHash($responseData) {
        if (!isset($responseData['signature'])) {
            $this->logError('Signature missing in response');
            return false;
        }
        
        $receivedSignature = $responseData['signature'];
        
        // Construct hash string based on Atom's latest format
        $hashString = $responseData['mmp_txn'] . 
                      $responseData['mer_txn'] . 
                      $responseData['f_code'] . 
                      $responseData['prod'] . 
                      $responseData['discriminator'] . 
                      number_format($responseData['amt'], 2, '.', '') . 
                      $responseData['bank_txn'] . 
                      $this->config['response_hash_key'];
        
        $calculatedSignature = hash('sha512', $hashString);
        
        $isValid = hash_equals($calculatedSignature, $receivedSignature);
        
        if (!$isValid) {
            $this->logError('Hash verification failed', [
                'received' => $receivedSignature,
                'calculated' => $calculatedSignature,
                'data' => $responseData
            ]);
        }
        
        return $isValid;
    }
    
    /**
     * Initiate payment request
     */
    public function initiatePayment($userId, $amount, $customerDetails = []) {
        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Validate amount
            if ($amount <= 0) {
                throw new Exception('Invalid amount');
            }
            
            // Generate unique transaction IDs
            $merchantTxnId = $this->generateMerchantTxnId($userId);
            $transactionDate = date('d/m/Y H:i:s');
            
            // Create pending transaction in database
            $stmt = $this->db->prepare("
                INSERT INTO {$this->transactionTable} 
                (user_id, merchant_txn_id, amount, currency, status, created_at) 
                VALUES (?, ?, ?, 'INR', 'PENDING', NOW())
            ");
            
            $stmt->execute([$userId, $merchantTxnId, $amount]);
            $transactionId = $this->db->lastInsertId();
            
            // Prepare Atom payment request parameters
            $atomParams = [
                'login' => $this->config['login_id'],
                'pass' => $this->config['password'],
                'ttype' => 'NBFundTransfer',
                'prodid' => $this->config['product_id'],
                'amt' => number_format($amount, 2, '.', ''),
                'txncurr' => 'INR',
                'txnscamt' => '0',
                'clientcode' => $this->config['client_code'],
                'txnid' => $merchantTxnId,
                'date' => date('d/m/Y'),
                'custacc' => $customerDetails['account_no'] ?? 'NA',
                'udf1' => $userId,
                'udf2' => 'Wallet Recharge',
                'udf3' => '',
                'udf4' => '',
                'ru' => $this->config['return_url'],
                'srvcetype' => 'WALLET',
                'signature' => ''
            ];
            
            // Generate and add signature
            $atomParams['signature'] = $this->generateRequestHash([
                'ttype' => $atomParams['ttype'],
                'tempTxnId' => $merchantTxnId,
                'amount' => $amount,
                'date' => $atomParams['date']
            ]);
            
            // Update transaction with Atom parameters
            $stmt = $this->db->prepare("
                UPDATE {$this->transactionTable} 
                SET transaction_id = ? 
                WHERE id = ?
            ");
            $stmt->execute([$merchantTxnId, $transactionId]);
            
            // Commit transaction
            $this->db->commit();
            
            // Log initiation
            $this->logInfo('Payment initiated', [
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'amount' => $amount
            ]);
            
            // Return payment form HTML
            return $this->generatePaymentForm($atomParams);
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logError('Payment initiation failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Generate auto-submit payment form
     */
    private function generatePaymentForm($params) {
        $paymentUrl = $this->config['test_mode'] ? 
                      $this->config['test_url'] : 
                      $this->config['prod_url'];
        
        $formHtml = '<form id="atomPaymentForm" action="' . htmlspecialchars($paymentUrl) . '" method="POST">';
        
        foreach ($params as $key => $value) {
            if ($key !== 'hash_key' && $key !== 'password') {
                $formHtml .= '<input type="hidden" name="' . htmlspecialchars($key) . '" 
                             value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $formHtml .= '</form>';
        $formHtml .= '<script>document.getElementById("atomPaymentForm").submit();</script>';
        
        return $formHtml;
    }
    
    /**
     * Process payment callback/response
     */
    public function processCallback($responseData) {
        try {
            // Start transaction with serializable isolation
            $this->db->beginTransaction();
            
            // Extract transaction reference
            $merchantTxnId = $responseData['mer_txn'] ?? $responseData['txnid'];
            $atomTxnId = $responseData['mmp_txn'] ?? $responseData['atomtxnId'];
            $statusCode = $responseData['f_code'] ?? $responseData['f_code'];
            
            if (!$merchantTxnId) {
                throw new Exception('Merchant transaction ID missing');
            }
            
            // Get transaction from database with lock
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->transactionTable} 
                WHERE merchant_txn_id = ? 
                FOR UPDATE
            ");
            $stmt->execute([$merchantTxnId]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$transaction) {
                throw new Exception('Transaction not found: ' . $merchantTxnId);
            }
            
            // Check if already processed (idempotency)
            if ($transaction['status'] !== 'PENDING') {
                $this->logInfo('Duplicate callback received', [
                    'transaction_id' => $transaction['id'],
                    'current_status' => $transaction['status']
                ]);
                
                $this->db->commit();
                return [
                    'success' => true,
                    'status' => $transaction['status'],
                    'message' => 'Transaction already processed'
                ];
            }
            
            // Verify hash signature
            if (!$this->verifyResponseHash($responseData)) {
                throw new Exception('Invalid response signature');
            }
            
            // Determine transaction status
            $status = $this->mapAtomStatus($statusCode);
            $paymentMode = $responseData['discriminator'] ?? 'Unknown';
            
            // Update transaction record
            $stmt = $this->db->prepare("
                UPDATE {$this->transactionTable} 
                SET status = ?,
                    transaction_id = ?,
                    payment_mode = ?,
                    bank_name = ?,
                    card_type = ?,
                    pg_reference = ?,
                    atom_response = ?,
                    callback_received = 1,
                    updated_at = NOW()
                WHERE id = ? AND status = 'PENDING'
            ");
            
            $updated = $stmt->execute([
                $status,
                $atomTxnId,
                $paymentMode,
                $responseData['bank_name'] ?? null,
                $responseData['card_type'] ?? null,
                $responseData['pg_ref'] ?? null,
                json_encode($responseData),
                $transaction['id']
            ]);
            
            if (!$updated) {
                throw new Exception('Failed to update transaction');
            }
            
            // Credit wallet for successful transactions
            if ($status === 'SUCCESS' && !$transaction['wallet_credited']) {
                $this->creditWallet(
                    $transaction['user_id'],
                    $transaction['amount'],
                    $transaction['id']
                );
            }
            
            // Commit transaction
            $this->db->commit();
            
            // Log callback processing
            $this->logInfo('Callback processed successfully', [
                'transaction_id' => $transaction['id'],
                'status' => $status
            ]);
            
            return [
                'success' => true,
                'status' => $status,
                'transaction_id' => $transaction['id'],
                'amount' => $transaction['amount']
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logError('Callback processing failed: ' . $e->getMessage(), $responseData);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Credit wallet balance with double-credit prevention
     */
    private function creditWallet($userId, $amount, $transactionId) {
        // Double-check wallet not already credited
        $stmt = $this->db->prepare("
            SELECT wallet_credited FROM {$this->transactionTable} 
            WHERE id = ? AND wallet_credited = 0
            FOR UPDATE
        ");
        $stmt->execute([$transactionId]);
        
        if (!$stmt->fetch()) {
            $this->logError('Wallet already credited for transaction: ' . $transactionId);
            return false;
        }
        
        // Get current wallet balance
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->walletTable} 
            WHERE user_id = ? 
            FOR UPDATE
        ");
        $stmt->execute([$userId]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $oldBalance = $wallet ? $wallet['balance'] : 0;
        $newBalance = $oldBalance + $amount;
        
        // Update or create wallet record
        if ($wallet) {
            $stmt = $this->db->prepare("
                UPDATE {$this->walletTable} 
                SET balance = ?,
                    last_transaction_id = ?,
                    updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([$newBalance, $transactionId, $userId]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO {$this->walletTable} 
                (user_id, balance, last_transaction_id, updated_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $newBalance, $transactionId]);
        }
        
        // Mark transaction as credited
        $stmt = $this->db->prepare("
            UPDATE {$this->transactionTable} 
            SET wallet_credited = 1,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$transactionId]);
        
        // Create audit log
        $this->createTransactionLog(
            $userId,
            $transactionId,
            'WALLET_CREDIT',
            $oldBalance,
            $newBalance,
            $amount,
            'Wallet credited via Atom payment'
        );
        
        return true;
    }
    
    /**
     * Create transaction audit log
     */
    private function createTransactionLog($userId, $transactionId, $action, $oldBalance, $newBalance, $amount, $description) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->logTable} 
            (user_id, transaction_id, action, old_balance, new_balance, amount, description, ip_address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $transactionId,
            $action,
            $oldBalance,
            $newBalance,
            $amount,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    }
    
    /**
     * Map Atom status code to internal status
     */
    private function mapAtomStatus($fCode) {
        $statusMap = [
            'Ok' => 'SUCCESS',
            'CANCELLED' => 'CANCELLED',
            'FAILED' => 'FAILED',
            'PENDING' => 'PENDING'
        ];
        
        return $statusMap[$fCode] ?? 'FAILED';
    }
    
    /**
     * Get transaction details
     */
    public function getTransactionDetails($transactionId) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->transactionTable} 
            WHERE id = ? OR merchant_txn_id = ?
        ");
        $stmt->execute([$transactionId, $transactionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user wallet balance
     */
    public function getWalletBalance($userId) {
        $stmt = $this->db->prepare("
            SELECT balance FROM {$this->walletTable} 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $wallet ? $wallet['balance'] : 0;
    }
    
    /**
     * Logging methods (implement based on your logging framework)
     */
    private function logInfo($message, $context = []) {
        if ($this->logger) {
            $this->logger->info($message, $context);
        }
        // Fallback to error_log
        error_log("[INFO] Atom Payment: " . $message . " " . json_encode($context));
    }
    
    private function logError($message, $context = []) {
        if ($this->logger) {
            $this->logger->error($message, $context);
        }
        error_log("[ERROR] Atom Payment: " . $message . " " . json_encode($context));
    }
}