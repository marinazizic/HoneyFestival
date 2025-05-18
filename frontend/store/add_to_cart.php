<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to add items to your cart.']);
    exit;
}

include "../account/db.php";

$user_id = $_SESSION['user_id'];
$product_id = $_POST['id'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$size = $_POST['size'];

header('Content-Type: application/json');

// Check if item already exists in cart (same product, same size)
$check_sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iis", $user_id, $product_id, $size);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Item exists → update quantity
    $existing = $check_result->fetch_assoc();
    $new_quantity = $existing['quantity'] + $quantity;

    $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiis", $new_quantity, $user_id, $product_id, $size);

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
    // Item doesn't exist → insert new row
    $insert_sql = "INSERT INTO cart (user_id, product_id, price, quantity, size) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iidis", $user_id, $product_id, $price, $quantity, $size);

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
