<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = ($_POST['product_id']);
$size = $_POST['size'] ?? 'No size';


$sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $product_id, $size);

if ($stmt->execute()) {
    header("Location: ./cart.php");
    exit();
} else {
    header("Location: ./cart.php?remove=fail");
    exit();
}

$stmt->close();
$conn->close();
