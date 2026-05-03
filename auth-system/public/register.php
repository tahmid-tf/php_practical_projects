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

    // ✅ Get raw input
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if (empty($errors)) {

        // ✅ Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Prepare statement
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ");

        try {
            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $hashedPassword,
            ]);

            echo "User created successfully";

        } catch (PDOException $e) {
            $errors[] = "Email already exists";
        }
    }
    }
?>

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
    <input type="text" name="name" placeholder="Name"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>

    <!-- ✅ CSRF token -->
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <button type="submit">Register</button>
</form>