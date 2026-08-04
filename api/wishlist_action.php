<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$user_id = get_current_user_id();

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to add items to your wishlist']);
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);

if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    exit;
}

// Check if already in wishlist
$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$existing = $stmt->fetch();

if ($existing) {
    // Remove from wishlist
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = ?");
    $stmt->execute([$existing['id']]);
    echo json_encode([
        'status' => 'success',
        'in_wishlist' => false,
        'message' => 'Removed from wishlist',
        'wishlist_count' => get_wishlist_count()
    ]);
} else {
    // Add to wishlist
    $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);
    echo json_encode([
        'status' => 'success',
        'in_wishlist' => true,
        'message' => 'Added to your luxury wishlist!',
        'wishlist_count' => get_wishlist_count()
    ]);
}
exit;
?>
