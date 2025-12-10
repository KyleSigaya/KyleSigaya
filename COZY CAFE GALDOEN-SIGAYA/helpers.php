<?php
// helpers.php
function ensure_cart_session() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['cart_id'])) {
        // create or find cart by session id
        $_SESSION['cart_id'] = session_id();
        $_SESSION['cart_count'] = 0;
    }
}
function update_cart_count(PDO $pdo) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $sid = session_id();
    // count items from cart_items via carts table
    $stmt = $pdo->prepare("SELECT c.id AS cart_id FROM carts c WHERE c.session_id = :sid LIMIT 1");
    $stmt->execute(['sid'=>$sid]);
    $row = $stmt->fetch();
    $count = 0;
    if ($row) {
        $cart_id = $row['cart_id'];
        $stmt2 = $pdo->prepare("SELECT SUM(qty) AS total FROM cart_items WHERE cart_id = :cart_id");
        $stmt2->execute(['cart_id'=>$cart_id]);
        $r = $stmt2->fetch();
        $count = (int)($r['total'] ?? 0);
    }
    $_SESSION['cart_count'] = $count;
    return $count;
}
