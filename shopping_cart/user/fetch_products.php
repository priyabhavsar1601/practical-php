<?php
session_start();
include '../includes/db.php'; // Include your MySQLi connection

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6; // Number of products per page
$offset = ($page - 1) * $limit;

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Count total products based on category
if ($category_id === null || $category_id === '') {
    $count_stmt = $mysqli->prepare("SELECT COUNT(*) FROM products");
} else {
    $count_stmt = $mysqli->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $count_stmt->bind_param("i", $category_id);
}
$count_stmt->execute();
$count_stmt->bind_result($total_products);
$count_stmt->fetch();
$count_stmt->close();

$total_pages = ceil($total_products / $limit);

// Fetch products based on category and pagination
if ($category_id === null || $category_id === '') {
    $stmt = $mysqli->prepare("SELECT * FROM products LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
} else {
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ?, ?");
    $stmt->bind_param("iii", $category_id, $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Return response as JSON
header('Content-Type: application/json');
echo json_encode(['products' => $products, 'total_pages' => $total_pages]);
?>
