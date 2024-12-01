<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка существования пользователя в базе данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Заполните все поля';
        header('Location: login.php');
        exit();
    }

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(); // Обновляем идентификатор сессии
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        setcookie('username', $user['username'], time() + 3600, "/");

        header('Location: index.php'); // Перенаправляем на главную страницу
        exit();
    } else {
        $_SESSION['login_error'] = 'Неверный логин или пароль';
        header('Location: login.php'); // Перенаправляем обратно на страницу входа
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Авторизация</title>
    <style>
      body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
        background-color: #b3a9a3; /* Цвет фона */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .login-container {
        background-color: #2e221b; /* Тёмный фон блока */
        width: 909px;
        height: 475px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
      }

      .login-container h2 {
        color: rgb(255, 255, 255);
        font-family: Inter;
        font-size: 32px;
        font-weight: 400;
        line-height: 39px;
        letter-spacing: 0%;
        text-align: left;
        margin-bottom: 20px;
      }

      form {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
      }

      form input[type='text'],
      form input[type='password'],
      form input[type='email'] {
        width: 90%;
        height: 40px;
        margin-bottom: 20px;
        padding: 0 10px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        background-color: #e8e0da; /* Цвет полей */
      }

      form button {
        width: 90%;
        height: 40px;
        border: none;
        border-radius: 5px;
        background-color: #a67541; /* Цвет кнопки */
        color: #ffffff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      form button:hover {
        background-color: #8a5931; /* Цвет кнопки при наведении */
      }

      .login-container p {
        margin-top: 20px;
        font-size: 14px;
        color: #ffffff;
      }

      .login-container p a {
        color: #a67541;
        text-decoration: none;
        transition: color 0.3s ease;
      }

      .login-container p a:hover {
        color: #8a5931;
      }

      #register-container {
        display: none;
      }
    </style>
  </head>
  <body>
    <div class="login-container" id="login-container">
      <h1>Вход в систему</h1>
    
    <?php
    if (isset($_SESSION['login_error'])) {
        echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']);
    }
    ?>

    <form method="POST" action="login.php">
        <label for="username">Логин:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Войти</button>
    </form>
      <p>
        Нет аккаунта?
        <a href="#" onclick="showRegisterForm()">Зарегистрируйся!</a>
      </p>
    </div>

    <div class="login-container" id="register-container">
      <h2>Регистрация</h2>
      <form action="process_register.php" method="POST">
        <input type="text" name="username" placeholder="Введите логин..." required />
        <input
          type="email"
          name="email"
          placeholder="Введите почту..."
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Введите пароль..."
          required
        />
        <input
          type="password"
          name="confirm_password"
          placeholder="Повторите пароль..."
          required
        />
        <button type="submit">Зарегистрироваться</button>
      </form>
      <p>
        Уже есть аккаунт? <a href="#" onclick="showLoginForm()">Войдите!</a>
      </p>
    </div>

    <script>
      function showRegisterForm() {
        document.getElementById('login-container').style.display = 'none'
        document.getElementById('register-container').style.display = 'flex'
      }

      function showLoginForm() {
        document.getElementById('register-container').style.display = 'none'
        document.getElementById('login-container').style.display = 'flex'
      }
    </script>
  </body>
</html>
