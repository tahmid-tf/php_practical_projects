<?php

    session_start();
    require_once '../config/database.php';

    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {

        // Generate token
        $token  = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save to DB
        $stmt = $pdo->prepare("
            UPDATE users
            SET reset_token = ?, reset_token_expiry = ?
            WHERE email = ?
        ");
        $stmt->execute([$token, $expiry, $email]);

        // Simulate email (IMPORTANT)
        $resetLink = "http://localhost/auth-system/public/reset-password.php?token=$token";

        echo "Reset link (copy this): <br>";
        echo "<a href='$resetLink'>$resetLink</a>";

    } else {
        $message = "Email not found";
    }
    }
?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>

<p><?php echo htmlspecialchars($message); ?></p>