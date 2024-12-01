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

    // Получаем информацию о товаре, чтобы удалить изображение, если оно есть
    $sql = "SELECT image FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Удаляем изображение, если оно существует
        if (file_exists($product['image'])) {
            unlink($product['image']);
        }

        // Удаляем товар из базы данных
        $sql_delete = "DELETE FROM products WHERE id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$product_id]);

        echo "<div class='message success'>Товар удален!</div>";
    } else {
        echo "<div class='message error'>Товар не найден!</div>";
    }
} else {
    echo "<div class='message error'>ID товара не указан!</div>";
}
?>
