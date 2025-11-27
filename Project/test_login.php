<?php

require_once 'db.php';

$name = 'testuser2';
$password = 'password';

$stmt = $conn->prepare("SELECT id, password FROM aquaguard WHERE name = ?");
if ($stmt) {
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        echo "Stored hash: " . $hashed_password . "\n";
        if (password_verify($password, $hashed_password)) {
            echo "Login successful for user ID: " . $user_id . "\n";
        } else {
            echo "Password verification failed.\n";
        }
    } else {
        echo "User not found.\n";
    }
    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error . "\n";
}

$conn->close();
?>
