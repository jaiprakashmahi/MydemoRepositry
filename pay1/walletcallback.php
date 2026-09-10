<?php
/**
 * Atom Payment Gateway Callback Handler
 * This file receives the payment response from Atom
 */
session_start();
require_once '../config/database.php';
require_once '../classes/AtomPaymentGateway.php';

try {
    // Initialize gateway
    $gateway = new AtomPaymentGateway($db);
    
    // Log incoming request
    error_log('Atom Callback Received: ' . json_encode($_POST));
    
    // Process the callback
    $result = $gateway->processCallback($_POST);
    
    // Check processing result
    if ($result['success']) {
        // Redirect based on transaction status
        switch ($result['status']) {
            case 'SUCCESS':
                $_SESSION['success_message'] = 'Payment successful! Your wallet has been credited with ₹' . 
                    number_format($result['amount'], 2);
                header('Location: /wallet/status.php?status=success&txn=' . $result['transaction_id']);
                break;
                
            case 'PENDING':
                $_SESSION['info_message'] = 'Payment is pending. We will update your wallet once confirmed.';
                header('Location: /wallet/status.php?status=pending&txn=' . $result['transaction_id']);
                break;
                
            case 'FAILED':
                $_SESSION['error_message'] = 'Payment failed. Please try again.';
                header('Location: /wallet/status.php?status=failed&txn=' . $result['transaction_id']);
                break;
                
            case 'CANCELLED':
                $_SESSION['info_message'] = 'Payment was cancelled.';
                header('Location: /wallet/status.php?status=cancelled&txn=' . $result['transaction_id']);
                break;
                
            default:
                header('Location: /wallet/add-money.php');
        }
    } else {
        // Log failed callback
        error_log('Atom Callback Processing Failed: ' . ($result['error'] ?? 'Unknown error'));
        $_SESSION['error_message'] = 'Unable to process payment. Please contact support.';
        header('Location: /wallet/add-money.php');
    }
    
} catch (Exception $e) {
    // Log critical error
    error_log('Critical Error in Atom Callback: ' . $e->getMessage());
    $_SESSION['error_message'] = 'System error. Please contact support.';
    header('Location: /wallet/add-money.php');
}