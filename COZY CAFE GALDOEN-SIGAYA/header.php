<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="site-nav">
  <div class="nav-inner">
    <a class="brand" href="/cozy_cafe/">Cozy Drip</a>
    <div class="nav-links">
      <a href="/cozy_cafe/">Home</a>
      <a href="/cozy_cafe/menu.php">Menu</a>
      <a href="/cozy_cafe/#contact">Contact</a>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="/cozy_cafe/login.php" class="btn small">Login</a>
      <?php else: ?>
        <span class="user">Hi, <?=htmlspecialchars($_SESSION['display_name'] ?? $_SESSION['username'])?></span>
        <a href="/cozy_cafe/cart.php" class="btn small">Cart</a>
        <a href="/cozy_cafe/logout.php" class="btn small alt">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
