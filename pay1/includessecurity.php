<?php
/**
 * Security Helper Functions
 */

// CSRF Protection Token Generation
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF Token
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        throw new Exception('CSRF token validation failed');
    }
    return true;
}

// Rate Limiting for Payment Initiation
function checkRateLimit($userId, $action) {
    // Implement based on your requirements
    $redis = new Redis();
    $key = "rate_limit:{$action}:{$userId}";
    $count = $redis->incr($key);
    
    if ($count === 1) {
        $redis->expire($key, 300); // 5 minutes window
    }
    
    if ($count > 10) { // Max 10 requests per 5 minutes
        throw new Exception('Rate limit exceeded. Please try again later.');
    }
}

// Input Validation
function validatePaymentInput($amount) {
    $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount < 10 || $amount > 10000) {
        throw new Exception('Invalid payment amount');
    }
    return $amount;
}

// IP Whitelist Check for Callbacks
function verifyCallbackIP() {
    $allowedIPs = [
        '103.21.58.0/24',  // Replace with actual Atom IP ranges
        '103.21.59.0/24'
    ];
    
    $clientIP = $_SERVER['REMOTE_ADDR'];
    foreach ($allowedIPs as $cidr) {
        if (cidrMatch($clientIP, $cidr)) {
            return true;
        }
    }
    
    error_log('Callback from unauthorized IP: ' . $clientIP);
    return false;
}

// CIDR Match Helper
function cidrMatch($ip, $cidr) {
    list($subnet, $mask) = explode('/', $cidr);
    return (ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet);
}