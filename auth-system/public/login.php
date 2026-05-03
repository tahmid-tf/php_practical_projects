<?php

    session_start();

    require_once '../config/database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header('Location: dashboard.php');

        exit();
    } else {
        echo "Invalid email or password";
    }
    }

?>

<form method="post">
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="submit" value="Login">
</form>
