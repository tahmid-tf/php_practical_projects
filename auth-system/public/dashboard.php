<?php

    session_start();
    require_once '../includes/auth.php';
    require_once '../includes/session.php';
    requireAuth();

?>

<?php include '../templates/header.php'; ?>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></h1>
    <p>This is your secure dashboard.</p>
    <a href="logout.php">Logout</a>

<?php include '../templates/footer.php'; ?>
