<?php
session_start();
include('db.php');

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Перенаправляем на страницу авторизации
    exit;
}

$user_id = $_SESSION['id'];  // Используем $_SESSION['id'] для получения user_id
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];  // Теперь используем переменную username
    $email = $_POST['email'];

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error'>Неверный формат email.</div>";
    } else {
        // Обновляем данные пользователя
        $sql_update = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$username, $email, $user_id]);

        if ($stmt_update->rowCount() > 0) {
            echo "<div class='success'>Данные обновлены!</div>";
        } else {
            echo "<div class='error'>Ошибка обновления данных.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать профиль</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus {
            border-color: #a67541;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #a67541;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #8a5931;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактировать профиль</h1>

        <form method="POST">
            <label for="username">Имя пользователя:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
</body>
</html>
