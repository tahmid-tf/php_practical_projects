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

//unsafe

// $sql  = "SELECT * FROM posts";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();

// while ($row = $stmt->fetch()) {
//     echo $row->title . "<br>";
// }

//safe

// $sql  = "SELECT * FROM posts WHERE author = :author" . " AND is_published = :is_published";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['author' => 'brad', 'is_published' => 1]);
// $row = $stmt->fetchAll();

// foreach ($row as $post) {
//     echo $post->title . "<br>";
// }

// get row count

// $sql  = "SELECT * FROM posts";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();
// $row = $stmt->rowCount();
// echo $row;

// insert data

// $sql  = "INSERT INTO posts (title, body, is_published, author) VALUES (:title, :body, :is_published, :author)";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['title' => 'Post Four', 'body' => 'This is post three', 'is_published' => 1, 'author' => 'Tahmid']);
