<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$ciid = (int)($_POST['remove'] ?? 0);
if ($ciid) {
    $pdo->prepare("DELETE FROM cart_items WHERE id = :id")->execute(['id'=>$ciid]);
}
header('Location: cart.php');
exit;
