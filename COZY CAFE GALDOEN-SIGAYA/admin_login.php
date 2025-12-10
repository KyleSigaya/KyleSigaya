<?php
session_start();
if(isset($_POST['login'])){
  if($_POST['user']=='admin' && $_POST['pass']=='1234'){
    $_SESSION['admin']=true; header('Location: admin.php'); exit;
  } else $err='Invalid';
}
?>
<!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>
<form method="post">
  <h3>Admin Login</h3>
  <input name="user" required placeholder="admin"><br>
  <input name="pass" required type="password"><br>
  <button name="login">Login</button>
  <?php if(!empty($err)) echo '<p>'.$err.'</p>'; ?>
</form>
</body></html>
