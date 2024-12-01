<?php
$host = '127.0.0.1';
$port = 8889;
$username = 'root';
$password = 'root';
$dbname = 'gothset';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
