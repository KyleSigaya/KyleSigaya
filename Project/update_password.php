<?php
require_once 'db.php';

$password = 'password'; // Replace with the actual password used during signup
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE aquaguard SET password = ? WHERE id = 1");
if ($stmt) {
    $stmt->bind_param("s", $hashed_password);
    if ($stmt->execute()) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password.";
    }
    $stmt->close();
} else {
    echo "Prepare failed.";
}
$conn->close();
?>
