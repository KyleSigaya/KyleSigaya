<?php
session_start();
require 'db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order placed</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<div style="max-width:800px;margin:80px auto;padding:20px;text-align:center">
  <h2>Thanks — your order is placed!</h2>
  <p>
      Order #<?php echo htmlspecialchars($id); ?> has been received.<br>
      We will email updates to you shortly.
  </p>
  <a href="index.php" style="
    display:inline-block;
    padding:10px 25px;
    background:#7a3e10;
    color:white;
    text-decoration:none;
    border-radius:12px;
    font-size:16px;
    font-weight:500;
">Return Home</a>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
