<?php
// Подключаем базу данных
include('db.php');
session_start(); // Начинаем сессию

// Проверяем, авторизован ли пользователь и является ли он администратором
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Если не администратор, перенаправляем на главную страницу или другую страницу
    header("Location: index.php"); // Измените на URL вашей главной страницы
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    // Убираем все символы, кроме цифр и точки (для десятичных чисел)
    $price = preg_replace('/[^\d.]/', '', $_POST['price']);
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageError = $_FILES['image']['error'];
    $imageType = $_FILES['image']['type'];

    // Проверка, существует ли товар с таким же именем
    $sql_check = "SELECT COUNT(*) FROM products WHERE name = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$name]);
    $exists = $stmt_check->fetchColumn();

    if ($exists) {
        echo "<div class='message error'>Товар с таким именем уже существует!</div>";
    } else {
        // Проверка на тип изображения (JPEG или PNG)
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($imageType, $allowedTypes)) {
            echo "<div class='message error'>Разрешены только изображения в формате JPEG или PNG.</div>";
        }
        // Проверка на размер изображения (например, до 2 MB)
        elseif ($imageSize > 2 * 1024 * 1024) {
            echo "<div class='message error'>Размер изображения не должен превышать 2 MB.</div>";
        } elseif ($imageError !== UPLOAD_ERR_OK) {
            echo "<div class='message error'>Ошибка загрузки изображения.</div>";
        } else {
            // Генерируем уникальное имя для изображения (чтобы избежать конфликтов)
            $imageName = uniqid() . '_' . basename($image);
            $imagePath = 'uploads/' . $imageName;

            // Перемещаем загруженное изображение в папку
            if (move_uploaded_file($imageTmp, $imagePath)) {
                echo "<div class='message success'>Изображение загружено успешно!</div>";

                // SQL запрос на добавление товара с сохранением только имени изображения
                $sql = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $price, $description, $imageName]);

                echo "<div class='message success'>Товар добавлен!</div>";
            } else {
                echo "<div class='message error'>Ошибка перемещения изображения.</div>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #444;
        }

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .success {
            background-color: #e0f7e0;
            color: #2e7d32;
        }

        .error {
            background-color: #ffcccb;
            color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Добавить товар</h1>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Название товара:</label>
        <input type="text" name="name" required><br>
        
        <label for="price">Цена:</label>
        <input type="text" name="price" required><br>
        
        <label for="description">Описание:</label>
        <textarea name="description" required></textarea><br>
        
        <label for="image">Изображение:</label>
        <input type="file" name="image" required><br>
        
        <button type="submit">Добавить товар</button>
    </form>
</div>

</body>
</html>
