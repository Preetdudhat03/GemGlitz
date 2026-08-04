<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// Sanitize user inputs against XSS
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Generate CSRF Token
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF Token
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Format Price to Currency String
function format_price($amount) {
    return '$' . number_format((float)$amount, 2);
}

// Auth Helper: Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Auth Helper: Check if user is admin
function is_admin() {
    return is_logged_in() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Enforce login redirect
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        set_flash_message('error', 'Please log in to continue.');
        header('Location: login.php');
        exit;
    }
}

// Enforce admin redirect
function require_admin() {
    if (!is_admin()) {
        set_flash_message('error', 'Access denied. Administrator privileges required.');
        header('Location: login.php');
        exit;
    }
}

// Get logged-in User ID
function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Session identifier for guest cart/wishlist
function get_session_id() {
    if (empty($_SESSION['guest_session_id'])) {
        $_SESSION['guest_session_id'] = session_id();
    }
    return $_SESSION['guest_session_id'];
}

// Flash Message Helper
function set_flash_message($type, $message) {
    $_SESSION['flash_msg'] = [
        'type' => $type, // 'success', 'error', 'info'
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash_msg'])) {
        $msg = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        return $msg;
    }
    return null;
}

// Get Cart Items Count
function get_cart_count() {
    global $pdo;
    $user_id = get_current_user_id();
    $session_id = get_session_id();
    
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ? AND user_id IS NULL");
        $stmt->execute([$session_id]);
    }
    $result = $stmt->fetch();
    return (int)($result['total'] ?? 0);
}

// Get Wishlist Items Count
function get_wishlist_count() {
    global $pdo;
    $user_id = get_current_user_id();
    if (!$user_id) return 0;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    return (int)($result['total'] ?? 0);
}

// Helper to check if item is in user wishlist
function is_in_wishlist($product_id) {
    global $pdo;
    $user_id = get_current_user_id();
    if (!$user_id) return false;
    
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    return (bool)$stmt->fetch();
}
?>
