<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pdoposts';

// pdo connection

    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // pdo query

    // $stmt = $pdo->query("SELECT * FROM posts");

    // while ($row = $stmt->fetch()) {
    //     echo $row->title . "<br>";
    // }


// prepared statements (prepare & execute)