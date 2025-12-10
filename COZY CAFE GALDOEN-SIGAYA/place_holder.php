<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];
$cart_id = (int)$_POST['cart_id'];

$stmt = $pdo->prepare("SELECT ci.qty, m.id AS mid, m.price FROM cart_items ci JOIN menu_items m ON m.id = ci.menu_item_id WHERE ci.cart_id = :cid");
$stmt->execute(['cid'=>$cart_id]); $items = $stmt->fetchAll();
if(!$items) { header('Location: cart.php'); exit; }

$total = 0; foreach($items as $it) $total += $it['price'] * $it['qty'];

// create order
$ins = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (:uid, :total, 'pending')");
$ins->execute(['uid'=>$user_id,'total'=>$total]);
$order_id = $pdo->lastInsertId();

// insert order items
$oi = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, qty, price) VALUES (:oid, :mid, :qty, :price)");
foreach($items as $it) {
    $oi->execute(['oid'=>$order_id,'mid'=>$it['mid'],'qty'=>$it['qty'],'price'=>$it['price']]);
}

// clear cart items and cart
$pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cid")->execute(['cid'=>$cart_id]);
$pdo->prepare("DELETE FROM carts WHERE id = :cid")->execute(['cid'=>$cart_id]);
$_SESSION['cart_count'] = 0;

header('Location: order_success.php?id='.$order_id);
exit;
