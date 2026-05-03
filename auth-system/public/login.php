<?php

    // ✅ Secure session setup (MUST be before session_start)
    session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false, // change to true in HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
    ]);

    session_start();

    require_once '../config/database.php';
    require_once '../includes/csrf.php';

    // If already logged in → redirect
    if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ CSRF check
    if (! verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token");
    }

    // ✅ Get input
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Basic validation
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {

        // ✅ Fetch user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user) {

            // ✅ Check login attempts (max 5 within 5 minutes)
            if ($user['login_attempts'] >= 5 && strtotime($user['last_attempt']) > strtotime('-5 minutes')) {
                $errors[] = "Too many attempts. Try again later.";
            } else {

                // ✅ Verify password
                if (password_verify($password, $user['password'])) {

                    // 🔥 IMPORTANT: regenerate session ID
                    session_regenerate_id(true);

                    // ✅ Reset login attempts
                    $stmt = $pdo->prepare("UPDATE users SET login_attempts = 0 WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    // ✅ Store session
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['name'];

                    // Redirect
                    header("Location: dashboard.php");
                    exit;

                } else {

                    // ❌ Wrong password → increase attempts
                    $stmt = $pdo->prepare("
                        UPDATE users
                        SET login_attempts = login_attempts + 1, last_attempt = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$user['id']]);

                    $errors[] = "Invalid credentials";
                }
            }

        } else {
            // ❌ Do NOT reveal email existence
            $errors[] = "Invalid credentials";
        }
    }
    }
?>

<!-- ✅ Show Errors -->
<?php if (! empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- ✅ Login Form -->
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>

    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <button type="submit">Login</button>
</form>