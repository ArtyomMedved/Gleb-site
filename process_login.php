<?php
require 'db.php';
session_start(); // Начинаем сессию

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка существования пользователя в базе данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Проверка, что данные введены
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Заполните все поля';
        header('Location: login.php');
        exit();
    }

    if ($user && password_verify($password, $user['password_hash'])) {
        // Сессия
        session_regenerate_id(); // Обновляем идентификатор сессии
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Сохраняем роль пользователя

        // Устанавливаем cookie (опционально)
        setcookie('username', $user['username'], time() + 3600, "/"); // Сохраняем cookie на 1 час

        header('Location: index.php'); // Перенаправляем на главную страницу
        exit();
    } else {
        $_SESSION['login_error'] = 'Неверный логин или пароль';
        header('Location: login.php'); // Перенаправляем обратно на страницу входа
        exit();
    }
}
?>
