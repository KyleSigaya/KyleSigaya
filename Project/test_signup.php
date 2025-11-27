<?php

require_once 'db.php';

$name = 'testuser2';
$password = 'password';
$phone = '0987654321';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hashed_password . "\n";

$stmt = $conn->prepare("INSERT INTO aquaguard (name, password, phone, requested_at) VALUES (?, ?, ?, NOW())");
if ($stmt) {
    $stmt->bind_param("sss", $name, $hashed_password, $phone);
    if ($stmt->execute()) {
        echo "User inserted successfully. ID: " . $conn->insert_id . "\n";
    } else {
        echo "Failed to insert user: " . $stmt->error . "\n";
    }
    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error . "\n";
}

$conn->close();
?>
