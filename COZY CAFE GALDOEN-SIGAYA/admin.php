<?php
session_start();
require 'db.php';
if(!isset($_SESSION['admin'])){ header('Location: admin_login.php'); exit; }

$items = $pdo->query("SELECT * FROM menu_items ORDER BY created_at DESC")->fetchAll();
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>
<h2>Admin</h2>
<a href="add_product.php">Add product</a> | <a href="logout_admin.php">Logout</a>
<h3>Products</h3>
<table border=1>
<?php foreach($items as $i): ?>
<tr><td><?=$i['id']?></td><td><?=$i['name']?></td><td>₱<?=number_format($i['price'],2)?></td></tr>
<?php endforeach;?>
</table>

<h3>Orders</h3>
<table border=1>
<?php foreach($orders as $o): ?>
<tr><td>#<?=$o['id']?></td><td><?=$o['full_name']?></td><td>₱<?=number_format($o['total'],2)?></td></tr>
<?php endforeach;?>
</table>
</body></html>
