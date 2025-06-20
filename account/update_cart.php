<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$size = $_POST['size'] ?? '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;


$sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($sql);


$stmt->bind_param("iiss", $quantity, $user_id, $product_id, $size);

if ($stmt->execute()) {
    header("Location: ./cart.php");
    exit();
}  else {
    header("Location: ./cart.php?adding=fail");
    exit();
}

$stmt->close();
$conn->close();
