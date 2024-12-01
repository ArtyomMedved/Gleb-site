<?php
// Подключаем базу данных
include('db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Получаем список всех товаров
$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
</head>
<body>

<div class="container">
    <h1>Управление товарами</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Описание</th>
                <th>Изображение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><img src="<?php echo $product['image']; ?>" alt="image" width="100"></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Редактировать</a> |
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin.php">Добавить новый товар</a>
</div>

</body>
</html>
