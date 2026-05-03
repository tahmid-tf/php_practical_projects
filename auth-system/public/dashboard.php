<?php

    session_start();
    require_once '../config/database.php';
    require_once '../includes/auth.php';
    requireAuth();

    if (! isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
    <a href="logout.php">Logout</a>
</body>
</html>
