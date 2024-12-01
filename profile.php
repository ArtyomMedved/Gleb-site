<?php
session_start();
include('db.php');

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Перенаправляем на страницу авторизации
    exit;
}

// Получаем информацию о пользователе из базы данных
$user_id = $_SESSION['id']; // Используем 'id' из сессии
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден!";
    exit;
}

// Проверяем роль пользователя (предположим, что роль хранится в базе данных)
$is_admin = ($user['role'] === 'admin'); // Проверяем, является ли пользователь администратором
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        .user-info {
            margin-bottom: 30px;
        }

        .user-info h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .user-info p {
            font-size: 18px;
            color: #666;
        }

        .user-info .created-at {
            color: #888;
            font-size: 14px;
        }

        .actions {
            margin-top: 30px;
        }

        .actions ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .actions li {
            margin-bottom: 15px;  /* Увеличиваем отступы между пунктами */
        }

        .actions a {
            display: block;  /* Чтобы ссылки занимали всю ширину li */
            text-decoration: none;
            color: #a67541;
            font-size: 18px;
            padding: 12px 20px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
        }

        .actions a:hover {
            background-color: #a67541;
            color: #fff;
        }

        /* Дополнительные стили для кнопки администратора */
        .admin-action {
            background-color: #d9534f; /* Красный цвет для кнопки администратора */
            color: #fff;
        }

        .admin-action:hover {
            background-color: #c9302c;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($user['username']); ?>!</h1>

        <div class="user-info">
            <h2>Ваша информация:</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Дата регистрации:</strong> <span class="created-at"><?php echo $user['created_at']; ?></span></p>
        </div>

        <div class="actions">
            <ul>
                <li><a href="edit_profile.php">Редактировать профиль</a></li>
                <li><a href="order_history.php">История заказов</a></li>
                <li><a href="logout.php">Выход</a></li>
                <?php if ($is_admin): ?>
                    <li><a href="admin_products.php" class="admin-action">Добавить Объявление</a></li> <!-- Кнопка для админа -->
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>
