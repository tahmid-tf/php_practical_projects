<?php

    session_start();

    require_once '../config/database.php';
    require_once '../includes/csrf.php';
    require_once '../includes/session.php';

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ CSRF check FIRST
    if (! verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token");
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // ✅ Validation
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: dashboard.php');

            exit();
        } else {
            $errors[] = "Invalid email or password";
        }
    }

    }

?>

<?php include '../templates/header.php'; ?>

<?php if ($msg = getFlash('success')): ?>
    <p><?php echo htmlspecialchars($msg); ?></p>
<?php endif; ?>

<!-- ✅ Show errors -->
<?php if (! empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    <input type="submit" value="Login">
</form>

<?php include '../templates/footer.php'; ?>
