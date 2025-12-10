<?php
session_start();
require 'db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Cart is empty.");
}

// Calculate total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_amount, created_at)
    VALUES (?, ?, NOW())
");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();

$order_id = $stmt->insert_id;

// Save items
$stmt2 = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)
");

foreach ($cart as $item) {
    $stmt2->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt2->execute();
}

// Clear cart
unset($_SESSION['cart']);

// Redirect to success page
header("Location: order_success.php?id=" . $order_id);
exit;
