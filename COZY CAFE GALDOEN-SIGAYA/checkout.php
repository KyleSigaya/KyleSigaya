<?php 
// checkout.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sessionID = session_id();

// Get cart ID
$stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id = :sid LIMIT 1");
$stmt->execute(['sid' => $sessionID]);
$cart = $stmt->fetch();

if (!$cart) {
    header("Location: menu.php");
    exit;
}

$cartID = $cart['id'];

// Fetch cart items
$stmt2 = $pdo->prepare("
    SELECT ci.menu_item_id, ci.qty, m.name, m.price
    FROM cart_items ci
    JOIN menu_items m ON m.id = ci.menu_item_id
    WHERE ci.cart_id = :cid
");
$stmt2->execute(['cid' => $cartID]);
$cartItems = $stmt2->fetchAll();

if (!$cartItems) {
    header("Location: menu.php");
    exit;
}

// Calculate total
$total = 0;
foreach ($cartItems as $ci) {
    $total += $ci['price'] * $ci['qty'];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment = $_POST['payment'] ?? 'cash';

    if ($name === '' || $phone === '') {
        $errors[] = "Name and phone are required.";
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insert order
            $stmt = $pdo->prepare("
                INSERT INTO orders 
                (user_id, customer_name, phone, address, payment_method, total, created_at)
                VALUES (:uid, :name, :phone, :address, :payment, :total, NOW())
            ");

            $stmt->execute([
                'uid' => $_SESSION['user_id'],
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'payment' => $payment,
                'total' => $total
            ]);

            $orderId = $pdo->lastInsertId();

            // Insert order items
            $stmtItem = $pdo->prepare("
                INSERT INTO order_items (order_id, menu_item_id, qty, price)
                VALUES (:oid, :mid, :qty, :price)
            ");

            foreach ($cartItems as $ci) {
                $stmtItem->execute([
                    'oid' => $orderId,
                    'mid' => $ci['menu_item_id'],
                    'qty' => $ci['qty'],
                    'price' => $ci['price']
                ]);
            }

            // Clear cart
            $pdo->prepare("DELETE FROM cart_items WHERE cart_id = :cid")
                ->execute(['cid' => $cartID]);

            $pdo->prepare("DELETE FROM carts WHERE id = :cid")
                ->execute(['cid' => $cartID]);

            $pdo->commit();

            header("Location: order_success.php?id=" . $orderId);
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Order failed: " . $e->getMessage();
        }
    }
}

function h($s) { return htmlspecialchars($s); }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout — Cozy Drip</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body{font-family:Arial;background:#fff8ef;margin:0;color:#2b1f17}
        .container{max-width:1100px;margin:28px auto;padding:0 18px}
        .card{background:#fff;padding:22px;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.06)}
        label{display:block;margin-top:10px;font-weight:600}
        input,textarea,select{width:100%;padding:10px;border-radius:8px;border:1px solid #eee;margin-top:6px}
        .btn-place{background:#7b3a12;color:#fff;padding:10px 16px;border:none;border-radius:8px;cursor:pointer;margin-top:14px}
        .summary{background:#fff;padding:12px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.05)}
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h1>Checkout</h1>

    <?php if (!empty($errors)): ?>
        <div style="color:#a33;margin-bottom:12px">
            <?php foreach ($errors as $e) echo h($e) . "<br>"; ?>
        </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:20px">
        <div class="card">
            <form method="post">
                <label>Full name</label>
                <input name="name" required value="<?= h($_POST['name'] ?? ($_SESSION['username'] ?? '')) ?>">

                <label>Phone</label>
                <input name="phone" required value="<?= h($_POST['phone'] ?? '') ?>">

                <label>Address (optional)</label>
                <input name="address" value="<?= h($_POST['address'] ?? '') ?>">

                <label>Payment method</label>
                <select name="payment">
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="card">Card</option>
                </select>

                <button class="btn-place" type="submit">
                    Place Order — ₱<?= number_format($total,2) ?>
                </button>
            </form>
        </div>

        <aside class="summary">
            <h3>Order Summary</h3>
            <?php foreach ($cartItems as $ci): ?>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee">
                    <div>
                        <div style="font-weight:700"><?= h($ci['name']) ?></div>
                        <div style="color:#666;font-size:13px">x <?= (int)$ci['qty'] ?></div>
                    </div>
                    <div>₱<?= number_format($ci['price'] * $ci['qty'],2) ?></div>
                </div>
            <?php endforeach; ?>

            <div style="margin-top:12px;font-weight:700;display:flex;justify-content:space-between">
                <div>Total</div><div>₱<?= number_format($total,2) ?></div>
            </div>
        </aside>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
