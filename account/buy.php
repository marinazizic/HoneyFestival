<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$insert_sql = "
    INSERT INTO shipped_orders (user_id, product_id, price, quantity, size, added_at)
    SELECT user_id, product_id, price, quantity, size, added_at
    FROM cart
    WHERE user_id = ?
";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("i", $user_id);

if ($insert_stmt->execute()) {
    $delete_sql = "DELETE FROM cart WHERE user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {
        header("Location: ./cart.php?shipped=success");
    } else {
        header("Location: ./cart.php?shipped=delete_fail");
    }

    $delete_stmt->close();
} else {
    header("Location: ./cart.php?shipped=insert_fail");
}

$insert_stmt->close();
$conn->close();
exit();
