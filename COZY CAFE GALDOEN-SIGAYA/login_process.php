<?php
session_start();
require 'db.php';

$user = trim($_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if (!$user || !$pass) {
    header('Location: login.php?error=missing');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u OR email = :u LIMIT 1");
$stmt->execute(['u' => $user]);
$row = $stmt->fetch();

if ($row && password_verify($pass, $row['password_hash'])) {

    $_SESSION['user_id'] = $row['id'];

    // Update cart count
    require 'helpers.php';
    update_cart_count($pdo);

    header('Location: index.php?login=success');
    exit;
}

header('Location: login.php?error=invalid');
exit;
