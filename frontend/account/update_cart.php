<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? '';
$quantity = intval($_POST['quantity'] ?? 0);

if (!$product_id || $quantity < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $quantity, $user_id, $product_id, $size);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB error']);
}

$stmt->close();
$conn->close();
?>
