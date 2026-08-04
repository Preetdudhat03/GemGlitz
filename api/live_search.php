<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode(['status' => 'error', 'message' => 'Query too short', 'products' => []]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.price, p.main_image, c.name as category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? OR p.metal_type LIKE ? OR p.gemstone_type LIKE ?
    LIMIT 6
");
$search_term = "%{$query}%";
$stmt->execute([$search_term, $search_term, $search_term, $search_term, $search_term]);
$products = $stmt->fetchAll();

echo json_encode(['status' => 'success', 'products' => $products]);
exit;
?>
