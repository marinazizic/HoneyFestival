<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

include "../account/db.php";

$user_id = $_SESSION['user_id'];
$product_id = $_POST['id'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$size = $_POST['size'];
$type = $_POST['type'];

header('Content-Type: application/json');

$check_sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND type = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iiss", $user_id, $product_id, $size, $type);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $existing = $check_result->fetch_assoc();
    $new_quantity = $existing['quantity'] + $quantity;

    $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ? AND type = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiiss", $new_quantity, $user_id, $product_id, $size, $type);

    if ($update_stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'redirect' => '../account/cart.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update cart item.'
        ]);
    }

    $update_stmt->close();
} else {
    $insert_sql = "INSERT INTO cart (user_id, product_id, price, quantity, size, type) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iidiss", $user_id, $product_id, $price, $quantity, $size, $type);

    if ($insert_stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'redirect' => '../account/cart.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add item to cart.'
        ]);
    }

    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
?>
