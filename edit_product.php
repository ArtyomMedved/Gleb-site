<?php
// Подключаем базу данных
include('db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Получаем текущие данные товара
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo "<div class='message error'>Товар не найден!</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    $price = preg_replace('/[^\d.]/', '', $_POST['price']);
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageError = $_FILES['image']['error'];
    $imageType = $_FILES['image']['type'];

    $imagePath = $product['image']; // если изображение не изменяется

    if ($image && $imageError === UPLOAD_ERR_OK) {
        // Загрузка нового изображения
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($imageType, $allowedTypes)) {
            echo "<div class='message error'>Разрешены только изображения в формате JPEG или PNG.</div>";
        } elseif ($imageSize > 2 * 1024 * 1024) {
            echo "<div class='message error'>Размер изображения не должен превышать 2 MB.</div>";
        } else {
            $imagePath = 'uploads/' . basename($image);
            move_uploaded_file($imageTmp, $imagePath);
        }
    }

    // SQL запрос на обновление товара
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $price, $description, $imagePath, $product_id]);

    echo "<div class='message success'>Товар обновлен!</div>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать товар</title>
    <style>
        /* Здесь тот же стиль, что и выше */
    </style>
</head>
<body>

<div class="container">
    <h1>Редактировать товар</h1>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Название товара:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        
        <label for="price">Цена:</label>
        <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>
        
        <label for="description">Описание:</label>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>
        
        <label for="image">Изображение:</label>
        <input type="file" name="image"><br>
        
        <button type="submit">Обновить товар</button>
    </form>
</div>

</body>
</html>
