<?php
session_start();

// Удаляем все данные сессии
session_unset();
session_destroy();

// Удаляем cookie, если оно есть
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Удаляем cookie
}

// Перенаправляем на главную страницу
header("Location: index.php");
exit;
?>
