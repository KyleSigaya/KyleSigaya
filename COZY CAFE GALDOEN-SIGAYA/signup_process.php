<?php
session_start();
require 'db.php';
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
if (!$username || !$password) { header('Location: signup.php'); exit; }
$hash = password_hash($password, PASSWORD_DEFAULT);
try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (:u,:p,:e)");
    $stmt->execute(['u'=>$username,'p'=>$hash,'e'=>$email]);
    $_SESSION['user_id'] = $pdo->lastInsertId();
    header('Location: index.php');
} catch (Exception $e) {
    // duplicate username
    header('Location: signup.php');
}
exit;
