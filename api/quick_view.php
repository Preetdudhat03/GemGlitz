<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$product_id = (int)($_GET['id'] ?? 0);

if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if ($product) {
    echo json_encode(['status' => 'success', 'product' => $product]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}
exit;
?>
