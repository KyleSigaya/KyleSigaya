<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $email === '' || $password === '') {
    $errors[] = 'All fields are required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email.';
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
      header("Location: signup.php?error=exists");
      exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (username, email, password_hash, created_at)
                             VALUES (:username, :email, :hash, NOW())");

    $insert->execute([
      'username' => $username,
      'email' => $email,
      'hash' => $hash
    ]);

    header("Location: signup.php?signup=success");
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign up — Cozy Drip</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body{font-family:Poppins;background:#fff8ef;margin:0;color:#2b1f17}
.container{max-width:880px;margin:40px auto;padding:0 18px}
.card{background:#fff;padding:28px;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.06)}
label{display:block;margin-top:8px;font-weight:600}
input{width:100%;padding:10px;border-radius:8px;border:1px solid #e8e2da;margin-top:6px}
.btn-black{background:#000;color:#fff;padding:10px 14px;border:none;border-radius:8px;cursor:pointer;margin-top:12px}

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

<div class="container">
  <h2>Create account</h2>
  <div class="card">

    <?php if (!empty($errors)): ?>
      <p class="error-msg">
        <?php foreach ($errors as $e) echo htmlspecialchars($e)."<br>"; ?>
      </p>
    <?php endif; ?>

    <?php
    if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
      echo "<p class='success-msg'>Account created successfully! You may now log in.</p>";
    }

    if (isset($_GET['error']) && $_GET['error'] == 'exists') {
      echo "<p class='error-msg'>Email already exists.</p>";
    }
    ?>

    <form method="post" action="signup.php">
      <label>Username</label>
      <input name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

      <label>Email</label>
      <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

      <label>Password</label>
      <input type="password" name="password" required>

      <div class="social-login">
          <p>Or continue with</p>
          <div class="icons">
              <a href="#"><img src="assets/google.png"></a>
              <a href="#"><img src="assets/facebook.png"></a>
              <a href="#"><img src="assets/twitter.png"></a>
          </div>
      </div>

      <button class="btn-black" type="submit">Sign up</button>
    </form>

    <p style="margin-top:12px">Already have an account? <a href="login.php">Login</a></p>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
