<?php
session_start();
if (isset($_SESSION['user_id'])) header('Location: index.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#fff8ef}
.box{max-width:420px;margin:80px auto;padding:20px;background:white;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.06)}
input{width:100%;padding:10px;margin-top:8px;border-radius:8px;border:1px solid #eee}
.btn{background:#7b3a12;color:white;padding:10px 14px;border:none;border-radius:8px;margin-top:10px;cursor:pointer}
.small{font-size:0.9em;margin-top:8px}

.success-msg{
    color:green;background:#e0ffe0;padding:8px 12px;border-radius:8px;margin-top:12px;text-align:center;
}
.error-msg{
    color:red;background:#ffe0e0;padding:8px 12px;border-radius:8px;margin-top:12px;text-align:center;
}

.social-login{text-align:center;margin-top:20px}
.social-login .icons img{width:32px;margin:6px;cursor:pointer}
</style>
</head>

<body>
<?php include 'navbar.php'; ?>

<div class="box">
  <h2>Login</h2>

  <?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>
    <div class="success-msg">Login successful!</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
    <div class="error-msg">Invalid username or password.</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'missing'): ?>
    <div class="error-msg">Please enter both username and password.</div>
<?php endif; ?>


  <form action="login_process.php" method="post">
    <input name="username" placeholder="Username or email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button class="btn" type="submit">Login</button>

    <div class="social-login">
        <p>Or continue with</p>
        <div class="icons">
            <a href="#"><img src="assets/google.png"></a>
            <a href="#"><img src="assets/facebook.png"></a>
            <a href="#"><img src="assets/twitter.png"></a>
        </div>
    </div>
  </form>

  <div class="small">Don't have an account? <a href="signup.php">Sign up</a></div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
