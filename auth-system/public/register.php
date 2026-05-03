<?php

    session_start();

    require_once '../config/database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // hash password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // stmt prepeare
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

    try {
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        echo "User created successfully";
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }
    }

?>

<form method="post">
    <input type="text" name="name" placeholder="Name"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit">Register</button>
</form>
