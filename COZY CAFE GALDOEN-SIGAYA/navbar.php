<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'db.php';

$logged_in = isset($_SESSION['user_id']);
$sessionID = session_id();
$cart_count = 0;

// Get cart ID
$stmt = $pdo->prepare("SELECT id FROM carts WHERE session_id = :sid LIMIT 1");
$stmt->execute(['sid' => $sessionID]);
$cart = $stmt->fetch();

if ($cart) {
    // Get total quantity
    $stmt2 = $pdo->prepare("SELECT SUM(qty) AS total FROM cart_items WHERE cart_id = :cid");
    $stmt2->execute(['cid' => $cart['id']]);

    $cart_count = (int)$stmt2->fetch()['total'];
}
?>
<header class="site-header">
  <div class="site-brand">
    <a href="index.php" class="brand">Cozy Drip</a>
  </div>

  <nav class="site-nav">
    <a href="index.php#home">Home</a>
    <a href="menu.php">Menu</a>
    <a href="index.php#about">About</a>
    <a href="index.php#contact">Contact</a>

    <?php if ($logged_in): ?>
      <a href="cart.php">
        Cart 
        <span id="cart-badge" class="navbar-cart-count">
          <?= $cart_count ?>
        </span>
      </a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>

<style>
.site-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:16px 32px;
  background:#fff;
  box-shadow:0 2px 6px rgba(0,0,0,0.05)
}
.brand{
  font-weight:700;
  color:#8b3d16;
  text-decoration:none
}
.site-nav a{
  margin-left:16px;
  color:#333;
  text-decoration:none
}
#cart-badge{
  background:#8b3d16;
  color:white;
  padding:2px 6px;
  border-radius:6px;
  font-size:0.9em;
  margin-left:6px
}
</style>
