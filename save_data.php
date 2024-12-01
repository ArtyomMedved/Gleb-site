<?php
require 'db.php';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка данных формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);

    if (!empty($name) && !empty($phone)) {
        $stmt = $pdo->prepare("INSERT INTO form_submissions (name, phone) VALUES (:name, :phone)");
        $stmt->execute(['name' => $name, 'phone' => $phone]);
        echo "Данные успешно сохранены!";
    } else {
        echo "Пожалуйста, заполните все поля.";
    }
}
?>
