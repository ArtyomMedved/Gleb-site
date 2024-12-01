<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die('Пароли не совпадают');
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);

        setcookie('username', $username, time() + 3600 * 24, '/');
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            die('Пользователь с таким логином или почтой уже существует');
        }
        die("Ошибка: " . $e->getMessage());
    }
}
?>
