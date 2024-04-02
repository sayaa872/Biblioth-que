<?php


session_start();
$host = 'localhost:3306';
$db   = 'bibli';
$user = 'root';
$password  = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $opt);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($success) {
    $user_id = $pdo->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    header('Location: index.php');
    exit();
}