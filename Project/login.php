<?php

session_start();
require_once 'db.php';

$name = trim($_POST['name'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($name && $password) {
    $stmt = $conn->prepare("SELECT id, password FROM aquaguard WHERE name = ?");
    if ($stmt) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $stmt->close();
                $conn->close();
                header('Location: dashboard.php');
                exit();
            } else {
                $stmt->close();
                $conn->close();
                header('Location: index.html?login_error=invalid_credentials');
                exit();
            }
        } else {
            $stmt->close();
            $conn->close();
                header('Location: index.html?login_error=invalid_credentials');
                exit();
        }
    } else {
        $conn->close();
                header('Location: index.html?login_error=database_error');
                exit();
    }
} else {
                header('Location: index.html?login_error=missing_fields');
                exit();
}
?>
