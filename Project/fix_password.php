<?php

require_once 'db.php';

$password = 'password'; // The plain text password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE aquaguard SET password = ? WHERE name = 'testuser'");
if ($stmt) {
    $stmt->bind_param("s", $hashed_password);
    if ($stmt->execute()) {
        echo "Password updated successfully.\n";
        echo "New hash: " . $hashed_password . "\n";
    } else {
        echo "Failed to update password.\n";
    }
    $stmt->close();
} else {
    echo "Failed to prepare statement.\n";
}

$conn->close();
?>
