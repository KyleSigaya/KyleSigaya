<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
// get cart for session
$sid = session_id();
$stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id = :sid LIMIT 1");
$stmt->execute(['sid'=>$sid]); $row = $stmt->fetch();
$items = [];
$total = 0;
if ($row) {
    $stmt2 = $pdo->prepare("SELECT ci.id AS ciid, ci.qty, m.* FROM cart_items ci JOIN menu_items m ON m.id = ci.menu_item_id WHERE ci.cart_id = :cid");
    $stmt2->execute(['cid'=>$row['id']]);
    $items = $stmt2->fetchAll();
    foreach($items as $it) $total += $it['price'] * $it['qty'];
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Cart</title>
<style>
body{font-family:Poppins,Arial;background:#fff8ef;color:#222}
.container{max-width:900px;margin:30px auto;padding:0 16px}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:12px;border-bottom:1px solid #eee;text-align:left}
.checkout{background:#7b3a12;color:white;padding:12px 18px;border-radius:8px;border:none;cursor:pointer}
.qty-input{width:56px;padding:6px;border:1px solid #ddd;border-radius:6px}
.remove{background:#eee;border:none;padding:8px;border-radius:6px;cursor:pointer}
</style>
</head><body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Your Cart</h2>
  <?php if(!$items): ?>
    <p>Your cart is empty. <a href="menu.php">Browse menu</a></p>
  <?php else: ?>
    <form method="post" action="update_cart.php">
    <table class="table">
      <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
      <tbody>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?php echo htmlspecialchars($it['name']); ?></td>
          <td>₱<?php echo number_format($it['price'],2); ?></td>
          <td>
            <input class="qty-input" type="number" name="qty[<?php echo $it['ciid']; ?>]" value="<?php echo $it['qty']; ?>" min="0">
          </td>
          <td>₱<?php echo number_format($it['price'] * $it['qty'],2); ?></td>
          <td>
            <button formaction="remove_from_cart.php" name="remove" value="<?php echo $it['ciid']; ?>" class="remove">Remove</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div style="margin-top:18px;text-align:right">
      <strong>Total: ₱<?php echo number_format($total,2); ?></strong>
    </div>
    <div style="margin-top:14px;display:flex;gap:12px;justify-content:flex-end">
      <button type="submit" class="remove">Update Cart</button>
      <a href="checkout.php" class="checkout">Proceed to Checkout</a>
    </div>
    </form>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body></html>
