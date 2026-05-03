<?php

    session_start();
    require_once '../config/database.php';

    $token = $_GET['token'] ?? '';

    $stmt = $pdo->prepare("
    SELECT * FROM users
    WHERE reset_token = ? AND reset_token_expiry > NOW()
");
    $stmt->execute([$token]);

    $user = $stmt->fetch();

    if (! $user) {
    die("Invalid or expired token");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'];

    if (strlen($password) < 6) {
        die("Password too short");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password & clear token
    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?, reset_token = NULL, reset_token_expiry = NULL
        WHERE id = ?
    ");
    $stmt->execute([$hashedPassword, $user['id']]);

    echo "Password updated successfully!";
    }
?>

<form method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
