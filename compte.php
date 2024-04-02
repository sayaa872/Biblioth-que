<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "bibli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT email, password FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($email, $password);
$stmt->fetch();

echo "Email: " . $email . "<br>";
echo "Password: " . $password . "<br>";

$stmt->close();
$conn->close();