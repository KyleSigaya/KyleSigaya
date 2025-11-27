<?php

session_start();
require_once 'db.php';

$name = trim($_POST['name'] ?? '');
$password = trim($_POST['password'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name && $password && $phone) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO aquaguard (name, password, phone, requested_at) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $hashed_password, $phone);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $stmt->close();
            $conn->close();
            header('Location: dashboard.php');
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header('Location: index.html?error=registration_failed');
            exit();
        }
    } else {
        $conn->close();
            header('Location: index.html?error=database_error');
            exit();
    }
} else {
            header('Location: index.html?error=missing_fields');
            exit();
}
?>
