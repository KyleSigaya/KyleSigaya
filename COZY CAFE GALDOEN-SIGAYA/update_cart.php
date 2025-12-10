<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$sid = session_id();
$stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id = :sid LIMIT 1");
$stmt->execute(['sid'=>$sid]); $row = $stmt->fetch();
if (!$row) { header('Location: cart.php'); exit; }
$cart_id = $row['id'];
if (!empty($_POST['qty']) && is_array($_POST['qty'])) {
    foreach($_POST['qty'] as $ciid => $q) {
        $q = (int)$q;
        if ($q <= 0) {
            $pdo->prepare("DELETE FROM cart_items WHERE id = :id AND cart_id = :cart")->execute(['id'=>$ciid,'cart'=>$cart_id]);
        } else {
            $pdo->prepare("UPDATE cart_items SET qty = :q WHERE id = :id AND cart_id = :cart")->execute(['q'=>$q,'id'=>$ciid,'cart'=>$cart_id]);
        }
    }
}
header('Location: cart.php');
exit;
