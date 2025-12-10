<?php
session_start();
require 'db.php';
if(!isset($_SESSION['admin'])){ header('Location: admin_login.php'); exit; }
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = $_POST['name']; $desc = $_POST['desc']; $price = floatval($_POST['price']); $img = $_POST['img']; $cat = $_POST['cat'];
  $stmt = $pdo->prepare("INSERT INTO menu_items (name,description,price,img,category) VALUES (?,?,?,?,?)");
  $stmt->execute([$name,$desc,$price,$img,$cat]);
  $msg='Product added';
}
?>
<!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>
<h2>Add Product</h2>
<form method="post">
  <input name="name" placeholder="Name" required><br>
  <input name="price" placeholder="Price" required><br>
  <input name="img" placeholder="Image URL" required><br>
  <input name="cat" placeholder="Category (Coffee,Pastries,Food...)" required><br>
  <textarea name="desc" placeholder="Description"></textarea><br>
  <button type="submit">Add</button>
</form>
<?php if($msg) echo "<p>$msg</p>"; ?>
</body></html>
