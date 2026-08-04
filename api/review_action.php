<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$user_id = get_current_user_id();

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in to submit a review.']);
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 5);
$review_title = sanitize($_POST['review_title'] ?? '');
$comment = sanitize($_POST['comment'] ?? '');

if (!$product_id || empty($comment)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid comment and rating.']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, review_title, comment, status) VALUES (?, ?, ?, ?, ?, 'approved')");
$stmt->execute([$product_id, $user_id, $rating, $review_title, $comment]);

// Update product rating average & count
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as count FROM reviews WHERE product_id = ? AND status = 'approved'");
$stmt->execute([$product_id]);
$stats = $stmt->fetch();

$stmt = $pdo->prepare("UPDATE products SET rating = ?, review_count = ? WHERE id = ?");
$stmt->execute([round($stats['avg_rating'], 2), $stats['count'], $product_id]);

echo json_encode(['status' => 'success', 'message' => 'Thank you! Your review has been published.']);
exit;
?>
