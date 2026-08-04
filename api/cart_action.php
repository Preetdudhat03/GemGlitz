<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$action = sanitize($_POST['action'] ?? '');
$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
$user_id = get_current_user_id();
$session_id = get_session_id();

if ($action === 'add') {
    if (!$product_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
        exit;
    }

    // Check stock
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    // Check if item already exists in cart
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? AND user_id IS NULL");
        $stmt->execute([$session_id, $product_id]);
    }
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        $new_qty = $cart_item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_qty, $cart_item['id']]);
    } else {
        if ($user_id) {
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$session_id, $product_id, $quantity]);
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Added {$product['name']} to cart!",
        'cart_count' => get_cart_count()
    ]);
    exit;
}

if ($action === 'update') {
    $cart_id = (int)($_POST['cart_id'] ?? 0);
    if ($cart_id && $quantity > 0) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$quantity, $cart_id]);
        echo json_encode(['status' => 'success', 'message' => 'Cart updated', 'cart_count' => get_cart_count()]);
        exit;
    }
}

if ($action === 'remove') {
    $cart_id = (int)($_POST['cart_id'] ?? 0);
    if ($cart_id) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$cart_id]);
        echo json_encode(['status' => 'success', 'message' => 'Item removed from cart', 'cart_count' => get_cart_count()]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
exit;
?>
