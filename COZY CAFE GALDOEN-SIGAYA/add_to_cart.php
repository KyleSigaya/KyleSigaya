<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'msg'=>'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'msg'=>'Invalid method']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$qty = isset($_POST['qty']) ? max(1,(int)$_POST['qty']) : 1;

if ($id <= 0) {
    echo json_encode(['success'=>false,'msg'=>'Invalid ID']);
    exit;
}

// fetch product
$stmt = $pdo->prepare("SELECT id FROM menu_items WHERE id = :id LIMIT 1");
$stmt->execute(['id'=>$id]);
$item = $stmt->fetch();

if (!$item) {
    echo json_encode(['success'=>false,'msg'=>'Item not found']);
    exit;
}

// -------------- CART LOGIC (DATABASE) -----------------

$sessionID = session_id();

// Create cart record if not exists
$stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id = :sid LIMIT 1");
$stmt->execute(['sid'=>$sessionID]);
$cart = $stmt->fetch();

if (!$cart) {
    $pdo->prepare("INSERT INTO carts (session_id) VALUES (:sid)")
        ->execute(['sid'=>$sessionID]);

    $cartID = $pdo->lastInsertId();
} else {
    $cartID = $cart['id'];
}

// check if item already exists
$stmt = $pdo->prepare("SELECT id, qty FROM cart_items WHERE cart_id = :cid AND menu_item_id = :mid LIMIT 1");
$stmt->execute(['cid'=>$cartID, 'mid'=>$id]);
$exists = $stmt->fetch();

if ($exists) {
    // update qty
    $newQty = $exists['qty'] + $qty;
    $pdo->prepare("UPDATE cart_items SET qty = :q WHERE id = :id")
        ->execute(['q'=>$newQty, 'id'=>$exists['id']]);
} else {
    // insert
    $pdo->prepare("INSERT INTO cart_items (cart_id, menu_item_id, qty) VALUES (:c,:m,:q)")
        ->execute(['c'=>$cartID, 'm'=>$id, 'q'=>$qty]);
}

// get updated count
$stmt = $pdo->prepare("
    SELECT SUM(qty) AS total 
    FROM cart_items 
    WHERE cart_id = :cid
");
$stmt->execute(['cid'=>$cartID]);
$count = (int)$stmt->fetch()['total'];

echo json_encode(['success'=>true,'count'=>$count,'msg'=>'Added to cart']);
exit;
